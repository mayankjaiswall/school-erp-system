<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use ZipArchive;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $students = Student::with('class')
            ->where('school_id', auth()->user()->school_id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('admission_no', 'like', "%{$search}%")
                        ->orWhere('roll_no', 'like', "%{$search}%")
                        ->orWhereHas('class', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('section', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->get();

        return view('principal.students.index', compact('students', 'search'));
    }

    public function create()
    {
        $school = School::where('id', auth()->user()->school_id)->firstOrFail();
        $classes = $this->schoolClasses()->get();

        return view('principal.students.create', compact('school', 'classes'));
    }

    public function store(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $validated = $request->validate($this->rules($schoolId));
        $validated['school_id'] = $schoolId;

        Student::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully.',
        ]);
    }

    public function edit($id)
    {
        $student = $this->schoolStudent($id);
        $classes = $this->schoolClasses()->get();

        return view('principal.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $student = $this->schoolStudent($id);
        $schoolId = auth()->user()->school_id;

        $validated = $request->validate($this->rules($schoolId, $student->id));
        $student->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully.',
        ]);
    }

    public function show($id)
    {
        $student = Student::with(['school', 'class'])
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($id);

        return view('principal.students.show', compact('student'));
    }

    public function destroy(Request $request, $id)
    {
        $student = $this->schoolStudent($id);
        $student->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully.',
            ]);
        }

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'students_file' => 'required|file|mimes:csv,txt,xlsx|max:5120',
        ]);

        $schoolId = auth()->user()->school_id;
        $extension = strtolower($request->file('students_file')->getClientOriginalExtension());

        if ($extension === 'xlsx' && ! class_exists(ZipArchive::class)) {
            return back()->with('error', 'XLSX import requires the PHP Zip extension. Please upload a CSV file instead.');
        }

        [$rows, $headerError] = $this->readImportRows($request->file('students_file')->getRealPath(), $extension);

        if ($headerError) {
            return back()->with('error', $headerError);
        }

        if (empty($rows)) {
            return back()->with('error', 'No student records found in the uploaded file.');
        }

        $classes = $this->schoolClasses()->get();
        $created = 0;
        $skipped = [];

        foreach ($rows as $index => $row) {
            $line = $index + 2;
            $classId = $this->resolveClassId($row, $classes);

            if (! $classId) {
                $skipped[] = "Row {$line}: Class not found.";
                continue;
            }

            $payload = [
                'school_id' => $schoolId,
                'class_id' => $classId,
                'admission_no' => $row['admission_no'] ?? null,
                'roll_no' => $row['roll_no'] ?? null,
                'name' => $row['name'] ?? null,
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'gender' => $this->normalizeGender($row['gender'] ?? null),
                'dob' => $this->normalizeDate($row['dob'] ?? null),
                'address' => $row['address'] ?? null,
                'status' => $this->normalizeStatus($row['status'] ?? null),
            ];

            $validator = Validator::make($payload, $this->rules($schoolId));

            if ($validator->fails()) {
                $skipped[] = "Row {$line}: ".$validator->errors()->first();
                continue;
            }

            Student::create($payload);
            $created++;
        }

        $message = "{$created} student".($created === 1 ? '' : 's').' imported successfully.';

        return back()
            ->with($created ? 'success' : 'error', $message)
            ->with('import_skipped', array_slice($skipped, 0, 10))
            ->with('import_skipped_count', count($skipped));
    }

    private function rules(int $schoolId, ?int $studentId = null): array
    {
        return [
            'class_id' => [
                'required',
                Rule::exists('school_classes', 'id')->where('school_id', $schoolId),
            ],
            'admission_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('students', 'admission_no')
                    ->where('school_id', $schoolId)
                    ->ignore($studentId),
            ],
            'roll_no' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|digits:10',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date',
            'address' => 'nullable|string',
            'photo' => 'nullable|string|max:255',
            'status' => 'required|in:1,0',
        ];
    }

    private function schoolClasses()
    {
        return SchoolClass::where('school_id', auth()->user()->school_id)
            ->where('status', 1)
            ->orderBy('name')
            ->orderBy('section');
    }

    private function schoolStudent($id): Student
    {
        return Student::where('school_id', auth()->user()->school_id)->findOrFail($id);
    }

    private function readImportRows(string $path, string $extension): array
    {
        $rows = strtolower($extension) === 'xlsx'
            ? $this->readXlsxRows($path)
            : $this->readCsvRows($path);

        if (count($rows) < 2) {
            return [[], null];
        }

        $headers = array_map(fn ($header) => $this->normalizeHeader($header), array_shift($rows));
        $headerError = $this->validateImportHeaders($headers);

        if ($headerError) {
            return [[], $headerError];
        }

        $records = [];

        foreach ($rows as $row) {
            $record = [];

            foreach ($headers as $index => $header) {
                if ($header !== '') {
                    $record[$header] = trim((string) ($row[$index] ?? ''));
                }
            }

            if (array_filter($record, fn ($value) => $value !== '')) {
                $records[] = $record;
            }
        }

        return [$records, null];
    }

    private function validateImportHeaders(array $headers): ?string
    {
        $headers = array_values(array_filter($headers));
        $allowed = [
            'name',
            'admission_no',
            'class',
            'class_name',
            'class_id',
            'section',
            'roll_no',
            'email',
            'phone',
            'gender',
            'dob',
            'address',
            'status',
        ];
        $unknown = array_diff($headers, $allowed);

        if ($unknown) {
            return 'Import stopped. These columns do not match the students table import format: '.implode(', ', $unknown).'.';
        }

        if (! in_array('name', $headers, true) || ! in_array('admission_no', $headers, true)) {
            return 'Import stopped. The file must include name and admission_no columns.';
        }

        if (! in_array('class_id', $headers, true) && ! in_array('class', $headers, true) && ! in_array('class_name', $headers, true)) {
            return 'Import stopped. The file must include class_id, class, or class_name column.';
        }

        return null;
    }

    private function readCsvRows(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'r');

        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }

    private function readXlsxRows(string $path): array
    {
        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            return [];
        }

        $sharedStrings = [];
        $sharedXml = $zip->getFromName('xl/sharedStrings.xml');

        if ($sharedXml !== false) {
            $xml = simplexml_load_string($sharedXml);

            foreach ($xml->si as $item) {
                $parts = [];

                if (isset($item->t)) {
                    $parts[] = (string) $item->t;
                }

                foreach ($item->r ?? [] as $run) {
                    $parts[] = (string) $run->t;
                }

                $sharedStrings[] = implode('', $parts);
            }
        }

        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            return [];
        }

        $xml = simplexml_load_string($sheetXml);
        $rows = [];

        foreach ($xml->sheetData->row as $row) {
            $values = [];

            foreach ($row->c as $cell) {
                $reference = (string) $cell['r'];
                $columnIndex = $this->columnIndex($reference);
                $value = (string) ($cell->v ?? '');

                if ((string) $cell['t'] === 's') {
                    $value = $sharedStrings[(int) $value] ?? '';
                } elseif ((string) $cell['t'] === 'inlineStr') {
                    $value = (string) ($cell->is->t ?? '');
                }

                $values[$columnIndex] = $value;
            }

            if ($values) {
                ksort($values);
                $rows[] = array_replace(array_fill(0, max(array_keys($values)) + 1, ''), $values);
            }
        }

        return $rows;
    }

    private function columnIndex(string $reference): int
    {
        preg_match('/^[A-Z]+/', strtoupper($reference), $matches);
        $letters = $matches[0] ?? 'A';
        $index = 0;

        foreach (str_split($letters) as $letter) {
            $index = ($index * 26) + (ord($letter) - 64);
        }

        return $index - 1;
    }

    private function normalizeHeader(string $header): string
    {
        $header = strtolower(trim($header));
        $header = preg_replace('/[^a-z0-9]+/', '_', $header);

        return trim($header, '_');
    }

    private function resolveClassId(array $row, $classes): ?int
    {
        if (! empty($row['class_id'])) {
            return $classes->firstWhere('id', (int) $row['class_id'])?->id;
        }

        $className = strtolower(trim((string) ($row['class'] ?? $row['class_name'] ?? '')));
        $section = strtolower(trim((string) ($row['section'] ?? '')));

        if ($className === '') {
            return null;
        }

        return $classes->first(function ($class) use ($className, $section) {
            $nameMatches = strtolower($class->name) === $className
                || strtolower(trim($class->name.' '.$class->section)) === trim($className.' '.$section)
                || strtolower(trim($class->name.' - '.$class->section)) === $className;

            if (! $nameMatches) {
                return false;
            }

            return $section === '' || strtolower((string) $class->section) === $section;
        })?->id;
    }

    private function normalizeGender(?string $gender): ?string
    {
        $gender = strtolower(trim((string) $gender));

        return in_array($gender, ['male', 'female', 'other'], true) ? $gender : null;
    }

    private function normalizeStatus(?string $status): int
    {
        $status = strtolower(trim((string) $status));

        return in_array($status, ['0', 'inactive', 'no', 'false'], true) ? 0 : 1;
    }

    private function normalizeDate(?string $date): ?string
    {
        $date = trim((string) $date);

        if ($date === '') {
            return null;
        }

        if (is_numeric($date)) {
            return gmdate('Y-m-d', ((int) $date - 25569) * 86400);
        }

        $timestamp = strtotime($date);

        return $timestamp ? date('Y-m-d', $timestamp) : $date;
    }
}

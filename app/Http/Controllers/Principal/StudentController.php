<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\StudentImportService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function import(Request $request, StudentImportService $importer)
    {
        $request->validate([
            'students_file' => 'required|file|mimes:csv,txt,xlsx|max:5120',
        ]);

        $schoolId = auth()->user()->school_id;
        $extension = strtolower($request->file('students_file')->getClientOriginalExtension());

        $result = $importer->import(
            $request->file('students_file')->getRealPath(),
            $extension,
            $schoolId,
            $this->schoolClasses()->get()
        );

        if ($result['error']) {
            return back()->with('error', $result['error']);
        }

        $message = "{$result['created']} student".($result['created'] === 1 ? '' : 's').' imported successfully.';

        return back()
            ->with($result['created'] ? 'success' : 'error', $message)
            ->with('import_skipped', array_slice($result['skipped'], 0, 10))
            ->with('import_skipped_count', count($result['skipped']));
    }

    public function importTemplate()
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, StudentImportService::HEADERS);
            fputcsv($handle, ['Rahul Sharma', 'ADM001', '10', 'A', '1', 'rahul@example.com', '9876543210', 'male', '2010-04-12', 'School address', 'active']);
            fclose($handle);
        }, 'student-import-template.csv', ['Content-Type' => 'text/csv']);
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
}

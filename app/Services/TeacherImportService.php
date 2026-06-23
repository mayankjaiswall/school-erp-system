<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use ZipArchive;

class TeacherImportService
{
    public const HEADERS = [
        'name',
        'email',
        'phone',
        'employee_code',
        'primary_subject',
        'qualification',
        'experience_years',
        'joining_date',
        'designation',
        'gender',
        'status',
        'password',
    ];

    public function import(string $path, string $extension, int $schoolId, Collection $subjects): array
    {
        if (strtolower($extension) === 'xlsx' && ! class_exists(ZipArchive::class)) {
            return ['created' => 0, 'skipped' => [], 'error' => 'XLSX import requires the PHP Zip extension. Please upload a CSV file instead.'];
        }

        [$rows, $headerError] = $this->readImportRows($path, $extension);

        if ($headerError) {
            return ['created' => 0, 'skipped' => [], 'error' => $headerError];
        }

        if (empty($rows)) {
            return ['created' => 0, 'skipped' => [], 'error' => 'No teacher records found in the uploaded file.'];
        }

        $created = 0;
        $skipped = [];
        $teacherRoleId = Role::where('slug', 'teacher')->firstOrFail()->id;

        foreach ($rows as $index => $row) {
            $line = $index + 2;
            $subjectId = $this->resolveSubjectId($row, $subjects);

            if (! $subjectId) {
                $skipped[] = "Row {$line}: Primary subject not found.";
                continue;
            }

            $payload = [
                'name' => $row['name'] ?? null,
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'employee_code' => $row['employee_code'] ?? null,
                'primary_subject_id' => $subjectId,
                'qualification' => $row['qualification'] ?? null,
                'experience_years' => $row['experience_years'] ?? null,
                'joining_date' => $this->normalizeDate($row['joining_date'] ?? null),
                'designation' => $row['designation'] ?? null,
                'gender' => $row['gender'] ?? null,
                'status' => $this->normalizeStatus($row['status'] ?? null),
                'password' => $row['password'] ?? null,
            ];

            $validator = Validator::make($payload, $this->rules($schoolId));

            if ($validator->fails()) {
                $skipped[] = "Row {$line}: ".$validator->errors()->first();
                continue;
            }

            DB::transaction(function () use ($payload, $schoolId, $teacherRoleId) {
                $user = User::create([
                    'school_id' => $schoolId,
                    'role_id' => $teacherRoleId,
                    'name' => $payload['name'],
                    'email' => $payload['email'],
                    'phone' => $payload['phone'] ?? null,
                    'password' => Hash::make($payload['password']),
                    'status' => $payload['status'],
                ]);

                Teacher::create([
                    'user_id' => $user->id,
                    'school_id' => $schoolId,
                    'primary_subject_id' => $payload['primary_subject_id'],
                    'employee_code' => $payload['employee_code'],
                    'name' => $payload['name'],
                    'email' => $payload['email'],
                    'phone' => $payload['phone'] ?? null,
                    'qualification' => $payload['qualification'],
                    'experience' => $payload['experience_years'],
                    'experience_years' => $payload['experience_years'],
                    'joining_date' => $payload['joining_date'],
                    'designation' => $payload['designation'],
                    'gender' => $payload['gender'] ?? null,
                    'status' => $payload['status'],
                ]);
            });

            $created++;
        }

        return ['created' => $created, 'skipped' => $skipped, 'error' => null];
    }

    private function rules(int $schoolId): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', 'unique:teachers,email', 'unique:users,email'],
            'phone' => 'nullable|digits:10',
            'employee_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teachers', 'employee_code')->where('school_id', $schoolId),
            ],
            'primary_subject_id' => [
                'required',
                Rule::exists('subjects', 'id')->where('school_id', $schoolId)->where('status', 1),
            ],
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:60',
            'joining_date' => 'required|date',
            'designation' => 'required|string|max:255',
            'gender' => 'nullable|string|max:255',
            'status' => 'required|in:1,0',
            'password' => 'required|string|min:8',
        ];
    }

    private function readImportRows(string $path, string $extension): array
    {
        $rows = strtolower($extension) === 'xlsx' ? $this->readXlsxRows($path) : $this->readCsvRows($path);

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
        $allowed = array_merge(self::HEADERS, ['primary_subject_id', 'primary_subject_code']);
        $unknown = array_diff($headers, $allowed);

        if ($unknown) {
            return 'Import stopped. These columns do not match the teachers import format: '.implode(', ', $unknown).'.';
        }

        $required = ['name', 'email', 'employee_code', 'qualification', 'experience_years', 'joining_date', 'designation', 'password'];
        $missing = array_diff($required, $headers);

        if ($missing) {
            return 'Import stopped. Missing required columns: '.implode(', ', $missing).'.';
        }

        if (! in_array('primary_subject', $headers, true) && ! in_array('primary_subject_id', $headers, true) && ! in_array('primary_subject_code', $headers, true)) {
            return 'Import stopped. The file must include primary_subject, primary_subject_id, or primary_subject_code column.';
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

    private function resolveSubjectId(array $row, Collection $subjects): ?int
    {
        if (! empty($row['primary_subject_id'])) {
            return $subjects->firstWhere('id', (int) $row['primary_subject_id'])?->id;
        }

        $code = strtolower(trim((string) ($row['primary_subject_code'] ?? '')));
        $name = strtolower(trim((string) ($row['primary_subject'] ?? '')));

        return $subjects->first(function (Subject $subject) use ($code, $name) {
            return ($code !== '' && strtolower($subject->code) === $code)
                || ($name !== '' && strtolower($subject->name) === $name);
        })?->id;
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

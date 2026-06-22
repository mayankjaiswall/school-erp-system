<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\StudentImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherStudentController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $this->teacher();
        $classes = $this->assignedClasses($teacher);
        $classIds = $classes->pluck('id');
        $search = trim((string) $request->query('search'));

        $students = $classIds->isEmpty()
            ? collect()
            : Student::with('class')
                ->where('school_id', Auth::user()->school_id)
                ->whereIn('class_id', $classIds)
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

        return view('teacher.students.index', compact('students', 'classes', 'search'));
    }

    public function import(Request $request, StudentImportService $importer)
    {
        $request->validate([
            'students_file' => 'required|file|mimes:csv,txt,xlsx|max:5120',
        ]);

        $teacher = $this->teacher();
        $result = $importer->import(
            $request->file('students_file')->getRealPath(),
            strtolower($request->file('students_file')->getClientOriginalExtension()),
            Auth::user()->school_id,
            $this->assignedClasses($teacher)
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

    private function teacher()
    {
        return Auth::user()->teacher()
            ->with('teacherSubjects.schoolClass')
            ->firstOrFail();
    }

    private function assignedClasses($teacher)
    {
        return $teacher->teacherSubjects
            ->pluck('schoolClass')
            ->filter()
            ->where('status', 1)
            ->unique('id')
            ->values();
    }
}

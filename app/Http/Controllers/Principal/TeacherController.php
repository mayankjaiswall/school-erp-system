<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Models\AttendanceSession;
use App\Models\Mark;
use App\Services\TeacherImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    //Teacher index
    public function index(Request $request)
    {  
        $search = trim((string) $request->query('search'));

        $teachers = Teacher::with(['user', 'primarySubject'])
            ->where('school_id', auth()->user()->school_id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%")
                        ->orWhere('qualification', 'like', "%{$search}%")
                        ->orWhereHas('primarySubject', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->get();

        return view('principal.teachers.index', compact('teachers', 'search'));
    }

    //Teacher create
    public function create()
    {
        $school = School::where('id', auth()->user()->school_id)->first();
        $subjects = $this->primarySubjectOptions();

        return view('principal.teachers.create', compact('school', 'subjects'));
    }

    //Teacher store
    public function store(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:teachers,email',
                'unique:users,email',
            ],
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
            'gender' => 'nullable|string',
            'status' => 'required|in:1,0',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($validated, $schoolId) {
            $user = User::create([
                'school_id' => $schoolId,
                'role_id' => $this->teacherRoleId(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'],
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'primary_subject_id' => $validated['primary_subject_id'],
                'employee_code' => $validated['employee_code'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'qualification' => $validated['qualification'],
                'experience' => $validated['experience_years'],
                'experience_years' => $validated['experience_years'],
                'joining_date' => $validated['joining_date'],
                'designation' => $validated['designation'],
                'gender' => $validated['gender'] ?? null,
                'status' => $validated['status'],
            ]);
        });

        return response()->json(['success' => true,'message' => 'Teacher created successfully.']); 
    }


    //Teacher edit
    public function edit($id)
    {
        $teacher = Teacher::with(['user', 'primarySubject'])
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($id);
        $subjects = $this->primarySubjectOptions();

        return view('principal.teachers.edit', compact('teacher', 'subjects'));
    }

    //Teacher update
    public function update(Request $request, $id)
    {
        $schoolId = auth()->user()->school_id;
        $teacher = Teacher::with('user')
            ->where('school_id', $schoolId)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('teachers', 'email')->ignore($teacher->id),
                Rule::unique('users', 'email')->ignore($teacher->user_id),
            ],
            'phone' => 'nullable|digits:10',
            'employee_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teachers', 'employee_code')
                    ->where('school_id', $schoolId)
                    ->ignore($teacher->id),
            ],
            'primary_subject_id' => [
                'required',
                Rule::exists('subjects', 'id')->where('school_id', $schoolId)->where('status', 1),
            ],
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:60',
            'joining_date' => 'required|date',
            'designation' => 'required|string|max:255',
            'gender' => 'nullable|string',
            'status' => 'required|in:1,0',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($teacher, $validated, $schoolId) {
            $user = $teacher->user;

            if (! $user) {
                $user = User::create([
                    'school_id' => $schoolId,
                    'role_id' => $this->teacherRoleId(),
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'password' => Hash::make($validated['password'] ?? Str::random(16)),
                    'status' => $validated['status'],
                ]);

                $teacher->user_id = $user->id;
            }

            $user->fill([
                'school_id' => $schoolId,
                'role_id' => $this->teacherRoleId(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'status' => $validated['status'],
            ]);

            if (! empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            $teacher->fill([
                'primary_subject_id' => $validated['primary_subject_id'],
                'employee_code' => $validated['employee_code'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'qualification' => $validated['qualification'],
                'experience' => $validated['experience_years'],
                'experience_years' => $validated['experience_years'],
                'joining_date' => $validated['joining_date'],
                'designation' => $validated['designation'],
                'gender' => $validated['gender'] ?? null,
                'status' => $validated['status'],
            ]);

            $teacher->save();
        });

        return response()->json(['success' => true,'message' => 'Teacher updated successfully.']);
    }

    //Teacher show
    public function show($id)
    {
        $teacher = Teacher::with([
                'school',
                'user',
                'primarySubject',
                'teacherSubjects.schoolClass.students',
                'teacherSubjects.subject',
            ])
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($id);

        $classIds = $teacher->teacherSubjects->pluck('school_class_id')->unique()->values();
        $workload = [
            'assigned_classes' => $classIds->count(),
            'assigned_subjects' => $teacher->primary_subject_id ? 1 : 0,
            'total_students' => $classIds->isEmpty()
                ? 0
                : Student::where('school_id', $teacher->school_id)->whereIn('class_id', $classIds)->count(),
            'attendance_records' => AttendanceSession::where('teacher_id', $teacher->id)->count(),
            'marks_records' => Mark::where('teacher_id', $teacher->id)->count(),
        ];

        $assignedClasses = $teacher->teacherSubjects
            ->pluck('schoolClass')
            ->filter()
            ->unique('id')
            ->values();

        $assignedSubjects = $teacher->primarySubject ? collect([$teacher->primarySubject]) : collect();

        return view('principal.teachers.show', compact('teacher', 'workload', 'assignedClasses', 'assignedSubjects'));
    }

    //Teacher destroy
    public function destroy($id)
    {
        $teacher = Teacher::with('user')
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($id);

        DB::transaction(function () use ($teacher) {
            $user = $teacher->user;
            $teacher->delete();

            if ($user && $user->role?->slug === 'teacher') {
                $user->delete();
            }
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }

    public function import(Request $request, TeacherImportService $importer)
    {
        $request->validate([
            'teachers_file' => 'required|file|mimes:csv,txt,xlsx|max:5120',
        ]);

        $result = $importer->import(
            $request->file('teachers_file')->getRealPath(),
            strtolower($request->file('teachers_file')->getClientOriginalExtension()),
            auth()->user()->school_id,
            $this->primarySubjectOptions()
        );

        if ($result['error']) {
            return back()->with('error', $result['error']);
        }

        $message = "{$result['created']} teacher".($result['created'] === 1 ? '' : 's').' imported successfully.';

        return back()
            ->with($result['created'] ? 'success' : 'error', $message)
            ->with('import_skipped', array_slice($result['skipped'], 0, 10))
            ->with('import_skipped_count', count($result['skipped']));
    }

    public function importTemplate()
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, TeacherImportService::HEADERS);
            fputcsv($handle, ['Anita Sharma', 'anita@example.com', '9876543210', 'TCH001', 'Mathematics', 'M.Sc B.Ed', '5', '2024-06-01', 'Senior Teacher', 'female', 'active', 'Password@123']);
            fclose($handle);
        }, 'teacher-import-template.csv', ['Content-Type' => 'text/csv']);
    }

    private function teacherRoleId(): int
    {
        return Role::where('slug', 'teacher')->firstOrFail()->id;
    }

    private function primarySubjectOptions()
    {
        return Subject::where('school_id', auth()->user()->school_id)
            ->where('status', 1)
            ->orderBy('name')
            ->orderBy('code')
            ->get()
            ->unique(fn (Subject $subject) => strtolower($subject->name))
            ->values();
    }
}

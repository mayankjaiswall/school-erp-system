<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeacherAttendanceController extends Controller
{
    public function index()
    {
        $teacher = $this->teacher();
        $classes = $this->assignedClasses($teacher)->get();

        return view('teacher.attendance.index', compact('teacher', 'classes'));
    }

    public function getStudents(Request $request, $class)
    {
        $teacher = $this->teacher();
        $attendanceDate = $request->query('date', now()->toDateString());

        Validator::make([
            'class_id' => $class,
            'attendance_date' => $attendanceDate,
        ], [
            'class_id' => ['required', 'integer'],
            'attendance_date' => ['required', 'date'],
        ])->validate();

        $schoolClass = $this->assignedClasses($teacher)->findOrFail($class);

        $session = AttendanceSession::with('attendances')
            ->where('class_id', $schoolClass->id)
            ->whereDate('attendance_date', $attendanceDate)
            ->first();

        $existing = $session
            ? $session->attendances->keyBy('student_id')
            : collect();

        $students = Student::where('school_id', auth()->user()->school_id)
            ->where('class_id', $schoolClass->id)
            ->where('status', 1)
            ->orderBy('roll_no')
            ->orderBy('name')
            ->get()
            ->map(function (Student $student) use ($existing) {
                $attendance = $existing->get($student->id);

                return [
                    'id' => $student->id,
                    'admission_no' => $student->admission_no,
                    'roll_no' => $student->roll_no,
                    'name' => $student->name,
                    'status' => $attendance?->status,
                    'remarks' => $attendance?->remarks,
                ];
            });

        return response()->json([
            'success' => true,
            'class' => [
                'id' => $schoolClass->id,
                'name' => $schoolClass->name,
                'section' => $schoolClass->section,
            ],
            'attendance_date' => $attendanceDate,
            'session_exists' => (bool) $session,
            'session_id' => $session?->id,
            'students' => $students,
        ]);
    }

    public function saveAttendance(Request $request)
    {
        $teacher = $this->teacher();
        $schoolId = auth()->user()->school_id;

        $validator = Validator::make($request->all(), [
            'class_id' => [
                'required',
                Rule::exists('school_classes', 'id')->where('school_id', $schoolId),
            ],
            'attendance_date' => ['required', 'date'],
            'attendance' => ['required', 'array'],
            'attendance.*.status' => ['required', Rule::in(['present', 'absent', 'late'])],
            'attendance.*.remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $validator->after(function ($validator) use ($request, $teacher, $schoolId) {
            $classId = (int) $request->input('class_id');

            if (!$classId || !$this->assignedClasses($teacher)->where('school_classes.id', $classId)->exists()) {
                $validator->errors()->add('class_id', 'You are not assigned to this class.');
                return;
            }

            $rosterIds = Student::where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->where('status', 1)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values();

            if ($rosterIds->isEmpty()) {
                $validator->errors()->add('attendance', 'This class has no active students.');
                return;
            }

            $submittedIds = collect(array_keys($request->input('attendance', [])))
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values();

            if ($rosterIds->diff($submittedIds)->isNotEmpty() || $submittedIds->diff($rosterIds)->isNotEmpty()) {
                $validator->errors()->add('attendance', 'Attendance must be submitted for every active student in the selected class.');
            }
        });

        $validated = $validator->validate();

        $session = DB::transaction(function () use ($validated, $teacher) {
            $session = AttendanceSession::updateOrCreate(
                [
                    'class_id' => $validated['class_id'],
                    'attendance_date' => $validated['attendance_date'],
                ],
                [
                    'teacher_id' => $teacher->id,
                ]
            );

            $studentIds = [];

            foreach ($validated['attendance'] as $studentId => $attendance) {
                $studentIds[] = (int) $studentId;

                Attendance::updateOrCreate(
                    [
                        'attendance_session_id' => $session->id,
                        'student_id' => (int) $studentId,
                    ],
                    [
                        'status' => $attendance['status'],
                        'remarks' => $attendance['remarks'] ?? null,
                    ]
                );
            }

            $session->attendances()
                ->whereNotIn('student_id', $studentIds)
                ->delete();

            return $session->fresh('attendances');
        });

        $summary = $session->attendances
            ->groupBy('status')
            ->map
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Attendance saved successfully.',
            'session_id' => $session->id,
            'summary' => [
                'present' => $summary->get('present', 0),
                'absent' => $summary->get('absent', 0),
                'late' => $summary->get('late', 0),
            ],
        ]);
    }

    public function report(Request $request)
    {
        $teacher = $this->teacher();
        $classes = $this->assignedClasses($teacher)->get();

        if (!$request->expectsJson() && !$request->ajax()) {
            return view('teacher.attendance.report', compact('teacher', 'classes'));
        }

        $classIds = $classes->pluck('id');

        $sessions = AttendanceSession::with(['schoolClass', 'attendances.student'])
            ->whereIn('class_id', $classIds)
            ->when($request->filled('class_id'), function ($query) use ($request, $classIds) {
                if ($classIds->contains((int) $request->query('class_id'))) {
                    $query->where('class_id', $request->query('class_id'));
                }
            })
            ->when($request->filled('from_date'), fn ($query) => $query->whereDate('attendance_date', '>=', $request->query('from_date')))
            ->when($request->filled('to_date'), fn ($query) => $query->whereDate('attendance_date', '<=', $request->query('to_date')))
            ->latest('attendance_date')
            ->latest('id')
            ->get();

        $records = $sessions->flatMap(function (AttendanceSession $session) {
            return $session->attendances->map(function (Attendance $attendance) use ($session) {
                return [
                    'date' => $session->attendance_date->format('Y-m-d'),
                    'class' => $session->schoolClass?->name . ($session->schoolClass?->section ? ' - ' . $session->schoolClass->section : ''),
                    'student' => $attendance->student?->name,
                    'roll_no' => $attendance->student?->roll_no,
                    'status' => $attendance->status,
                    'remarks' => $attendance->remarks,
                ];
            });
        })->values();

        $summary = $records->groupBy('status')->map->count();

        return response()->json([
            'success' => true,
            'summary' => [
                'present' => $summary->get('present', 0),
                'absent' => $summary->get('absent', 0),
                'late' => $summary->get('late', 0),
            ],
            'records' => $records,
        ]);
    }

    private function teacher(): Teacher
    {
        abort_unless(auth()->user()?->role?->slug === 'teacher', 403);

        return auth()->user()
            ->teacher()
            ->with(['teacherSubjects.schoolClass', 'teacherSubjects.subject'])
            ->firstOrFail();
    }

    private function assignedClasses(Teacher $teacher)
    {
        $classIds = $teacher->teacherSubjects()
            ->pluck('school_class_id')
            ->unique()
            ->values();

        return SchoolClass::where('school_id', auth()->user()->school_id)
            ->whereIn('id', $classIds)
            ->where('status', 1)
            ->orderBy('name')
            ->orderBy('section');
    }
}

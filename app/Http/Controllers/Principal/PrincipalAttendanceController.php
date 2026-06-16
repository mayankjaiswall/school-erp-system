<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class PrincipalAttendanceController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->role?->slug === 'principal', 403);

        $schoolId = auth()->user()->school_id;
        $classes = SchoolClass::where('school_id', $schoolId)
            ->where('status', 1)
            ->orderBy('name')
            ->orderBy('section')
            ->get();

        $students = Student::where('school_id', $schoolId)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('principal.attendance.index', compact('classes', 'students'));
    }

    public function filter(Request $request)
    {
        abort_unless(auth()->user()?->role?->slug === 'principal', 403);

        $sessions = $this->filteredSessions($request)
            ->with(['schoolClass', 'teacher', 'attendances.student.class'])
            ->latest('attendance_date')
            ->latest('id')
            ->get();

        $studentFilter = $request->filled('student_id') ? (int) $request->query('student_id') : null;

        $records = $sessions->flatMap(function (AttendanceSession $session) use ($studentFilter) {
            return $session->attendances
                ->when($studentFilter, fn ($attendances) => $attendances->where('student_id', $studentFilter))
                ->map(function (Attendance $attendance) use ($session) {
                    $className = $session->schoolClass?->name . ($session->schoolClass?->section ? ' - ' . $session->schoolClass->section : '');

                    return [
                        'session_id' => $session->id,
                        'date' => $session->attendance_date->format('Y-m-d'),
                        'class_id' => $session->class_id,
                        'class' => $className,
                        'teacher' => $session->teacher?->name,
                        'student_id' => $attendance->student_id,
                        'student' => $attendance->student?->name,
                        'roll_no' => $attendance->student?->roll_no,
                        'status' => $attendance->status,
                        'remarks' => $attendance->remarks,
                    ];
                });
        })->values();

        $summary = $records->groupBy('status')->map->count();
        $studentPercentages = $this->studentPercentages($records);
        $classPercentages = $this->classPercentages($records);

        return response()->json([
            'success' => true,
            'summary' => [
                'present' => $summary->get('present', 0),
                'absent' => $summary->get('absent', 0),
                'late' => $summary->get('late', 0),
            ],
            'records' => $records,
            'student_percentages' => $studentPercentages,
            'class_percentages' => $classPercentages,
        ]);
    }

    public function show($id)
    {
        abort_unless(auth()->user()?->role?->slug === 'principal', 403);

        $session = AttendanceSession::with(['schoolClass', 'teacher', 'attendances.student'])
            ->whereHas('schoolClass', fn ($query) => $query->where('school_id', auth()->user()->school_id))
            ->findOrFail($id);

        $summary = $session->attendances->groupBy('status')->map->count();

        return response()->json([
            'success' => true,
            'session' => [
                'id' => $session->id,
                'date' => $session->attendance_date->format('Y-m-d'),
                'class' => $session->schoolClass?->name . ($session->schoolClass?->section ? ' - ' . $session->schoolClass->section : ''),
                'teacher' => $session->teacher?->name,
                'summary' => [
                    'present' => $summary->get('present', 0),
                    'absent' => $summary->get('absent', 0),
                    'late' => $summary->get('late', 0),
                ],
                'students' => $session->attendances->map(fn (Attendance $attendance) => [
                    'name' => $attendance->student?->name,
                    'roll_no' => $attendance->student?->roll_no,
                    'status' => $attendance->status,
                    'remarks' => $attendance->remarks,
                ])->values(),
            ],
        ]);
    }

    private function filteredSessions(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        return AttendanceSession::query()
            ->whereHas('schoolClass', fn ($query) => $query->where('school_id', $schoolId))
            ->when($request->filled('class_id'), fn ($query) => $query->where('class_id', $request->query('class_id')))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('attendance_date', $request->query('date')))
            ->when($request->filled('student_id'), function ($query) use ($request, $schoolId) {
                $query->whereHas('attendances.student', function ($query) use ($request, $schoolId) {
                    $query->where('students.school_id', $schoolId)
                        ->where('students.id', $request->query('student_id'));
                });
            });
    }

    private function studentPercentages($records)
    {
        return $records
            ->groupBy('student_id')
            ->map(function ($items) {
                $total = $items->count();
                $attended = $items->whereIn('status', ['present', 'late'])->count();
                $student = $items->first();

                return [
                    'student' => $student['student'],
                    'roll_no' => $student['roll_no'],
                    'total' => $total,
                    'attended' => $attended,
                    'percentage' => $total > 0 ? round(($attended / $total) * 100, 2) : 0,
                ];
            })
            ->sortBy('student')
            ->values();
    }

    private function classPercentages($records)
    {
        return $records
            ->groupBy('class_id')
            ->map(function ($items) {
                $total = $items->count();
                $attended = $items->whereIn('status', ['present', 'late'])->count();
                $class = $items->first();

                return [
                    'class' => $class['class'],
                    'total' => $total,
                    'attended' => $attended,
                    'percentage' => $total > 0 ? round(($attended / $total) * 100, 2) : 0,
                ];
            })
            ->sortBy('class')
            ->values();
    }
}

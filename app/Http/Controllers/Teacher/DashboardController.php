<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\Mark;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->role?->slug === 'teacher', 403);

        $teacher = Auth::user()->teacher()
            ->with(['primarySubject', 'teacherSubjects.schoolClass', 'teacherSubjects.subject'])
            ->firstOrFail();

        $assignments = $teacher?->teacherSubjects ?? collect();
        $classIds = $assignments->pluck('school_class_id')->unique()->values();

        $totalClasses = $classIds->count();
        $totalSubjects = $assignments->pluck('subject_id')->unique()->count();
        $totalStudents = $classIds->isEmpty()
            ? 0
            : Student::where('school_id', Auth::user()->school_id)
                ->whereIn('class_id', $classIds)
                ->count();

        $recentStudents = $classIds->isEmpty()
            ? collect()
            : Student::with('class')
                ->where('school_id', Auth::user()->school_id)
                ->whereIn('class_id', $classIds)
                ->latest()
                ->take(6)
                ->get();

        $assignedClasses = $assignments
            ->pluck('schoolClass')
            ->filter()
            ->unique('id')
            ->values();

        $assignedSubjects = $assignments
            ->pluck('subject')
            ->filter()
            ->unique('id')
            ->values();

        return view('teacher.dashboard', compact(
            'teacher',
            'totalClasses',
            'totalSubjects',
            'totalStudents',
            'assignedClasses',
            'assignedSubjects',
            'recentStudents'
        ));
    }

    public function profile()
    {
        abort_unless(Auth::user()->role?->slug === 'teacher', 403);

        $teacher = Auth::user()->teacher()
            ->with(['primarySubject', 'teacherSubjects.schoolClass', 'teacherSubjects.subject'])
            ->firstOrFail();

        $assignments = $teacher->teacherSubjects;
        $classIds = $assignments->pluck('school_class_id')->unique()->values();
        $subjectIds = $assignments->pluck('subject_id')->unique()->values();

        $assignedClasses = $assignments->pluck('schoolClass')->filter()->unique('id')->values();
        $assignedSubjects = $assignments->pluck('subject')->filter()->unique('id')->values();

        $workload = [
            'assigned_classes' => $classIds->count(),
            'assigned_subjects' => $subjectIds->count(),
            'total_students' => $classIds->isEmpty()
                ? 0
                : Student::where('school_id', Auth::user()->school_id)->whereIn('class_id', $classIds)->count(),
            'attendance_records' => AttendanceSession::where('teacher_id', $teacher->id)->count(),
            'marks_records' => Mark::where('teacher_id', $teacher->id)->count(),
        ];

        $recentAttendance = AttendanceSession::with('schoolClass')
            ->where('teacher_id', $teacher->id)
            ->latest('attendance_date')
            ->latest('id')
            ->take(5)
            ->get();

        $recentMarks = Mark::with(['exam', 'schoolClass', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('teacher.profile', compact(
            'teacher',
            'assignedClasses',
            'assignedSubjects',
            'workload',
            'recentAttendance',
            'recentMarks'
        ));
    }
}

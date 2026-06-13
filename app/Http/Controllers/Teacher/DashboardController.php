<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->role?->slug === 'teacher', 403);

        $teacher = Auth::user()->teacher()
            ->with(['teacherSubjects.schoolClass', 'teacherSubjects.subject'])
            ->first();

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
}

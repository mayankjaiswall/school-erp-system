<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;

class DashboardController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        abort_unless($schoolId, 403, 'Principal is not attached to a school.');

        $totalTeachers = Teacher::where('school_id', $schoolId)->count();
        $totalStudents = Student::where('school_id', $schoolId)->count();
        $totalClasses = SchoolClass::where('school_id', $schoolId)->count();
        $totalSubjects = Subject::where('school_id', $schoolId)->count();

        $recentTeachers = Teacher::where('school_id', $schoolId)
            ->latest()
            ->limit(5)
            ->get();

        $recentStudents = Student::with('class')
            ->where('school_id', $schoolId)
            ->latest()
            ->limit(5)
            ->get();

        return view('principal.dashboard', compact(
            'totalTeachers',
            'totalStudents',
            'totalClasses',
            'totalSubjects',
            'recentTeachers',
            'recentStudents'
        ));
    }
}

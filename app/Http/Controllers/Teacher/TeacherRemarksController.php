<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherRemark;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherRemarksController extends Controller
{
    public function index()
    {
        $teacher = $this->teacher();
        $classes = $this->assignedClasses($teacher)->get();
        $students = Student::with('class')
            ->where('school_id', auth()->user()->school_id)
            ->whereIn('class_id', $classes->pluck('id'))
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $remarks = TeacherRemark::with(['student.class'])
            ->where('teacher_id', $teacher->id)
            ->latest('remark_date')
            ->latest()
            ->take(50)
            ->get();

        return view('teacher.remarks.index', compact('teacher', 'classes', 'students', 'remarks'));
    }

    public function store(Request $request)
    {
        $teacher = $this->teacher();
        $classIds = $this->assignedClasses($teacher)->pluck('id');

        $validated = $request->validate([
            'student_id' => [
                'required',
                Rule::exists('students', 'id')->where('school_id', auth()->user()->school_id)->where('status', 1),
            ],
            'remark_date' => ['required', 'date'],
            'remark' => ['required', 'string', 'max:3000'],
        ]);

        $student = Student::where('school_id', auth()->user()->school_id)
            ->whereIn('class_id', $classIds)
            ->findOrFail($validated['student_id']);

        TeacherRemark::create([
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'remark_date' => $validated['remark_date'],
            'remark' => $validated['remark'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Remark added successfully.',
        ]);
    }

    private function teacher(): Teacher
    {
        abort_unless(auth()->user()?->role?->slug === 'teacher', 403);

        return auth()->user()
            ->teacher()
            ->with(['teacherSubjects.schoolClass'])
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

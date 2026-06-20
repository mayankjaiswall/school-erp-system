<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeacherMarksController extends Controller
{
    public function index()
    {
        $teacher = $this->teacher();
        $assignments = $teacher->teacherSubjects()
            ->with('schoolClass')
            ->get();

        $classes = $assignments
            ->pluck('schoolClass')
            ->filter()
            ->where('status', 1)
            ->unique('id')
            ->values();

        $exams = Exam::where('school_id', auth()->user()->school_id)
            ->where('status', 1)
            ->orderByDesc('exam_date')
            ->orderBy('name')
            ->get();

        return view('teacher.marks.index', compact('teacher', 'exams', 'classes'));
    }

    public function loadStudents(Request $request)
    {
        $teacher = $this->teacher();
        $schoolId = auth()->user()->school_id;

        $validated = Validator::make($request->all(), [
            'exam_id' => [
                'required',
                Rule::exists('exams', 'id')->where('school_id', $schoolId)->where('status', 1),
            ],
            'class_id' => [
                'required',
                Rule::exists('school_classes', 'id')->where('school_id', $schoolId)->where('status', 1),
            ],
        ])->after(function ($validator) use ($request, $teacher, $schoolId) {
            $this->validateAssignment($validator, $request, $teacher, $schoolId);
        })->validate();

        $validated['subject_id'] = $teacher->primary_subject_id;

        $existingMarks = Mark::where('school_id', $schoolId)
            ->where('exam_id', $validated['exam_id'])
            ->where('class_id', $validated['class_id'])
            ->where('subject_id', $validated['subject_id'])
            ->get()
            ->keyBy('student_id');

        $students = Student::where('school_id', $schoolId)
            ->where('class_id', $validated['class_id'])
            ->where('status', 1)
            ->orderBy('roll_no')
            ->orderBy('name')
            ->get()
            ->map(function (Student $student) use ($existingMarks) {
                $mark = $existingMarks->get($student->id);

                return [
                    'id' => $student->id,
                    'admission_no' => $student->admission_no,
                    'roll_no' => $student->roll_no,
                    'name' => $student->name,
                    'marks_obtained' => $mark?->marks_obtained,
                    'max_marks' => $mark?->max_marks,
                    'remarks' => $mark?->remarks,
                ];
            });

        return response()->json([
            'success' => true,
            'existing' => $existingMarks->isNotEmpty(),
            'max_marks' => $existingMarks->first()?->max_marks,
            'students' => $students,
        ]);
    }

    public function saveMarks(Request $request)
    {
        $teacher = $this->teacher();
        $schoolId = auth()->user()->school_id;

        $validator = Validator::make($request->all(), [
            'exam_id' => [
                'required',
                Rule::exists('exams', 'id')->where('school_id', $schoolId)->where('status', 1),
            ],
            'class_id' => [
                'required',
                Rule::exists('school_classes', 'id')->where('school_id', $schoolId)->where('status', 1),
            ],
            'max_marks' => ['required', 'numeric', 'gt:0', 'max:9999'],
            'marks' => ['required', 'array'],
            'marks.*.marks_obtained' => ['required', 'numeric', 'min:0'],
            'marks.*.remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $validator->after(function ($validator) use ($request, $teacher, $schoolId) {
            $this->validateAssignment($validator, $request, $teacher, $schoolId);

            $maxMarks = (float) $request->input('max_marks');
            foreach ($request->input('marks', []) as $studentId => $mark) {
                if (isset($mark['marks_obtained']) && (float) $mark['marks_obtained'] > $maxMarks) {
                    $validator->errors()->add("marks.{$studentId}.marks_obtained", 'Marks obtained cannot be greater than max marks.');
                }
            }

            $classId = (int) $request->input('class_id');
            if (!$classId) {
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
                $validator->errors()->add('marks', 'This class has no active students.');
                return;
            }

            $submittedIds = collect(array_keys($request->input('marks', [])))
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values();

            if ($rosterIds->diff($submittedIds)->isNotEmpty() || $submittedIds->diff($rosterIds)->isNotEmpty()) {
                $validator->errors()->add('marks', 'Marks must be submitted for every active student in the selected class.');
            }
        });

        $validated = $validator->validate();
        $validated['subject_id'] = $teacher->primary_subject_id;

        DB::transaction(function () use ($validated, $teacher, $schoolId) {
            foreach ($validated['marks'] as $studentId => $mark) {
                Mark::updateOrCreate(
                    [
                        'exam_id' => $validated['exam_id'],
                        'student_id' => (int) $studentId,
                        'subject_id' => $validated['subject_id'],
                    ],
                    [
                        'school_id' => $schoolId,
                        'class_id' => $validated['class_id'],
                        'teacher_id' => $teacher->id,
                        'marks_obtained' => $mark['marks_obtained'],
                        'max_marks' => $validated['max_marks'],
                        'remarks' => $mark['remarks'] ?? null,
                    ]
                );
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Marks saved successfully.',
        ]);
    }

    private function teacher(): Teacher
    {
        abort_unless(auth()->user()?->role?->slug === 'teacher', 403);

        return auth()->user()
            ->teacher()
            ->with(['primarySubject', 'teacherSubjects.schoolClass'])
            ->firstOrFail();
    }

    private function validateAssignment($validator, Request $request, Teacher $teacher, int $schoolId): void
    {
        if (!$request->filled('class_id')) {
            return;
        }

        if (!$teacher->primary_subject_id) {
            $validator->errors()->add('subject_id', 'This teacher does not have a Primary Subject assigned.');
            return;
        }

        $assigned = $teacher->teacherSubjects()
            ->where('school_id', $schoolId)
            ->where('school_class_id', $request->input('class_id'))
            ->where('subject_id', $teacher->primary_subject_id)
            ->exists();

        if (!$assigned) {
            $validator->errors()->add('class_id', 'You are not assigned to this class for your Primary Subject.');
        }
    }
}

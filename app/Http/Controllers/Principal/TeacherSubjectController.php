<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeacherSubjectController extends Controller
{
    public function index()
    {
        $assignments = TeacherSubject::with(['teacher', 'schoolClass', 'subject'])
            ->where('school_id', auth()->user()->school_id)
            ->latest()
            ->get();

        return view('principal.teacher-subjects.index', compact('assignments'));
    }

    public function create()
    {
        return view('principal.teacher-subjects.create', $this->formData());
    }

    public function store(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $validated = $this->validatedData($request, $schoolId);
        $validated['school_id'] = $schoolId;

        TeacherSubject::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Teacher assigned to subject successfully.',
        ]);
    }

    public function edit($id)
    {
        $assignment = $this->schoolAssignment($id);

        return view('principal.teacher-subjects.edit', array_merge(
            $this->formData(),
            compact('assignment')
        ));
    }

    public function update(Request $request, $id)
    {
        $assignment = $this->schoolAssignment($id);
        $schoolId = auth()->user()->school_id;
        $validated = $this->validatedData($request, $schoolId, $assignment->id);

        $assignment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Teacher subject assignment updated successfully.',
        ]);
    }

    public function show($id)
    {
        $assignment = TeacherSubject::with(['school', 'teacher', 'schoolClass', 'subject'])
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($id);

        return view('principal.teacher-subjects.show', compact('assignment'));
    }

    public function destroy(Request $request, $id)
    {
        $assignment = $this->schoolAssignment($id);
        $assignment->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher subject assignment deleted successfully.',
            ]);
        }

        return redirect()
            ->route('teacher-subjects.index')
            ->with('success', 'Teacher subject assignment deleted successfully.');
    }

    private function validatedData(Request $request, int $schoolId, ?int $assignmentId = null): array
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => [
                'required',
                Rule::exists('teachers', 'id')->where('school_id', $schoolId),
            ],
            'school_class_id' => [
                'required',
                Rule::exists('school_classes', 'id')->where('school_id', $schoolId),
            ],
            'subject_id' => [
                'required',
                Rule::exists('subjects', 'id')->where('school_id', $schoolId),
                Rule::unique('teacher_subjects', 'subject_id')
                    ->where('school_id', $schoolId)
                    ->where('school_class_id', $request->school_class_id)
                    ->ignore($assignmentId),
            ],
        ], [
            'subject_id.unique' => 'This class subject is already assigned to a teacher.',
        ]);

        $validator->after(function ($validator) use ($request, $schoolId) {
            if (!$request->subject_id || !$request->school_class_id) {
                return;
            }

            $subjectBelongsToClass = Subject::where('school_id', $schoolId)
                ->where('class_id', $request->school_class_id)
                ->where('id', $request->subject_id)
                ->exists();

            if (!$subjectBelongsToClass) {
                $validator->errors()->add('subject_id', 'Selected subject does not belong to the selected class.');
            }
        });

        return $validator->validate();
    }

    private function formData(): array
    {
        $schoolId = auth()->user()->school_id;

        return [
            'teachers' => Teacher::where('school_id', $schoolId)
                ->where('status', 1)
                ->orderBy('name')
                ->get(),
            'classes' => SchoolClass::where('school_id', $schoolId)
                ->where('status', 1)
                ->orderBy('name')
                ->orderBy('section')
                ->get(),
            'subjects' => Subject::where('school_id', $schoolId)
                ->where('status', 1)
                ->orderBy('name')
                ->get(),
        ];
    }

    private function schoolAssignment($id): TeacherSubject
    {
        return TeacherSubject::where('school_id', auth()->user()->school_id)->findOrFail($id);
    }
}

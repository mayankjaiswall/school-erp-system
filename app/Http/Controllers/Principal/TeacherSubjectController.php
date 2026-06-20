<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeacherSubjectController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $assignments = TeacherSubject::with(['teacher.primarySubject', 'schoolClass', 'subject'])
            ->where('school_id', auth()->user()->school_id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('teacher', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('employee_code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('subject', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('schoolClass', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('section', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->get();

        return view('principal.teacher-subjects.index', compact('assignments', 'search'));
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
            'message' => 'Teacher assigned to class successfully.',
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
            'message' => 'Teacher class assignment updated successfully.',
        ]);
    }

    public function show($id)
    {
        $assignment = TeacherSubject::with(['school', 'teacher.primarySubject', 'schoolClass', 'subject'])
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
        ]);

        $validator->after(function ($validator) use ($request, $schoolId, $assignmentId) {
            if (!$request->teacher_id || !$request->school_class_id) {
                return;
            }

            $teacher = Teacher::where('school_id', $schoolId)
                ->where('status', 1)
                ->find($request->teacher_id);

            if (!$teacher) {
                return;
            }

            if (!$teacher->primary_subject_id) {
                $validator->errors()->add('teacher_id', 'This teacher does not have a Primary Subject assigned.');
                return;
            }

            $duplicate = TeacherSubject::where('school_id', $schoolId)
                ->where('teacher_id', $teacher->id)
                ->where('school_class_id', $request->school_class_id)
                ->where('subject_id', $teacher->primary_subject_id)
                ->when($assignmentId, fn ($query) => $query->where('id', '!=', $assignmentId))
                ->exists();

            if ($duplicate) {
                $validator->errors()->add('school_class_id', 'This teacher is already assigned to this class for their Primary Subject.');
            }

            $classSubjectAlreadyAssigned = TeacherSubject::where('school_id', $schoolId)
                ->where('school_class_id', $request->school_class_id)
                ->where('subject_id', $teacher->primary_subject_id)
                ->when($assignmentId, fn ($query) => $query->where('id', '!=', $assignmentId))
                ->exists();

            if ($classSubjectAlreadyAssigned) {
                $validator->errors()->add('school_class_id', 'This class already has a teacher assigned for this Primary Subject.');
            }
        });

        $validated = $validator->validate();
        $teacher = Teacher::where('school_id', $schoolId)->findOrFail($validated['teacher_id']);
        $validated['subject_id'] = $teacher->primary_subject_id;

        return $validated;
    }

    private function formData(): array
    {
        $schoolId = auth()->user()->school_id;

        return [
            'teachers' => Teacher::with('primarySubject')
                ->where('school_id', $schoolId)
                ->where('status', 1)
                ->orderBy('name')
                ->get(),
            'classes' => SchoolClass::where('school_id', $schoolId)
                ->where('status', 1)
                ->orderBy('name')
                ->orderBy('section')
                ->get(),
        ];
    }

    private function schoolAssignment($id): TeacherSubject
    {
        return TeacherSubject::where('school_id', auth()->user()->school_id)->findOrFail($id);
    }
}

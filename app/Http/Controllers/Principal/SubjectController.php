<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('class')
            ->where('school_id', auth()->user()->school_id)
            ->latest()
            ->get();

        return view('principal.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $classes = $this->schoolClasses()->get();

        return view('principal.subjects.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $validated = $request->validate($this->rules($schoolId));
        $validated['school_id'] = $schoolId;

        Subject::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Subject created successfully.',
        ]);
    }

    public function edit($id)
    {
        $subject = $this->schoolSubject($id);
        $classes = $this->schoolClasses()->get();

        return view('principal.subjects.edit', compact('subject', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $subject = $this->schoolSubject($id);
        $schoolId = auth()->user()->school_id;

        $validated = $request->validate($this->rules($schoolId, $subject->id));
        $subject->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Subject updated successfully.',
        ]);
    }

    public function show($id)
    {
        $subject = Subject::with(['school', 'class'])
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($id);

        return view('principal.subjects.show', compact('subject'));
    }

    public function destroy(Request $request, $id)
    {
        $subject = $this->schoolSubject($id);
        $subject->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully.',
            ]);
        }

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }

    private function rules(int $schoolId, ?int $subjectId = null): array
    {
        return [
            'class_id' => [
                'required',
                Rule::exists('school_classes', 'id')->where('school_id', $schoolId),
            ],
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects', 'code')
                    ->where('school_id', $schoolId)
                    ->where('class_id', request('class_id'))
                    ->ignore($subjectId),
            ],
            'description' => 'nullable|string',
            'status' => 'required|in:1,0',
        ];
    }

    private function schoolClasses()
    {
        return SchoolClass::where('school_id', auth()->user()->school_id)
            ->where('status', 1)
            ->orderBy('name')
            ->orderBy('section');
    }

    private function schoolSubject($id): Subject
    {
        return Subject::where('school_id', auth()->user()->school_id)->findOrFail($id);
    }
}

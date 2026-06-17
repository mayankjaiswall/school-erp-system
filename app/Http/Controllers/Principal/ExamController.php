<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizePrincipal();

        $search = trim((string) $request->query('search'));

        $exams = Exam::where('school_id', auth()->user()->school_id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('exam_type', 'like', "%{$search}%")
                        ->orWhere('academic_year', 'like', "%{$search}%");
                });
            })
            ->latest('exam_date')
            ->latest()
            ->get();

        return view('principal.exams.index', compact('exams', 'search'));
    }

    public function create()
    {
        $this->authorizePrincipal();

        return view('principal.exams.create', ['exam' => null]);
    }

    public function store(Request $request)
    {
        $this->authorizePrincipal();

        $schoolId = auth()->user()->school_id;
        $validated = $request->validate($this->rules($schoolId));
        $validated['school_id'] = $schoolId;

        Exam::create($validated);

        return redirect()
            ->route('principal.exams.index')
            ->with('success', 'Exam created successfully.');
    }

    public function edit($id)
    {
        $this->authorizePrincipal();

        $exam = $this->schoolExam($id);

        return view('principal.exams.create', compact('exam'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizePrincipal();

        $exam = $this->schoolExam($id);
        $validated = $request->validate($this->rules(auth()->user()->school_id, $exam->id));

        $exam->update($validated);

        return redirect()
            ->route('principal.exams.index')
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizePrincipal();

        $exam = $this->schoolExam($id);
        $exam->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Exam deleted successfully.',
            ]);
        }

        return redirect()
            ->route('principal.exams.index')
            ->with('success', 'Exam deleted successfully.');
    }

    private function rules(int $schoolId, ?int $examId = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('exams', 'name')
                    ->where('school_id', $schoolId)
                    ->where('academic_year', request('academic_year'))
                    ->ignore($examId),
            ],
            'exam_type' => ['required', 'string', 'max:255'],
            'exam_date' => ['required', 'date'],
            'academic_year' => ['required', 'string', 'max:20'],
            'status' => ['required', Rule::in(['1', '0', 1, 0])],
        ];
    }

    private function schoolExam($id): Exam
    {
        return Exam::where('school_id', auth()->user()->school_id)->findOrFail($id);
    }

    private function authorizePrincipal(): void
    {
        abort_unless(auth()->user()?->role?->slug === 'principal', 403);
        abort_unless(auth()->user()->school_id, 403, 'Principal is not attached to a school.');
    }
}

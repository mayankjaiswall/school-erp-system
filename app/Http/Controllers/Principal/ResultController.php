<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizePrincipal();

        $schoolId = auth()->user()->school_id;
        $exams = Exam::where('school_id', $schoolId)->orderByDesc('exam_date')->get();
        $classes = SchoolClass::where('school_id', $schoolId)->where('status', 1)->orderBy('name')->orderBy('section')->get();
        $subjects = Subject::where('school_id', $schoolId)->where('status', 1)->orderBy('name')->get();
        $marks = $this->filteredMarks($request)->get();

        return view('principal.reports.results', [
            'exams' => $exams,
            'classes' => $classes,
            'subjects' => $subjects,
            'marks' => $marks,
            'filters' => $request->only(['exam_id', 'class_id', 'subject_id']),
            'totalExams' => $exams->count(),
            'totalMarksEntries' => Mark::where('school_id', $schoolId)->count(),
            'averagePercentage' => $this->averagePercentage($marks),
            'classResults' => $this->groupResults($marks, 'schoolClass'),
            'subjectResults' => $this->groupResults($marks, 'subject'),
            'examResults' => $this->groupResults($marks, 'exam'),
        ]);
    }

    public function classResult(Request $request)
    {
        $this->authorizePrincipal();

        $marks = $this->filteredMarks($request)->get();

        return response()->json([
            'success' => true,
            'results' => $this->groupResults($marks, 'schoolClass')->values(),
        ]);
    }

    public function subjectResult(Request $request)
    {
        $this->authorizePrincipal();

        $marks = $this->filteredMarks($request)->get();

        return response()->json([
            'success' => true,
            'results' => $this->groupResults($marks, 'subject')->values(),
        ]);
    }

    private function filteredMarks(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        return Mark::with(['exam', 'student', 'schoolClass', 'subject', 'teacher'])
            ->where('school_id', $schoolId)
            ->when($request->filled('exam_id'), fn ($query) => $query->where('exam_id', $request->query('exam_id')))
            ->when($request->filled('class_id'), fn ($query) => $query->where('class_id', $request->query('class_id')))
            ->when($request->filled('subject_id'), fn ($query) => $query->where('subject_id', $request->query('subject_id')))
            ->latest();
    }

    private function groupResults(Collection $marks, string $relation): Collection
    {
        return $marks
            ->groupBy(fn (Mark $mark) => $mark->{$relation}?->id ?? 'unknown')
            ->map(function (Collection $group) use ($relation) {
                $first = $group->first();
                $model = $first->{$relation};
                $obtained = $group->sum(fn (Mark $mark) => (float) $mark->marks_obtained);
                $maximum = $group->sum(fn (Mark $mark) => (float) $mark->max_marks);

                return [
                    'name' => $this->displayName($relation, $model),
                    'entries' => $group->count(),
                    'students' => $group->pluck('student_id')->unique()->count(),
                    'marks_obtained' => $obtained,
                    'max_marks' => $maximum,
                    'percentage' => $maximum > 0 ? round(($obtained / $maximum) * 100, 2) : 0,
                ];
            })
            ->sortBy('name')
            ->values();
    }

    private function averagePercentage(Collection $marks): float
    {
        $maximum = $marks->sum(fn (Mark $mark) => (float) $mark->max_marks);

        if ($maximum <= 0) {
            return 0;
        }

        $obtained = $marks->sum(fn (Mark $mark) => (float) $mark->marks_obtained);

        return round(($obtained / $maximum) * 100, 2);
    }

    private function displayName(string $relation, $model): string
    {
        if (!$model) {
            return 'Unknown';
        }

        if ($relation === 'schoolClass') {
            return $model->name . ($model->section ? ' - ' . $model->section : '');
        }

        if ($relation === 'exam') {
            return $model->name . ' (' . $model->academic_year . ')';
        }

        return $model->name;
    }

    private function authorizePrincipal(): void
    {
        abort_unless(auth()->user()?->role?->slug === 'principal', 403);
        abort_unless(auth()->user()->school_id, 403, 'Principal is not attached to a school.');
    }
}

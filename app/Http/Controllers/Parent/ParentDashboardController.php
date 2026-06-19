<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\ParentModel;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\TeacherRemark;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ParentDashboardController extends Controller
{
    public function dashboard()
    {
        $parent = $this->parentProfile();
        $children = $this->childrenQuery()->get();
        $selectedChild = $children->first();
        $attendanceSummary = $this->attendanceSummary($selectedChild);
        $latestResult = $selectedChild ? $this->latestResult($selectedChild) : null;
        $recentAttendance = $selectedChild ? $this->attendanceRecords($selectedChild)->take(6)->get() : collect();
        $latestResults = $selectedChild ? $this->resultSummaries($selectedChild)->take(5)->get() : collect();
        $upcomingExams = $selectedChild ? $this->upcomingExams($selectedChild) : collect();
        $remarks = $selectedChild ? $this->remarksQuery($selectedChild)->take(5)->get() : collect();

        return view('parent.dashboard', compact(
            'parent',
            'children',
            'selectedChild',
            'attendanceSummary',
            'latestResult',
            'recentAttendance',
            'latestResults',
            'upcomingExams',
            'remarks'
        ));
    }

    public function children(Request $request)
    {
        $children = $this->childrenQuery()->get();

        if ($request->expectsJson() || $request->ajax()) {
            $child = $request->filled('student_id')
                ? $this->ownedStudent((int) $request->query('student_id'))
                : $children->first();

            return response()->json([
                'success' => true,
                'child' => $child ? $this->studentPayload($child) : null,
            ]);
        }

        return view('parent.children', compact('children'));
    }

    public function attendance(Request $request)
    {
        $children = $this->childrenQuery()->get();
        $academicYears = $this->academicYears();

        if (!$request->expectsJson() && !$request->ajax()) {
            return view('parent.attendance', compact('children', 'academicYears'));
        }

        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'month' => ['nullable', 'integer', 'between:1,12'],
            'academic_year' => ['nullable', 'string', 'max:20'],
        ]);

        $student = $this->ownedStudent((int) $validated['student_id']);
        $records = $this->attendanceRecords($student)
            ->when(!empty($validated['month']), function ($query) use ($validated) {
                $year = $this->calendarYearForMonth((int) $validated['month'], $validated['academic_year'] ?? null);
                $query->whereYear('attendance_sessions.attendance_date', $year)
                    ->whereMonth('attendance_sessions.attendance_date', $validated['month']);
            })
            ->get();

        return response()->json([
            'success' => true,
            'summary' => $this->attendanceSummaryFromRecords($records),
            'records' => $records->map(fn (Attendance $attendance) => [
                'date' => $attendance->attendanceSession?->attendance_date?->format('Y-m-d'),
                'status' => ucfirst($attendance->status),
                'remarks' => $attendance->remarks ?? '-',
            ])->values(),
        ]);
    }

    public function results(Request $request)
    {
        $children = $this->childrenQuery()->get();
        $exams = $this->schoolExams()->get();

        if (!$request->expectsJson() && !$request->ajax()) {
            return view('parent.results', compact('children', 'exams'));
        }

        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'exam_id' => ['nullable', Rule::exists('exams', 'id')->where('school_id', $this->schoolId())],
        ]);

        $student = $this->ownedStudent((int) $validated['student_id']);
        $summaries = $this->resultSummaries($student)
            ->when(!empty($validated['exam_id']), fn ($query) => $query->where('exam_id', $validated['exam_id']))
            ->get()
            ->map(fn (Exam $exam) => $this->examResultPayload($student, $exam))
            ->values();

        return response()->json([
            'success' => true,
            'results' => $summaries,
        ]);
    }

    public function reportCards(Request $request)
    {
        $children = $this->childrenQuery()->get();
        $exams = $this->schoolExams()->get();

        if (!$request->expectsJson() && !$request->ajax()) {
            return view('parent.report-cards', compact('children', 'exams'));
        }

        $validated = $this->validatedReportRequest($request);
        $reportCards = $this->reportCardsForParent($validated['student_id'], $validated['exam_id']);

        if ($reportCards->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No report card data found for the selected child and exam.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'html' => view('report-cards.pdf', [
                'reportCards' => $reportCards,
                'standalone' => false,
                'printMode' => false,
                'forPdf' => false,
                'schoolLogo' => $this->schoolLogo(false),
            ])->render(),
        ]);
    }

    public function downloadReportCard(Request $request)
    {
        $validated = $this->validatedReportRequest($request);
        $reportCards = $this->reportCardsForParent($validated['student_id'], $validated['exam_id']);

        abort_if($reportCards->isEmpty(), 404, 'No report card data found.');

        $first = $reportCards->first();
        $fileName = Str::slug($first['student']->name . '-' . $first['exam']->name . '-report-card') . '.pdf';

        $pdf = Pdf::loadView('report-cards.pdf', [
            'reportCards' => $reportCards,
            'standalone' => true,
            'printMode' => false,
            'forPdf' => true,
            'schoolLogo' => $this->schoolLogo(true),
        ])->setPaper('a4');

        return $pdf->download($fileName);
    }

    public function printReportCard(Request $request)
    {
        $validated = $this->validatedReportRequest($request);
        $reportCards = $this->reportCardsForParent($validated['student_id'], $validated['exam_id']);

        abort_if($reportCards->isEmpty(), 404, 'No report card data found.');

        return view('report-cards.pdf', [
            'reportCards' => $reportCards,
            'standalone' => true,
            'printMode' => true,
            'forPdf' => false,
            'schoolLogo' => $this->schoolLogo(false),
        ]);
    }

    public function remarks(Request $request)
    {
        $children = $this->childrenQuery()->get();

        if (!$request->expectsJson() && !$request->ajax()) {
            return view('parent.remarks', compact('children'));
        }

        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
        ]);

        $student = $this->ownedStudent((int) $validated['student_id']);
        $remarks = $this->remarksQuery($student)->get();

        return response()->json([
            'success' => true,
            'remarks' => $remarks->map(fn (TeacherRemark $remark) => [
                'date' => $remark->remark_date?->format('Y-m-d'),
                'teacher' => $remark->teacher?->name ?? $remark->teacher?->user?->name ?? 'Teacher',
                'remark' => $remark->remark,
            ])->values(),
        ]);
    }

    public function profile()
    {
        $parent = $this->parentProfile()->load(['user', 'students.class']);

        return view('parent.profile', compact('parent'));
    }

    private function parentProfile(): ParentModel
    {
        abort_unless(auth()->user()?->role?->slug === 'parent', 403);

        return auth()->user()
            ->parentProfile()
            ->with('students.class')
            ->where('status', 1)
            ->firstOrFail();
    }

    private function childrenQuery()
    {
        return $this->parentProfile()
            ->students()
            ->with('class')
            ->where('students.school_id', $this->schoolId())
            ->where('students.status', 1)
            ->orderBy('students.name');
    }

    private function ownedStudent(int $studentId): Student
    {
        return $this->childrenQuery()->where('students.id', $studentId)->firstOrFail();
    }

    private function schoolId(): int
    {
        abort_unless(auth()->user()?->school_id, 403, 'Parent is not attached to a school.');

        return (int) auth()->user()->school_id;
    }

    private function attendanceRecords(Student $student)
    {
        return Attendance::with('attendanceSession')
            ->join('attendance_sessions', 'attendance_sessions.id', '=', 'attendances.attendance_session_id')
            ->select('attendances.*')
            ->where('attendances.student_id', $student->id)
            ->where('attendance_sessions.class_id', $student->class_id)
            ->latest('attendance_sessions.attendance_date')
            ->latest('attendances.id');
    }

    private function attendanceSummary(?Student $student): array
    {
        if (!$student) {
            return $this->emptyAttendanceSummary();
        }

        return $this->attendanceSummaryFromRecords($this->attendanceRecords($student)->get());
    }

    private function attendanceSummaryFromRecords(Collection $records): array
    {
        $total = $records->count();
        $present = $records->where('status', 'present')->count();
        $late = $records->where('status', 'late')->count();
        $absent = $records->where('status', 'absent')->count();

        return [
            'present_days' => $present,
            'absent_days' => $absent,
            'late_days' => $late,
            'total_days' => $total,
            'percentage' => $total > 0 ? round((($present + $late) / $total) * 100, 2) : 0,
        ];
    }

    private function emptyAttendanceSummary(): array
    {
        return [
            'present_days' => 0,
            'absent_days' => 0,
            'late_days' => 0,
            'total_days' => 0,
            'percentage' => 0,
        ];
    }

    private function latestResult(Student $student): ?array
    {
        $exam = $this->resultSummaries($student)->first();

        return $exam ? $this->examResultPayload($student, $exam) : null;
    }

    private function resultSummaries(Student $student)
    {
        return Exam::where('school_id', $this->schoolId())
            ->whereHas('marks', fn ($query) => $query->where('student_id', $student->id))
            ->with(['marks' => fn ($query) => $query->where('student_id', $student->id)->with('subject')])
            ->orderByDesc('exam_date')
            ->orderByDesc('id');
    }

    private function examResultPayload(Student $student, Exam $exam): array
    {
        $marks = $exam->marks;
        $total = $marks->sum(fn (Mark $mark) => (float) $mark->max_marks);
        $obtained = $marks->sum(fn (Mark $mark) => (float) $mark->marks_obtained);
        $percentage = $total > 0 ? round(($obtained / $total) * 100, 2) : 0;
        $hasFailed = $marks->contains(fn (Mark $mark) => (float) $mark->max_marks <= 0 || (((float) $mark->marks_obtained / (float) $mark->max_marks) * 100) < 40);

        return [
            'exam_id' => $exam->id,
            'exam' => $exam->name,
            'exam_type' => $exam->exam_type,
            'exam_date' => $exam->exam_date?->format('Y-m-d'),
            'academic_year' => $exam->academic_year,
            'total_marks' => $total,
            'obtained_marks' => $obtained,
            'percentage' => $percentage,
            'grade' => $this->grade($percentage),
            'result' => $hasFailed ? 'FAIL' : 'PASS',
            'attendance_percentage' => $this->attendanceSummary($student)['percentage'],
            'subjects' => $marks->map(fn (Mark $mark) => [
                'subject' => $mark->subject?->name ?? 'Unknown Subject',
                'marks_obtained' => (float) $mark->marks_obtained,
                'max_marks' => (float) $mark->max_marks,
                'percentage' => (float) $mark->max_marks > 0 ? round(((float) $mark->marks_obtained / (float) $mark->max_marks) * 100, 2) : 0,
                'grade' => $this->grade((float) $mark->max_marks > 0 ? (((float) $mark->marks_obtained / (float) $mark->max_marks) * 100) : 0),
                'remarks' => $mark->remarks,
            ])->values(),
        ];
    }

    private function upcomingExams(Student $student): Collection
    {
        return Exam::where('school_id', $this->schoolId())
            ->where('status', 1)
            ->whereDate('exam_date', '>=', now()->toDateString())
            ->orderBy('exam_date')
            ->take(5)
            ->get()
            ->map(fn (Exam $exam) => [
                'name' => $exam->name,
                'exam_type' => $exam->exam_type,
                'exam_date' => $exam->exam_date?->format('d M Y'),
                'class' => $student->class?->name . ($student->class?->section ? ' - ' . $student->class->section : ''),
            ]);
    }

    private function remarksQuery(Student $student)
    {
        return TeacherRemark::with('teacher.user')
            ->where('student_id', $student->id)
            ->latest('remark_date')
            ->latest('id');
    }

    private function schoolExams()
    {
        return Exam::where('school_id', $this->schoolId())
            ->where('status', 1)
            ->orderByDesc('exam_date')
            ->orderBy('name');
    }

    private function academicYears(): Collection
    {
        $examYears = Exam::where('school_id', $this->schoolId())->distinct()->pluck('academic_year');
        $sessionYears = AttendanceSession::whereIn('class_id', $this->childrenQuery()->pluck('students.class_id')->unique())
            ->get()
            ->map(fn (AttendanceSession $session) => $this->academicYearForDate($session->attendance_date));

        return $examYears->merge($sessionYears)->filter()->unique()->values();
    }

    private function academicYearForDate($date): string
    {
        $year = (int) $date->format('Y');
        $month = (int) $date->format('m');

        return $month >= 4 ? $year . '-' . ($year + 1) : ($year - 1) . '-' . $year;
    }

    private function calendarYearForMonth(int $month, ?string $academicYear): int
    {
        if (!$academicYear || !str_contains($academicYear, '-')) {
            return (int) now()->format('Y');
        }

        [$startYear, $endYear] = array_map('intval', explode('-', $academicYear, 2));

        return $month >= 4 ? $startYear : $endYear;
    }

    private function studentPayload(Student $student): array
    {
        return [
            'id' => $student->id,
            'name' => $student->name,
            'admission_no' => $student->admission_no,
            'class' => $student->class?->name,
            'section' => $student->class?->section,
            'roll_no' => $student->roll_no,
            'dob' => $student->dob?->format('d M Y'),
            'status' => $student->status ? 'Active' : 'Inactive',
            'photo' => $student->photo ? asset($student->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=2563eb&color=fff',
        ];
    }

    private function validatedReportRequest(Request $request): array
    {
        $validated = Validator::make($request->all(), [
            'student_id' => ['required', 'integer'],
            'exam_id' => [
                'required',
                Rule::exists('exams', 'id')->where('school_id', $this->schoolId())->where('status', 1),
            ],
        ])->validate();

        $this->ownedStudent((int) $validated['student_id']);

        return $validated;
    }

    private function reportCardsForParent(int $studentId, int $examId): Collection
    {
        $student = $this->ownedStudent($studentId);
        $exam = Exam::where('school_id', $this->schoolId())->findOrFail($examId);
        $schoolClass = SchoolClass::with('school')
            ->where('school_id', $this->schoolId())
            ->findOrFail($student->class_id);

        $marks = Mark::with('subject')
            ->where('school_id', $this->schoolId())
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('class_id', $student->class_id)
            ->get()
            ->sortBy(fn (Mark $mark) => $mark->subject?->name ?? '')
            ->values();

        if ($marks->isEmpty()) {
            return collect();
        }

        $totalMarks = $marks->sum(fn (Mark $mark) => (float) $mark->max_marks);
        $obtainedMarks = $marks->sum(fn (Mark $mark) => (float) $mark->marks_obtained);
        $percentage = $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;
        $hasFailedSubject = $marks->contains(fn (Mark $mark) => (float) $mark->max_marks <= 0 || (((float) $mark->marks_obtained / (float) $mark->max_marks) * 100) < 40);

        return collect([[
            'school' => $schoolClass->school,
            'exam' => $exam,
            'student' => $student,
            'class' => $schoolClass,
            'subjects' => $marks->map(fn (Mark $mark) => [
                'name' => $mark->subject?->name ?? 'Unknown Subject',
                'max_marks' => (float) $mark->max_marks,
                'obtained_marks' => (float) $mark->marks_obtained,
                'percentage' => (float) $mark->max_marks > 0 ? round(((float) $mark->marks_obtained / (float) $mark->max_marks) * 100, 2) : 0,
                'grade' => $this->grade((float) $mark->max_marks > 0 ? (((float) $mark->marks_obtained / (float) $mark->max_marks) * 100) : 0),
            ])->values(),
            'summary' => [
                'total_marks' => $totalMarks,
                'obtained_marks' => $obtainedMarks,
                'percentage' => $percentage,
                'overall_grade' => $this->grade($percentage),
                'result' => $hasFailedSubject ? 'FAIL' : 'PASS',
            ],
            'attendance' => [
                'present_days' => $this->attendanceSummary($student)['present_days'] + $this->attendanceSummary($student)['late_days'],
                'total_days' => $this->attendanceSummary($student)['total_days'],
                'percentage' => $this->attendanceSummary($student)['percentage'],
            ],
        ]]);
    }

    private function grade(float $percentage): string
    {
        return match (true) {
            $percentage >= 90 => 'A+',
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B+',
            $percentage >= 60 => 'B',
            $percentage >= 50 => 'C',
            $percentage >= 40 => 'D',
            default => 'F',
        };
    }

    private function schoolLogo(bool $forPdf): ?string
    {
        foreach (['school-logo.png', 'logo.png', 'images/school-logo.png', 'images/logo.png'] as $path) {
            if (file_exists(public_path($path))) {
                return $forPdf ? public_path($path) : asset($path);
            }
        }

        return null;
    }
}

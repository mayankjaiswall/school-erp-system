<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportCardController extends Controller
{
    public function index()
    {
        $role = $this->role();
        $schoolId = $this->schoolId();
        $exams = Exam::where('school_id', $schoolId)
            ->where('status', 1)
            ->orderByDesc('exam_date')
            ->orderBy('name')
            ->get();

        if ($role === 'teacher') {
            $teacher = $this->teacher();
            $classes = $this->assignedClasses($teacher)->get();

            return view('teacher.report-cards.index', compact('teacher', 'exams', 'classes'));
        }

        $this->authorizePrincipal();

        $classes = SchoolClass::where('school_id', $schoolId)
            ->where('status', 1)
            ->orderBy('name')
            ->orderBy('section')
            ->get();

        return view('principal.report-cards.index', compact('exams', 'classes'));
    }

    public function getStudents(Request $request)
    {
        $schoolId = $this->schoolId();

        $validated = Validator::make($request->all(), [
            'class_id' => [
                'required',
                Rule::exists('school_classes', 'id')->where('school_id', $schoolId)->where('status', 1),
            ],
        ])->after(function ($validator) use ($request) {
            $this->validateClassAccess($validator, (int) $request->input('class_id'));
        })->validate();

        $students = Student::where('school_id', $schoolId)
            ->where('class_id', $validated['class_id'])
            ->where('status', 1)
            ->orderBy('roll_no')
            ->orderBy('name')
            ->get()
            ->map(fn (Student $student) => [
                'id' => $student->id,
                'name' => $student->name,
                'admission_no' => $student->admission_no,
                'roll_no' => $student->roll_no,
            ])
            ->values();

        return response()->json([
            'success' => true,
            'students' => $students,
        ]);
    }

    public function generate(Request $request)
    {
        $validated = $this->validatedReportRequest($request);
        $reportCards = $this->reportCards($validated['exam_id'], $validated['class_id'], $validated['student_id'] ?? null);

        if ($reportCards->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No marks found for the selected filters.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'count' => $reportCards->count(),
            'html' => view('report-cards.pdf', [
                'reportCards' => $reportCards,
                'standalone' => false,
                'printMode' => false,
                'forPdf' => false,
                'schoolLogo' => $this->schoolLogo(false),
            ])->render(),
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $validated = $this->validatedReportRequest($request);
        $reportCards = $this->reportCards($validated['exam_id'], $validated['class_id'], $validated['student_id'] ?? null);

        abort_if($reportCards->isEmpty(), 404, 'No marks found for the selected filters.');

        $first = $reportCards->first();
        $scope = $validated['student_id'] ?? 'class';
        $fileName = Str::slug($first['exam']->name . '-report-card-' . $scope) . '.pdf';

        $pdf = Pdf::loadView('report-cards.pdf', [
            'reportCards' => $reportCards,
            'standalone' => true,
            'printMode' => false,
            'forPdf' => true,
            'schoolLogo' => $this->schoolLogo(true),
        ])->setPaper('a4');

        return $pdf->download($fileName);
    }

    public function print(Request $request)
    {
        $validated = $this->validatedReportRequest($request);
        $reportCards = $this->reportCards($validated['exam_id'], $validated['class_id'], $validated['student_id'] ?? null);

        abort_if($reportCards->isEmpty(), 404, 'No marks found for the selected filters.');

        return view('report-cards.pdf', [
            'reportCards' => $reportCards,
            'standalone' => true,
            'printMode' => true,
            'forPdf' => false,
            'schoolLogo' => $this->schoolLogo(false),
        ]);
    }

    private function validatedReportRequest(Request $request): array
    {
        $schoolId = $this->schoolId();

        $validator = Validator::make($request->all(), [
            'exam_id' => [
                'required',
                Rule::exists('exams', 'id')->where('school_id', $schoolId)->where('status', 1),
            ],
            'class_id' => [
                'required',
                Rule::exists('school_classes', 'id')->where('school_id', $schoolId)->where('status', 1),
            ],
            'student_id' => [
                'nullable',
                Rule::exists('students', 'id')->where('school_id', $schoolId)->where('status', 1),
            ],
        ]);

        $validator->after(function ($validator) use ($request, $schoolId) {
            $classId = (int) $request->input('class_id');
            $this->validateClassAccess($validator, $classId);

            if (!$request->filled('student_id')) {
                return;
            }

            $studentInClass = Student::where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->where('id', $request->input('student_id'))
                ->where('status', 1)
                ->exists();

            if (!$studentInClass) {
                $validator->errors()->add('student_id', 'Selected student does not belong to the selected class.');
            }
        });

        return $validator->validate();
    }

    private function reportCards(int $examId, int $classId, ?int $studentId = null): Collection
    {
        $schoolId = $this->schoolId();
        $exam = Exam::where('school_id', $schoolId)->findOrFail($examId);
        $schoolClass = SchoolClass::with('school')
            ->where('school_id', $schoolId)
            ->findOrFail($classId);

        $students = Student::where('school_id', $schoolId)
            ->where('class_id', $classId)
            ->where('status', 1)
            ->when($studentId, fn ($query) => $query->where('id', $studentId))
            ->orderBy('roll_no')
            ->orderBy('name')
            ->get();

        $studentIds = $students->pluck('id');

        if ($studentIds->isEmpty()) {
            return collect();
        }

        $marks = Mark::with('subject')
            ->where('school_id', $schoolId)
            ->where('exam_id', $examId)
            ->where('class_id', $classId)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->sortBy(fn (Mark $mark) => $mark->subject?->name ?? '')
            ->groupBy('student_id');

        return $students
            ->map(function (Student $student) use ($exam, $schoolClass, $marks) {
                $studentMarks = $marks->get($student->id, collect())->values();

                if ($studentMarks->isEmpty()) {
                    return null;
                }

                $totalMarks = $studentMarks->sum(fn (Mark $mark) => (float) $mark->max_marks);
                $obtainedMarks = $studentMarks->sum(fn (Mark $mark) => (float) $mark->marks_obtained);
                $percentage = $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;
                $hasFailedSubject = $studentMarks->contains(function (Mark $mark) {
                    $maxMarks = (float) $mark->max_marks;

                    return $maxMarks <= 0 || (((float) $mark->marks_obtained / $maxMarks) * 100) < 40;
                });

                return [
                    'school' => $schoolClass->school,
                    'exam' => $exam,
                    'student' => $student,
                    'class' => $schoolClass,
                    'subjects' => $studentMarks->map(fn (Mark $mark) => [
                        'name' => $mark->subject?->name ?? 'Unknown Subject',
                        'max_marks' => (float) $mark->max_marks,
                        'obtained_marks' => (float) $mark->marks_obtained,
                        'percentage' => (float) $mark->max_marks > 0
                            ? round(((float) $mark->marks_obtained / (float) $mark->max_marks) * 100, 2)
                            : 0,
                        'grade' => $this->grade((float) $mark->max_marks > 0
                            ? (((float) $mark->marks_obtained / (float) $mark->max_marks) * 100)
                            : 0),
                    ])->values(),
                    'summary' => [
                        'total_marks' => $totalMarks,
                        'obtained_marks' => $obtainedMarks,
                        'percentage' => $percentage,
                        'overall_grade' => $this->grade($percentage),
                        'result' => $hasFailedSubject ? 'FAIL' : 'PASS',
                    ],
                    'attendance' => $this->attendanceSummary($student, $schoolClass),
                ];
            })
            ->filter()
            ->values();
    }

    private function attendanceSummary(Student $student, SchoolClass $schoolClass): array
    {
        $sessionIds = AttendanceSession::where('class_id', $schoolClass->id)->pluck('id');
        $totalDays = $sessionIds->count();

        if ($totalDays === 0) {
            return [
                'present_days' => 0,
                'total_days' => 0,
                'percentage' => 0,
            ];
        }

        $presentDays = Attendance::whereIn('attendance_session_id', $sessionIds)
            ->where('student_id', $student->id)
            ->whereIn('status', ['present', 'late'])
            ->count();

        return [
            'present_days' => $presentDays,
            'total_days' => $totalDays,
            'percentage' => round(($presentDays / $totalDays) * 100, 2),
        ];
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

    private function validateClassAccess($validator, int $classId): void
    {
        if ($this->role() !== 'teacher') {
            return;
        }

        $assigned = $this->assignedClasses($this->teacher())
            ->where('school_classes.id', $classId)
            ->exists();

        if (!$assigned) {
            $validator->errors()->add('class_id', 'You are not assigned to this class.');
        }
    }

    private function assignedClasses(Teacher $teacher)
    {
        $classIds = $teacher->teacherSubjects()
            ->pluck('school_class_id')
            ->unique()
            ->values();

        return SchoolClass::where('school_id', $this->schoolId())
            ->whereIn('id', $classIds)
            ->where('status', 1)
            ->orderBy('name')
            ->orderBy('section');
    }

    private function teacher(): Teacher
    {
        abort_unless($this->role() === 'teacher', 403);

        return auth()->user()
            ->teacher()
            ->with(['teacherSubjects.schoolClass', 'teacherSubjects.subject'])
            ->firstOrFail();
    }

    private function authorizePrincipal(): void
    {
        abort_unless($this->role() === 'principal', 403);
        abort_unless($this->schoolId(), 403, 'Principal is not attached to a school.');
    }

    private function role(): ?string
    {
        return auth()->user()?->role?->slug;
    }

    private function schoolId(): int
    {
        abort_unless(auth()->user()?->school_id, 403, 'User is not attached to a school.');

        return (int) auth()->user()->school_id;
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

@extends('layouts.principal')

@section('title', 'Result Reports')
@section('page-title', 'Result Reports')

@section('content')
<style>
    .report-header{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;box-shadow:0 15px 35px rgba(37,99,235,.25)}
    .filter-card,.table-card{background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:25px;box-shadow:0 8px 20px rgba(15,23,42,.05)}
    .metric-card{color:#fff;padding:24px;border-radius:18px;box-shadow:0 10px 30px rgba(0,0,0,.12)}
    .metric-card h2{font-size:34px;font-weight:700;margin:8px 0}
    .card-blue{background:linear-gradient(135deg,#2563eb,#1d4ed8)}
    .card-green{background:linear-gradient(135deg,#16a34a,#15803d)}
    .card-orange{background:linear-gradient(135deg,#ea580c,#c2410c)}
    .section-title{font-weight:700;color:#0f172a;margin-bottom:16px}
</style>

<div class="report-header">
    <h2 class="mb-2">Academic Result Reports</h2>
    <p class="mb-0 opacity-75">Review class wise, subject wise, and exam wise performance.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="metric-card card-blue">
            <h6>Total Exams</h6>
            <h2>{{ $totalExams }}</h2>
            <small>Created exams</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card card-green">
            <h6>Total Marks Entries</h6>
            <h2>{{ $totalMarksEntries }}</h2>
            <small>Saved mark records</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card card-orange">
            <h6>Average Percentage</h6>
            <h2>{{ number_format($averagePercentage, 2) }}%</h2>
            <small>Based on selected filters</small>
        </div>
    </div>
</div>

<div class="filter-card mb-4">
    <form action="{{ route('principal.reports.results') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="exam_id" class="form-label fw-semibold">Exam</label>
            <select id="exam_id" name="exam_id" class="form-select">
                <option value="">All Exams</option>
                @foreach($exams as $exam)
                    <option value="{{ $exam->id }}" @selected(($filters['exam_id'] ?? '') == $exam->id)>{{ $exam->name }} - {{ $exam->academic_year }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="class_id" class="form-label fw-semibold">Class</label>
            <select id="class_id" name="class_id" class="form-select">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected(($filters['class_id'] ?? '') == $class->id)>{{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="subject_id" class="form-label fw-semibold">Subject</label>
            <select id="subject_id" name="subject_id" class="form-select">
                <option value="">All Subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected(($filters['subject_id'] ?? '') == $subject->id)>{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Apply</button>
            <a href="{{ route('principal.reports.results') }}" class="btn btn-light border">Reset</a>
        </div>
    </form>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="table-card h-100">
            <h5 class="section-title">Class Wise Result</h5>
            @include('principal.reports.partials.result-summary-table', ['results' => $classResults, 'empty' => 'No class result found.'])
        </div>
    </div>
    <div class="col-lg-4">
        <div class="table-card h-100">
            <h5 class="section-title">Subject Wise Result</h5>
            @include('principal.reports.partials.result-summary-table', ['results' => $subjectResults, 'empty' => 'No subject result found.'])
        </div>
    </div>
    <div class="col-lg-4">
        <div class="table-card h-100">
            <h5 class="section-title">Exam Wise Result</h5>
            @include('principal.reports.partials.result-summary-table', ['results' => $examResults, 'empty' => 'No exam result found.'])
        </div>
    </div>
</div>

<div class="table-card mt-4">
    <h5 class="section-title">Detailed Marks</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Exam</th>
                    <th>Marks</th>
                    <th>Percentage</th>
                    <th>Teacher</th>
                </tr>
            </thead>
            <tbody>
                @forelse($marks as $mark)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $mark->student?->name }}</strong>
                            <div class="small text-muted">{{ $mark->student?->admission_no }}</div>
                        </td>
                        <td>{{ $mark->schoolClass?->name }}{{ $mark->schoolClass?->section ? ' - '.$mark->schoolClass->section : '' }}</td>
                        <td>{{ $mark->subject?->name }}</td>
                        <td>{{ $mark->exam?->name }}</td>
                        <td>{{ number_format((float) $mark->marks_obtained, 2) }} / {{ number_format((float) $mark->max_marks, 2) }}</td>
                        <td>{{ $mark->max_marks > 0 ? number_format(((float) $mark->marks_obtained / (float) $mark->max_marks) * 100, 2) : '0.00' }}%</td>
                        <td>{{ $mark->teacher?->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No marks found for selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

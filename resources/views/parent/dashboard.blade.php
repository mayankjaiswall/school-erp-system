@extends('layouts.parent')

@section('title', 'Parent Dashboard')
@section('page-title', 'Parent Dashboard')

@section('content')
<style>
    .welcome-banner{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:26px 30px;border-radius:16px;margin-bottom:22px;box-shadow:0 10px 28px rgba(37,99,235,.22)}
    .welcome-banner h2{font-size:28px;line-height:1.25}
    .welcome-banner p{font-size:15px}
    .list-item{display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #f1f5f9}.list-item:last-child{border-bottom:none}
    .avatar{width:42px;height:42px;border-radius:50%;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}
    .content-card h5{font-size:18px;font-weight:650}
    @media (max-width:1199px){.welcome-banner h2{font-size:24px}}
</style>

<div class="welcome-banner">
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-end">
        <div>
            <h2 class="mb-1">Welcome, {{ auth()->user()->name }}</h2>
            <p class="mb-0">Track attendance, results, report cards and teacher remarks for your children.</p>
        </div>
        <div style="min-width:240px">
            <label class="form-label">Switch Child</label>
            <select id="dashboardStudent" class="form-select">
                @foreach($children as $child)
                    <option value="{{ $child->id }}">{{ $child->name }} - {{ $child->admission_no }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3"><div class="stats-card card-blue"><h6>Total Children</h6><h2>{{ $children->count() }}</h2><small>Linked to your profile</small></div></div>
    <div class="col-md-3"><div class="stats-card card-green"><h6>Today's Attendance</h6><h2 id="todayAttendance">{{ $todayAttendance ? ucfirst($todayAttendance->status) : '-' }}</h2><small id="attendancePercent">{{ $attendanceSummary['percentage'] }}% overall</small></div></div>
    <div class="col-md-3"><div class="stats-card card-orange"><h6>Upcoming Exams</h6><h2>{{ $upcomingExams->count() }}</h2><small>Scheduled exams</small></div></div>
    <div class="col-md-3"><div class="stats-card card-purple"><h6>Latest Result</h6><h2 id="latestPercent">{{ $latestResult['percentage'] ?? 0 }}%</h2><small id="latestExam">{{ $latestResult['exam'] ?? 'No result' }}</small></div></div>
</div>

<div class="row mt-4">
    <div class="col-lg-6">
        <div class="content-card">
            <h5 class="mb-3">My Children</h5>
            @forelse($children as $child)
                <div class="list-item">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar">{{ strtoupper(substr($child->name, 0, 1)) }}</div>
                        <div><strong>{{ $child->name }}</strong><br><small class="text-muted">{{ $child->class?->name }}{{ $child->class?->section ? ' - '.$child->class->section : '' }} | Roll: {{ $child->roll_no ?? '-' }}</small></div>
                    </div>
                    <span class="badge bg-success">{{ $child->pivot->relationship }}</span>
                </div>
            @empty
                <div class="text-center text-muted py-4">No linked children found.</div>
            @endforelse
        </div>
    </div>
    <div class="col-lg-6">
        <div class="content-card">
            <h5 class="mb-3">Recent Attendance</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Date</th><th>Status</th><th>Remarks</th></tr></thead>
                    <tbody id="recentAttendanceBody">
                        @forelse($recentAttendance as $attendance)
                            <tr><td>{{ $attendance->attendanceSession?->attendance_date?->format('Y-m-d') }}</td><td><span class="badge bg-info">{{ ucfirst($attendance->status) }}</span></td><td>{{ $attendance->remarks ?? '-' }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">No attendance found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-4"><div class="content-card"><h5 class="mb-3">Latest Results</h5><div id="latestResultsList">@forelse($latestResults as $exam)<div class="list-item"><div><strong>{{ $exam->name }}</strong><br><small class="text-muted">{{ $exam->exam_type }}</small></div><span class="badge bg-primary">{{ $exam->exam_date?->format('d M Y') }}</span></div>@empty<div class="text-muted text-center py-4">No results found.</div>@endforelse</div></div></div>
    <div class="col-lg-4"><div class="content-card"><h5 class="mb-3">Upcoming Exams</h5>@forelse($upcomingExams as $exam)<div class="list-item"><div><strong>{{ $exam['name'] }}</strong><br><small class="text-muted">{{ $exam['class'] }}</small></div><span class="badge bg-warning text-dark">{{ $exam['exam_date'] }}</span></div>@empty<div class="text-muted text-center py-4">No upcoming exams.</div>@endforelse</div></div>
    <div class="col-lg-4"><div class="content-card"><h5 class="mb-3">Teacher Remarks</h5><div id="remarksList">@forelse($remarks as $remark)<div class="list-item"><div><strong>{{ $remark->teacher?->name ?? 'Teacher' }}</strong><br><small class="text-muted">{{ $remark->remark_date?->format('d M Y') }}</small><p class="mb-0 mt-1">{{ $remark->remark }}</p></div></div>@empty<div class="text-muted text-center py-4">No remarks found.</div>@endforelse</div></div></div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const attendanceUrl = "{{ route('parent.attendance') }}";
    const resultsUrl = "{{ route('parent.results') }}";
    const remarksUrl = "{{ route('parent.remarks') }}";

    function escapeHtml(value) { return $('<div>').text(value || '').html(); }

    $('#dashboardStudent').on('change', function () {
        const studentId = $(this).val();

        $.getJSON(attendanceUrl, {student_id: studentId}).done(function (response) {
            const today = response.records.find((record) => record.date === new Date().toISOString().slice(0, 10));
            $('#todayAttendance').text(today ? today.status : '-');
            $('#attendancePercent').text(response.summary.percentage + '% overall');
            $('#recentAttendanceBody').html(response.records.slice(0, 6).map((record) => `<tr><td>${escapeHtml(record.date)}</td><td><span class="badge bg-info">${escapeHtml(record.status)}</span></td><td>${escapeHtml(record.remarks)}</td></tr>`).join('') || '<tr><td colspan="3" class="text-center text-muted">No attendance found.</td></tr>');
        });

        $.getJSON(resultsUrl, {student_id: studentId}).done(function (response) {
            const latest = response.results[0];
            $('#latestPercent').text(latest ? latest.percentage + '%' : '0%');
            $('#latestExam').text(latest ? latest.exam : 'No result');
            $('#latestResultsList').html(response.results.slice(0, 5).map((result) => `<div class="list-item"><div><strong>${escapeHtml(result.exam)}</strong><br><small class="text-muted">${escapeHtml(result.exam_type)}</small></div><span class="badge bg-primary">${escapeHtml(result.percentage)}%</span></div>`).join('') || '<div class="text-muted text-center py-4">No results found.</div>');
        });

        $.getJSON(remarksUrl, {student_id: studentId}).done(function (response) {
            $('#remarksList').html(response.remarks.slice(0, 5).map((remark) => `<div class="list-item"><div><strong>${escapeHtml(remark.teacher)}</strong><br><small class="text-muted">${escapeHtml(remark.date)}</small><p class="mb-0 mt-1">${escapeHtml(remark.remark)}</p></div></div>`).join('') || '<div class="text-muted text-center py-4">No remarks found.</div>');
        });
    });
});
</script>
@endpush

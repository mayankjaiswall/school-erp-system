@extends('layouts.teacher')

@section('title', 'Teacher Profile')
@section('page-title', 'Teacher Profile')

@section('content')
<style>
    .profile-header{background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;padding:32px;border-radius:20px;margin-bottom:25px;box-shadow:0 15px 35px rgba(22,163,74,.25)}
    .profile-card,.stats-card{background:#fff;border:1px solid #e2e8f0;box-shadow:0 8px 20px rgba(15,23,42,.05)}
    .profile-card{border-radius:18px;padding:24px;height:100%}
    .profile-card small{display:block;color:#64748b;font-weight:700;text-transform:uppercase;font-size:12px;margin-bottom:6px}
    .profile-card h5{margin:0;color:#0f172a;font-weight:700}
    .stats-card{border-radius:16px;padding:20px;text-align:center}
    .stats-card h3{margin:0;color:#16a34a;font-weight:700}
    .stats-card span{color:#64748b;font-size:14px}
    .section-card{background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:24px;margin-top:24px;box-shadow:0 8px 20px rgba(15,23,42,.05)}
</style>

<div class="profile-header">
    <h2 class="mb-2">{{ $teacher->name }}</h2>
    <p class="mb-0">
        {{ $teacher->designation ?? 'Teacher' }}
        @if($teacher->primarySubject)
            &bull; {{ $teacher->primarySubject->name }} Specialist
        @endif
    </p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3"><div class="stats-card"><h3>{{ $workload['assigned_classes'] }}</h3><span>Assigned Classes</span></div></div>
    <div class="col-md-3"><div class="stats-card"><h3>{{ $workload['assigned_subjects'] }}</h3><span>Assigned Subjects</span></div></div>
    <div class="col-md-3"><div class="stats-card"><h3>{{ $workload['total_students'] }}</h3><span>Total Students</span></div></div>
    <div class="col-md-3"><div class="stats-card"><h3>{{ $workload['marks_records'] }}</h3><span>Marks Submitted</span></div></div>
</div>

<div class="row g-4">
    <div class="col-md-4"><div class="profile-card"><small>Employee Code</small><h5>{{ $teacher->employee_code ?? 'N/A' }}</h5></div></div>
    <div class="col-md-4"><div class="profile-card"><small>Phone</small><h5>{{ $teacher->phone ?? 'N/A' }}</h5></div></div>
    <div class="col-md-4"><div class="profile-card"><small>Email</small><h5>{{ $teacher->email ?? 'N/A' }}</h5></div></div>
    <div class="col-md-4"><div class="profile-card"><small>Qualification</small><h5>{{ $teacher->qualification ?? 'N/A' }}</h5></div></div>
    <div class="col-md-4"><div class="profile-card"><small>Experience</small><h5>{{ is_null($teacher->display_experience) ? 'N/A' : $teacher->display_experience . ' Years' }}</h5></div></div>
    <div class="col-md-4"><div class="profile-card"><small>Joining Date</small><h5>{{ $teacher->joining_date ? $teacher->joining_date->format('d M Y') : 'N/A' }}</h5></div></div>
    <div class="col-md-6"><div class="profile-card"><small>Designation</small><h5>{{ $teacher->designation ?? 'N/A' }}</h5></div></div>
    <div class="col-md-6"><div class="profile-card"><small>Primary Subject</small><h5>{{ $teacher->primarySubject?->name ?? 'N/A' }}</h5></div></div>
</div>

<div class="section-card">
    <h5 class="mb-3">Assignments</h5>
    <div class="row g-4">
        <div class="col-md-6">
            <h6>Classes</h6>
            @forelse($assignedClasses as $class)
                <span class="badge bg-success me-2 mb-2">{{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}</span>
            @empty
                <p class="text-muted mb-0">No classes assigned.</p>
            @endforelse
        </div>
        <div class="col-md-6">
            <h6>Subjects</h6>
            @forelse($assignedSubjects as $subject)
                <span class="badge bg-primary me-2 mb-2">{{ $subject->name }}</span>
            @empty
                <p class="text-muted mb-0">No subjects assigned.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="section-card">
            <h5 class="mb-3">Recent Attendance Activity</h5>
            <table class="table align-middle">
                <thead><tr><th>Date</th><th>Class</th></tr></thead>
                <tbody>
                    @forelse($recentAttendance as $session)
                        <tr>
                            <td>{{ $session->attendance_date->format('d M Y') }}</td>
                            <td>{{ $session->schoolClass?->name }}{{ $session->schoolClass?->section ? ' - '.$session->schoolClass->section : '' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center text-muted">No attendance activity yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="section-card">
            <h5 class="mb-3">Recent Marks Activity</h5>
            <table class="table align-middle">
                <thead><tr><th>Exam</th><th>Class</th><th>Subject</th></tr></thead>
                <tbody>
                    @forelse($recentMarks as $mark)
                        <tr>
                            <td>{{ $mark->exam?->name ?? 'N/A' }}</td>
                            <td>{{ $mark->schoolClass?->name }}{{ $mark->schoolClass?->section ? ' - '.$mark->schoolClass->section : '' }}</td>
                            <td>{{ $mark->subject?->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">No marks activity yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

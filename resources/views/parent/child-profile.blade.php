@extends('layouts.parent')

@section('title', 'Child Profile')
@section('page-title', 'Child Profile')

@section('content')
<style>.child-header{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:18px;margin-bottom:25px;box-shadow:0 12px 28px rgba(37,99,235,.22)}.child-photo-lg{width:110px;height:110px;border-radius:50%;object-fit:cover;border:4px solid rgba(255,255,255,.55)}.info-tile{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:18px;height:100%}.info-tile small{display:block;color:#64748b;font-weight:600;margin-bottom:5px}.info-tile strong{color:#0f172a}</style>
<div class="child-header">
    <div class="d-flex flex-wrap gap-4 align-items-center justify-content-between">
        <div class="d-flex gap-3 align-items-center">
            <img class="child-photo-lg" src="{{ $child->photo ? asset($child->photo) : 'https://ui-avatars.com/api/?name='.urlencode($child->name).'&background=2563eb&color=fff' }}" alt="{{ $child->name }}">
            <div>
                <h2 class="mb-1">{{ $child->name }}</h2>
                <p class="mb-0">{{ $child->admission_no }} | Roll: {{ $child->roll_no ?? '-' }}</p>
            </div>
        </div>
        <a href="{{ route('parent.children') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3"><div class="stats-card card-green"><h6>Attendance</h6><h2>{{ $attendanceSummary['percentage'] }}%</h2><small>{{ $attendanceSummary['present_days'] + $attendanceSummary['late_days'] }} / {{ $attendanceSummary['total_days'] }} days</small></div></div>
    <div class="col-md-3"><div class="stats-card card-blue"><h6>Latest Result</h6><h2>{{ $latestResult['percentage'] ?? 0 }}%</h2><small>{{ $latestResult['exam'] ?? 'No result' }}</small></div></div>
    <div class="col-md-3"><div class="stats-card card-orange"><h6>Class</h6><h2>{{ $child->class?->name ?? '-' }}</h2><small>Section {{ $child->class?->section ?? '-' }}</small></div></div>
    <div class="col-md-3"><div class="stats-card card-purple"><h6>Status</h6><h2>{{ $child->status ? 'Active' : 'Inactive' }}</h2><small>Academic status</small></div></div>
</div>

<div class="content-card">
    <h5 class="mb-3">Student Information</h5>
    <div class="row g-3">
        <div class="col-md-4"><div class="info-tile"><small>Admission Number</small><strong>{{ $child->admission_no }}</strong></div></div>
        <div class="col-md-4"><div class="info-tile"><small>Roll Number</small><strong>{{ $child->roll_no ?? '-' }}</strong></div></div>
        <div class="col-md-4"><div class="info-tile"><small>Date of Birth</small><strong>{{ $child->dob?->format('d M Y') ?? '-' }}</strong></div></div>
        <div class="col-md-4"><div class="info-tile"><small>Gender</small><strong>{{ $child->gender ?? '-' }}</strong></div></div>
        <div class="col-md-4"><div class="info-tile"><small>Email</small><strong>{{ $child->email ?? '-' }}</strong></div></div>
        <div class="col-md-4"><div class="info-tile"><small>Phone</small><strong>{{ $child->phone ?? '-' }}</strong></div></div>
        <div class="col-12"><div class="info-tile"><small>Address</small><strong>{{ $child->address ?? '-' }}</strong></div></div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="content-card">
            <h5 class="mb-3">Recent Attendance</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Date</th><th>Status</th><th>Remarks</th></tr></thead>
                    <tbody>
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
    <div class="col-lg-6">
        <div class="content-card">
            <h5 class="mb-3">Recent Teacher Remarks</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Date</th><th>Teacher</th><th>Remark</th></tr></thead>
                    <tbody>
                        @forelse($recentRemarks as $remark)
                            <tr><td>{{ $remark->remark_date?->format('Y-m-d') }}</td><td>{{ $remark->teacher?->name ?? $remark->teacher?->user?->name ?? 'Teacher' }}</td><td>{{ $remark->remark }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">No remarks found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

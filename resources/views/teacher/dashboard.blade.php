@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')

@section('page-title', 'Teacher Dashboard')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: #fff;
        padding: 35px;
        border-radius: 20px;
        margin-bottom: 25px;
        box-shadow: 0 15px 35px rgba(22, 163, 74, .25)
    }

    .welcome-banner h2 {
        margin: 0;
        font-weight: 700
    }

    .stats-card {
        color: #fff;
        padding: 25px;
        border-radius: 18px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .08)
    }

    .stats-card h2 {
        font-size: 34px;
        margin: 10px 0;
        font-weight: 700
    }

    .stats-card::after {
        content: '';
        position: absolute;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, .15);
        border-radius: 50%;
        top: -20px;
        right: -20px
    }

    .card-blue {
        background: linear-gradient(135deg, #2563eb, #1d4ed8)
    }

    .card-green {
        background: linear-gradient(135deg, #16a34a, #15803d)
    }

    .card-orange {
        background: linear-gradient(135deg, #ea580c, #c2410c)
    }

    .card-purple {
        background: linear-gradient(135deg, #9333ea, #7e22ce)
    }

    .content-card {
        background: #fff;
        border-radius: 20px;
        padding: 25px;
        margin-top: 25px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .05)
    }

    .list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9
    }

    .list-item:last-child {
        border-bottom: none
    }

    .avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #16a34a;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700
    }

    .quick-btn {
        width: 100%;
        margin-bottom: 12px;
        padding: 12px;
        border-radius: 12px;
        font-weight: 600
    }
</style>

<div class="welcome-banner">
    <h2>Welcome, {{ auth()->user()->name }}</h2>
    <p class="mb-0">View your assigned classes, subjects and students.</p>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="stats-card card-blue">
            <h6>Assigned Classes</h6>
            <h2>{{ $totalClasses }}</h2>
            <small>Your class sections</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card card-green">
            <h6>Assigned Subjects</h6>
            <h2>{{ $totalSubjects }}</h2>
            <small>Your teaching subjects</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card card-orange">
            <h6>My Students</h6>
            <h2>{{ $totalStudents }}</h2>
            <small>In assigned classes</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card card-purple">
            <h6>Employee Code</h6>
            <h2>{{ $teacher->employee_code ?? '-' }}</h2>
            <small>Teacher profile</small>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <div class="content-card">
            <h5 class="mb-3">Assigned Classes</h5>
            @forelse($assignedClasses as $class)
            <div class="list-item">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar">{{ strtoupper(substr($class->name, 0, 1)) }}</div>
                    <div>
                        <strong>{{ $class->name }}</strong>
                        <br>
                        <small class="text-muted">Section: {{ $class->section ?? '-' }}</small>
                    </div>
                </div>
                <span class="badge bg-success">Assigned</span>
            </div>
            @empty
            <div class="text-center text-muted py-4">No classes assigned yet</div>
            @endforelse
        </div>
    </div>

    <div class="col-lg-4">
        <div class="content-card">
            <h5 class="mb-4">Quick Actions</h5>
            <a href="{{ route('teacher.attendance.index') }}" class="btn btn-primary quick-btn">Take Attendance</a>
            <a href="#" class="btn btn-success quick-btn disabled">Enter Marks</a>
            <a href="{{ route('teacher.attendance.report') }}" class="btn btn-warning text-white quick-btn">View Reports</a>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-5">
        <div class="content-card">
            <h5 class="mb-3">Assigned Subjects</h5>
            @forelse($assignedSubjects as $subject)
            <div class="list-item">
                <div>
                    <strong>{{ $subject->name }}</strong>
                    <br>
                    <small class="text-muted">{{ $subject->code ?? 'No code' }}</small>
                </div>
                <span class="badge bg-primary">Subject</span>
            </div>
            @empty
            <div class="text-center text-muted py-4">No subjects assigned yet</div>
            @endforelse
        </div>
    </div>

    <div class="col-lg-7">
        <div class="content-card">
            <h5 class="mb-3">Recent Students</h5>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentStudents as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->class?->name ?? '-' }}</td>
                        <td><span class="badge bg-success">Active</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No students found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

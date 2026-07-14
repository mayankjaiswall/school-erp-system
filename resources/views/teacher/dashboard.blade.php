@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')

@section('page-title', 'Teacher Dashboard')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: #fff;
        padding: 26px 30px;
        border-radius: 16px;
        margin-bottom: 22px;
        box-shadow: 0 10px 28px rgba(22, 163, 74, .22)
    }

    .welcome-banner h2 {
        margin: 0;
        font-weight: 700;
        font-size: 28px;
        line-height: 1.25
    }

    .welcome-banner p {
        font-size: 15px
    }

    .stats-card {
        color: #fff;
        padding: 20px 22px;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        min-height: 126px;
        box-shadow: 0 8px 22px rgba(0, 0, 0, .07)
    }

    .stats-card h2 {
        font-size: 28px;
        margin: 9px 0;
        font-weight: 700;
        line-height: 1
    }

    .stats-card h6 {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 0
    }

    .stats-card small {
        font-size: 13px
    }

    .stats-card::after {
        content: '';
        position: absolute;
        width: 92px;
        height: 92px;
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
        border-radius: 16px;
        padding: 22px;
        margin-top: 22px;
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
        width: 42px;
        height: 42px;
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
        padding: 10px;
        border-radius: 10px;
        font-weight: 600
    }
    .content-card h5 {
        font-size: 18px;
        font-weight: 650
    }

    @media (max-width: 1199px) {
        .welcome-banner h2 {
            font-size: 24px
        }
    }
</style>

<div class="welcome-banner">
    <h2>Welcome, {{ auth()->user()->name }}</h2>
    <p class="mb-0">
        {{ $teacher->designation ?? 'Teacher' }}
        @if($teacher->primarySubject)
            &bull; Subject Specialist: {{ $teacher->primarySubject->name }}
        @endif
    </p>
</div>

<div class="content-card mt-0 mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-md-3">
            <small class="text-muted fw-semibold">Teacher Name</small>
            <div class="fw-bold">{{ $teacher->name }}</div>
        </div>
        <div class="col-md-3">
            <small class="text-muted fw-semibold">Designation</small>
            <div class="fw-bold">{{ $teacher->designation ?? 'N/A' }}</div>
        </div>
        <div class="col-md-2">
            <small class="text-muted fw-semibold">Primary Subject</small>
            <div class="fw-bold">{{ $teacher->primarySubject?->name ?? 'N/A' }}</div>
        </div>
        <div class="col-md-2">
            <small class="text-muted fw-semibold">Qualification</small>
            <div class="fw-bold">{{ $teacher->qualification ?? 'N/A' }}</div>
        </div>
        <div class="col-md-2">
            <small class="text-muted fw-semibold">Experience</small>
            <div class="fw-bold">{{ is_null($teacher->display_experience) ? 'N/A' : $teacher->display_experience . ' Years' }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
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
            <a href="{{ route('teacher.marks.index') }}" class="btn btn-success quick-btn">Enter Marks</a>
            <a href="{{ route('teacher.profile') }}" class="btn btn-outline-primary quick-btn">View Profile</a>
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

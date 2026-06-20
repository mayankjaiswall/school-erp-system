@extends('layouts.principal')

@section('title', 'Teacher Details')

@section('page-title', 'Teacher Details')

@section('content')

<style>
    .teacher-profile-card{
        background: linear-gradient(135deg,#2563eb,#1d4ed8);
        color:#fff;
        padding:30px;
        border-radius:20px;
        margin-bottom:25px;
        box-shadow:0 15px 35px rgba(37,99,235,.25);
    }

    .teacher-name{
        margin:0;
        font-size:32px;
        font-weight:700;
    }

    .teacher-subtitle{
        margin-top:6px;
        opacity:.9;
    }

    .status-pill{
        padding:10px 20px;
        border-radius:50px;
        font-size:14px;
        font-weight:600;
    }

    .status-pill.active{
        background:#22c55e;
        color:#fff;
    }

    .status-pill.inactive{
        background:#ef4444;
        color:#fff;
    }

    .info-card{
        background:#fff;
        padding:25px;
        border-radius:16px;
        border:1px solid #e2e8f0;
        box-shadow:0 8px 20px rgba(15,23,42,.05);
        height:100%;
        transition:.3s;
    }

    .info-card:hover{
        transform:translateY(-3px);
    }

    .info-card small{
        display:block;
        color:#64748b;
        font-weight:600;
        margin-bottom:10px;
        text-transform:uppercase;
        letter-spacing:.5px;
    }

    .info-card h5{
        margin:0;
        color:#0f172a;
        font-weight:700;
    }

    .address-card{
        background:#fff;
        padding:25px;
        border-radius:16px;
        margin-top:25px;
        border:1px solid #e2e8f0;
        box-shadow:0 8px 20px rgba(15,23,42,.05);
    }

    .address-card h5{
        margin-bottom:15px;
        font-weight:700;
    }

    .address-card p{
        margin:0;
        color:#64748b;
        line-height:1.8;
    }

    .action-buttons{
        margin-top:25px;
        display:flex;
        justify-content:flex-end;
        gap:12px;
    }

    .btn-back{
        border:1px solid #d1d5db;
        background:#fff;
        color:#374151;
        padding:10px 20px;
        border-radius:10px;
        text-decoration:none;
        font-weight:600;
    }

    .btn-edit{
        background:#2563eb;
        color:#fff;
        padding:10px 20px;
        border-radius:10px;
        text-decoration:none;
        font-weight:600;
    }

    .btn-edit:hover{
        color:#fff;
        background:#1d4ed8;
    }

    .stats-card{
        background:#fff;
        padding:20px;
        border-radius:16px;
        text-align:center;
        box-shadow:0 8px 20px rgba(15,23,42,.05);
        border:1px solid #e2e8f0;
    }

    .stats-card h3{
        margin:0;
        color:#2563eb;
        font-weight:700;
    }

    .stats-card span{
        color:#64748b;
        font-size:14px;
    }
</style>

<!-- Teacher Header -->

<div class="teacher-profile-card">

    <div class="d-flex justify-content-between align-items-center">

        <div>

            <h2 class="teacher-name">
                {{ $teacher->name }}
            </h2>

            <p class="teacher-subtitle">
                {{ $teacher->designation ?? 'Teacher' }} @if($teacher->primarySubject) &bull; {{ $teacher->primarySubject->name }} Specialist @endif
            </p>

        </div>

        <div>

            @if($teacher->status)
                <span class="status-pill active">
                    Active Teacher
                </span>
            @else
                <span class="status-pill inactive">
                    Inactive Teacher
                </span>
            @endif

        </div>

    </div>

</div>

<!-- Quick Stats -->

<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ $workload['assigned_classes'] }}</h3>
            <span>Assigned Classes</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ $workload['assigned_subjects'] }}</h3>
            <span>Assigned Subjects</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ $workload['total_students'] }}</h3>
            <span>Total Students</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ $workload['attendance_records'] }}</h3>
            <span>Attendance Records</span>
        </div>
    </div>

</div>

<!-- Information Cards -->

<div class="row g-4">

    <div class="col-md-4">

        <div class="info-card">

            <small>Full Name</small>

            <h5>
                {{ $teacher->name }}
            </h5>

        </div>

    </div>

    <div class="col-md-4">

        <div class="info-card">

            <small>Email Address</small>

            <h5>
                {{ $teacher->email }}
            </h5>

        </div>

    </div>

    <div class="col-md-4">

        <div class="info-card">

            <small>Employee Code</small>

            <h5>
                {{ $teacher->employee_code ?? 'N/A' }}
            </h5>

        </div>

    </div>

    <div class="col-md-4">

        <div class="info-card">

            <small>Status</small>

            <h5>
                {{ $teacher->status ? 'Active' : 'Inactive' }}
            </h5>

        </div>

    </div>

    <div class="col-md-4">

        <div class="info-card">

            <small>Designation</small>

            <h5>
                {{ $teacher->designation ?? 'N/A' }}
            </h5>

        </div>

    </div>

    <div class="col-md-4">

        <div class="info-card">

            <small>Primary Subject</small>

            <h5>
                {{ $teacher->primarySubject?->name ?? 'N/A' }}
            </h5>

        </div>

    </div>

    <div class="col-md-6">

        <div class="info-card">

            <small>Email Address</small>

            <h5>
                {{ $teacher->email ?? 'N/A' }}
            </h5>

        </div>

    </div>

    <div class="col-md-6">

        <div class="info-card">

            <small>Phone Number</small>

            <h5>
                {{ $teacher->phone ?? 'N/A' }}
            </h5>

        </div>

    </div>

    <div class="col-md-6">

        <div class="info-card">

            <small>Qualification</small>

            <h5>
                {{ $teacher->qualification ?? 'N/A' }}
            </h5>

        </div>

    </div>

    <div class="col-md-6">

        <div class="info-card">

            <small>Experience</small>

            <h5>
                {{ is_null($teacher->display_experience) ? 'N/A' : $teacher->display_experience . ' Years' }}
            </h5>

        </div>

    </div>

    <div class="col-md-6">

        <div class="info-card">

            <small>Joining Date</small>

            <h5>
                {{ $teacher->joining_date ? $teacher->joining_date->format('d M Y') : 'N/A' }}
            </h5>

        </div>

    </div>

    <div class="col-md-6">

        <div class="info-card">

            <small>Linked User ID</small>

            <h5>
                {{ $teacher->user_id ?? 'N/A' }}
            </h5>

        </div>

    </div>

</div>

<div class="row g-4 mt-2">
    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ $workload['marks_records'] }}</h3>
            <span>Marks Records Submitted</span>
        </div>
    </div>
</div>

<div class="address-card">

    <h5>
        <i class="bi bi-journal-bookmark-fill text-primary"></i>
        Assigned Classes
    </h5>

    <p>
        @forelse($assignedClasses as $class)
            <span class="badge bg-primary me-2 mb-2">
                {{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}
            </span>
        @empty
            No classes assigned.
        @endforelse
    </p>

</div>

<div class="address-card">

    <h5>
        <i class="bi bi-book-fill text-primary"></i>
        Assigned Subjects
    </h5>

    <p>
        @forelse($assignedSubjects as $subject)
            <span class="badge bg-success me-2 mb-2">
                {{ $subject->name }}
            </span>
        @empty
            No subjects assigned.
        @endforelse
    </p>

</div>

<!-- System Information -->

<div class="row g-4 mt-2">

    <div class="col-md-6">

        <div class="info-card">

            <small>Created At</small>

            <h5>
                {{ $teacher->created_at->format('d M Y, h:i A') }}
            </h5>

        </div>

    </div>

</div>

<!-- Action Buttons -->

<div class="action-buttons">

    <a href="{{ route('teachers.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i>
        Back
    </a>

    <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn-edit">
        <i class="bi bi-pencil-square"></i>
        Edit Teacher
    </a>

</div>

@endsection

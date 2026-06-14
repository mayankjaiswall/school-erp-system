@extends('layouts.principal')

@section('title', 'Principal Dashboard')

@section('page-title', 'Principal Dashboard')

@section('content')

<style>
    .welcome-banner{
        background: linear-gradient(135deg,#16a34a,#15803d);
        color:#fff;
        padding:35px;
        border-radius:20px;
        margin-bottom:25px;
        box-shadow:0 15px 35px rgba(22,163,74,.25);
    }

    .welcome-banner h2{
        margin:0;
        font-weight:700;
    }

    .stats-card{
        color:#fff;
        padding:25px;
        border-radius:18px;
        position:relative;
        overflow:hidden;
        box-shadow:0 8px 20px rgba(0,0,0,.08);
    }

    .stats-card h2{
        font-size:34px;
        margin:10px 0;
        font-weight:700;
    }

    .stats-card::after{
        content:'';
        position:absolute;
        width:100px;
        height:100px;
        background:rgba(255,255,255,.15);
        border-radius:50%;
        top:-20px;
        right:-20px;
    }

    .card-blue{
        background:linear-gradient(135deg,#2563eb,#1d4ed8);
    }

    .card-green{
        background:linear-gradient(135deg,#16a34a,#15803d);
    }

    .card-orange{
        background:linear-gradient(135deg,#ea580c,#c2410c);
    }

    .card-purple{
        background:linear-gradient(135deg,#9333ea,#7e22ce);
    }

    .content-card{
        background:#fff;
        border-radius:20px;
        padding:25px;
        margin-top:25px;
        border:1px solid #e2e8f0;
        box-shadow:0 8px 20px rgba(15,23,42,.05);
    }

    .quick-btn{
        width:100%;
        margin-bottom:12px;
        padding:12px;
        border-radius:12px;
        font-weight:600;
    }

    .list-item{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:12px 0;
        border-bottom:1px solid #f1f5f9;
    }

    .list-item:last-child{
        border-bottom:none;
    }

    .avatar{
        width:45px;
        height:45px;
        border-radius:50%;
        background:#2563eb;
        color:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:700;
    }
</style>

<div class="welcome-banner">

    <h2>
        Welcome, {{ auth()->user()->name }} 👋
    </h2>

    <p class="mb-0">
        Manage your school, teachers, students and classes.
    </p>

</div>

<div class="row g-4">

    <div class="col-md-3">
        <div class="stats-card card-blue">
            <h6>Total Teachers</h6>
            <h2>{{ $totalTeachers ?? 0 }}</h2>
            <small>Active Teachers</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card card-green">
            <h6>Total Students</h6>
            <h2>{{ $totalStudents ?? 0 }}</h2>
            <small>Enrolled Students</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card card-orange">
            <h6>Total Classes</h6>
            <h2>{{ $totalClasses ?? 0 }}</h2>
            <small>Available Classes</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card card-purple">
            <h6>Total Subjects</h6>
            <h2>{{ $totalSubjects ?? 0 }}</h2>
            <small>School Subjects</small>
        </div>
    </div>

</div>

<div class="row mt-4">

    <div class="col-lg-8">

        <div class="content-card">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Recent Teachers</h5>
            </div>

            @forelse($recentTeachers ?? [] as $teacher)

                <div class="list-item">

                    <div class="d-flex align-items-center gap-3">

                        <div class="avatar">
                            {{ strtoupper(substr($teacher->name,0,1)) }}
                        </div>

                        <div>
                            <strong>{{ $teacher->name }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ $teacher->email }}
                            </small>
                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center text-muted py-4">
                    No Teachers Found
                </div>

            @endforelse

        </div>

    </div>

    <div class="col-lg-4">

        <div class="content-card">

            <h5 class="mb-4">
                Quick Actions
            </h5>

            <a href="{{ route('teachers.create') }}"
               class="btn btn-primary quick-btn">
                Add Teacher
            </a>

            <a href="{{ route('students.create') }}"
               class="btn btn-success quick-btn">
                Add Student
            </a>

            <a href="{{ route('classes.create') }}"
               class="btn btn-warning text-white quick-btn">
                Create Class
            </a>

        </div>

    </div>

</div>

<div class="content-card">

    <h5 class="mb-3">
        Recent Students
    </h5>

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

            @forelse($recentStudents ?? [] as $student)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student->name }}</td>
                    <td>
                        {{ $student->class?->name ?? '-' }}{{ $student->class?->section ? ' - '.$student->class->section : '' }}
                    </td>
                    <td>
                        <span class="badge {{ $student->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $student->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4" class="text-center text-muted">
                        No Students Found
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection

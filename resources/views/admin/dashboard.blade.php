@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Super Admin Dashboard')

@section('content')

<style>
    .welcome-banner{
        background: linear-gradient(135deg,#2563eb,#1d4ed8);
        color:#fff;
        padding:35px;
        border-radius:20px;
        margin-bottom:25px;
        box-shadow:0 15px 35px rgba(37,99,235,.25);
    }

    .welcome-banner h2{
        margin:0;
        font-weight:700;
    }

    .welcome-banner p{
        margin-top:10px;
        opacity:.9;
    }

    .dashboard-card{
        color:#fff;
        padding:25px;
        border-radius:20px;
        position:relative;
        overflow:hidden;
        transition:.3s;
        box-shadow:0 8px 25px rgba(0,0,0,.08);
    }

    .dashboard-card:hover{
        transform:translateY(-5px);
    }

    .dashboard-card::after{
        content:'';
        position:absolute;
        width:120px;
        height:120px;
        border-radius:50%;
        background:rgba(255,255,255,.15);
        top:-30px;
        right:-30px;
    }

    .dashboard-card h2{
        font-size:32px;
        font-weight:700;
        margin:10px 0;
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
        box-shadow:0 8px 20px rgba(15,23,42,.05);
        border:1px solid #e2e8f0;
    }

    .school-item{
        display:flex;
        align-items:center;
        gap:15px;
        padding:15px 0;
        border-bottom:1px solid #f1f5f9;
    }

    .school-item:last-child{
        border-bottom:none;
    }

    .avatar{
        width:50px;
        height:50px;
        border-radius:50%;
        background:#2563eb;
        color:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:700;
    }

    .quick-btn{
        width:100%;
        margin-bottom:10px;
        border-radius:12px;
        padding:12px;
        font-weight:600;
    }

    .overview-box{
        text-align:center;
        padding:20px;
        border-radius:15px;
        background:#f8fafc;
    }

    .overview-box h3{
        color:#2563eb;
        font-weight:700;
        margin-bottom:5px;
    }
</style>
<!-- Welcome Banner -->
<div class="welcome-banner">
    <h2>
        Welcome Back, {{ auth()->user()->name }} 👋
    </h2>
    <p>
        Manage schools, users, roles and monitor your complete School ERP SaaS Platform.
    </p>
</div>
<!-- Statistics -->
<div class="row g-4">
    <div class="col-md-3">
        <div class="dashboard-card card-blue">
            <h6>Total Schools</h6>
            <h2>{{ $totalSchools ?? 0 }}</h2>
            <small>Registered Schools</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card card-green">
            <h6>Total Users</h6>
            <h2>{{ $totalUsers ?? 0 }}</h2>
            <small>System Users</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card card-orange">
            <h6>Total Roles</h6>
            <h2>{{ $totalRoles ?? 0 }}</h2>
            <small>Available Roles</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card card-purple">
            <h6>Active Schools</h6>
            <h2>{{ $activeSchools ?? 0 }}</h2>
            <small>Currently Active</small>
        </div>
    </div>
</div>
<!-- Second Row -->
<div class="row mt-4">
    <!-- Recent Schools -->
    <div class="col-lg-8">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    Recent Schools
                </h5>
                <a href="{{ route('schools.index') }}"
                   class="btn btn-primary btn-sm">
                    View All
                </a>
            </div>
            @forelse($recentSchools as $school)
                <div class="school-item">
                    <div class="avatar">
                        {{ strtoupper(substr($school->name,0,1)) }}
                    </div>
                    <div>
                        <strong>
                            {{ $school->name }}
                        </strong>
                        <br>
                        <small class="text-muted">
                            {{ $school->email }}
                        </small>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    No Schools Found
                </div>
            @endforelse
        </div>
    </div>
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="content-card">
            <h5 class="mb-4">
                Quick Actions
            </h5>
            <a href="{{ route('schools.create') }}"
               class="btn btn-primary quick-btn">
                <i class="bi bi-plus-circle"></i>
                Add New School
            </a>
            <a href="#"
               class="btn btn-success quick-btn">
                <i class="bi bi-person-plus"></i>
                Create User
            </a>
            <a href="#"
               class="btn btn-warning quick-btn text-white">
                <i class="bi bi-shield-lock"></i>
                Manage Roles
            </a>
        </div>
    </div>
</div>
<!-- Recent Users -->
<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            Recent Users
        </h5>
    </div>
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
        @forelse($recentUsers as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge bg-primary">
                        {{ $user->role->name ?? 'N/A' }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4"
                    class="text-center text-muted">
                    No Users Found
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
<!-- Platform Overview -->
<div class="content-card">
    <h5 class="mb-4">
        Platform Overview
    </h5>
    <div class="row">
        <div class="col-md-3">
            <div class="overview-box">
                <h3>{{ $totalSchools ?? 0 }}</h3>
                <span>Total Schools</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="overview-box">
                <h3>{{ $totalUsers ?? 0 }}</h3>
                <span>Total Users</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="overview-box">
                <h3>{{ $activeSchools ?? 0 }}</h3>
                <span>Active Schools</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="overview-box">
                <h3>{{ $totalRoles ?? 0 }}</h3>
                <span>Roles</span>
            </div>
        </div>
    </div>
</div>

@endsection
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Super Admin Dashboard')

@section('content')

<style>
    .welcome-banner {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        padding: 26px 30px;
        border-radius: 16px;
        margin-bottom: 22px;
        box-shadow: 0 10px 28px rgba(37, 99, 235, .22);
    }

    .welcome-banner h2 {
        margin: 0;
        font-weight: 700;
        font-size: 28px;
        line-height: 1.25;
    }

    .welcome-banner p {
        margin-top: 8px;
        opacity: .9;
        font-size: 15px;
    }

    .dashboard-card {
        color: #fff;
        padding: 20px 22px;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        transition: .3s;
        min-height: 126px;
        box-shadow: 0 8px 22px rgba(0, 0, 0, .07);
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
    }

    .dashboard-card::after {
        content: '';
        position: absolute;
        width: 92px;
        height: 92px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .15);
        top: -30px;
        right: -30px;
    }

    .dashboard-card h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 9px 0;
        line-height: 1;
    }

    .dashboard-card h6 {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 0;
    }

    .dashboard-card small {
        font-size: 13px;
    }

    .card-blue {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .card-green {
        background: linear-gradient(135deg, #16a34a, #15803d);
    }

    .card-orange {
        background: linear-gradient(135deg, #ea580c, #c2410c);
    }

    .card-purple {
        background: linear-gradient(135deg, #9333ea, #7e22ce);
    }

    .content-card {
        background: #fff;
        border-radius: 16px;
        padding: 22px;
        margin-top: 22px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .05);
        border: 1px solid #e2e8f0;
    }

    .content-card h5 {
        font-size: 18px;
        font-weight: 650;
    }

    .school-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .school-item:last-child {
        border-bottom: none;
    }

    .avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #2563eb;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .quick-btn {
        width: 100%;
        margin-bottom: 10px;
        border-radius: 12px;
        padding: 10px;
        font-weight: 600;
    }

    .overview-box {
        text-align: center;
        padding: 16px;
        border-radius: 14px;
        background: #f8fafc;
    }

    .overview-box h3 {
        color: #2563eb;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .chart-box {
        height: 260px;
    }

    @media (max-width: 1199px) {
        .welcome-banner h2 {
            font-size: 24px;
        }

        .chart-box {
            height: 240px;
        }
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
<div class="row g-3">
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
<div class="row mt-4">

    <div class="col-lg-6">
       <div class="content-card">
    <h5 class="mb-3">Schools Growth Analytics</h5>

    <div class="chart-box">
        <canvas id="schoolsChart"></canvas>
    </div>
</div>
    </div>
    <div class="col-lg-6">
    <div class="content-card">
    <h5 class="mb-3">Platform Distribution</h5>
    <div class="chart-box">
        <canvas id="rolesChart"></canvas>
    </div>
    </div>
</div>

</div>
<!-- Second Row -->
<div class="row mt-4">
    <!-- Recent Schools -->
    <div class="col-lg-6">
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
     <div class="col-lg-6">
    <div class="content-card">
        <h5 class="mb-4">
            Quick Actions
        </h5>
        <div class="d-grid gap-3">

            <a href="{{ route('schools.create') }}"
                class="btn btn-primary">

                <i class="bi bi-building-add"></i>
                Add New School

            </a>

            <a href="{{ route('users.create') }}"
                class="btn btn-success">

                <i class="bi bi-person-plus"></i>
                Create User

            </a>

            <a href="{{ route('roles.create') }}"
                class="btn btn-warning text-white">

                <i class="bi bi-shield-plus"></i>
                Create Role

            </a>

        </div>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const schoolCanvas = document.getElementById('schoolsChart');

    if (schoolCanvas) {

        new Chart(schoolCanvas, {

            type: 'line',

            data: {

                labels: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun'
                ],

                datasets: [{

                    label: 'Schools',

                    data: [
                        2,
                        4,
                        5,
                        7,
                        10,
                        {{ $totalSchools ?? 0 }}
                    ],

                    borderColor: '#2563eb',

                    backgroundColor: 'rgba(37,99,235,0.15)',

                    borderWidth: 3,

                    tension: .4,

                    fill: true

                }]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    }
                },

                scales: {

                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    const roleCanvas = document.getElementById('rolesChart');
    if (roleCanvas) {
        new Chart(roleCanvas, {

            type: 'doughnut',

            data: {

                labels: [
                    'Schools',
                    'Users',
                    'Roles'
                ],

                datasets: [{

                    data: [
                        {{ $totalSchools ?? 0 }},
                        {{ $totalUsers ?? 0 }},
                        {{ $totalRoles ?? 0 }}
                    ],

                    backgroundColor: [
                        '#2563eb',
                        '#16a34a',
                        '#ea580c'
                    ],

                    borderWidth: 0

                }]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '70%',

                plugins: {

                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

});
</script>
@endsection

@extends('layouts.admin')

@section('title', 'Dashboard')

@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
@endphp

@section('page-title')
    Hey, {{ auth()->user()->name }} 👋
@endsection

@section('page-subtitle')
    {{ $greeting }} — here's what's happening on your platform · {{ now()->format('l, d M Y') }}
@endsection

@section('content')

<style>
    /* =========================STAT CARDS========================= */

    .stat-card {
        position: relative;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 20px;
        height: 100%;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        transition: box-shadow .2s ease, transform .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, .09);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--accent, #2563eb);
    }

    .stat-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .stat-label {
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--muted);
    }

    .stat-icon {
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: var(--radius);
        font-size: 19px;
        color: var(--accent, #2563eb);
        background: var(--accent-soft, #eff6ff);
    }

    .stat-value {
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -.02em;
        color: var(--ink);
        margin: 10px 0 2px;
        line-height: 1;
        font-variant-numeric: tabular-nums;
    }

    .stat-sub {
        font-size: 13px;
        color: var(--muted);
        font-weight: 500;
    }

    .stat-blue   { --accent: #2563eb; --accent-soft: #eff6ff; }
    .stat-green  { --accent: #16a34a; --accent-soft: #f0fdf4; }
    .stat-orange { --accent: #ea580c; --accent-soft: #fff7ed; }
    .stat-purple { --accent: #9333ea; --accent-soft: #faf5ff; }

    /* =========================CONTENT CARDS========================= */

    .content-card h5 {
        font-size: 16px;
        font-weight: 700;
        letter-spacing: -.01em;
        color: var(--ink);
    }

    .card-hint {
        font-size: 12.5px;
        color: var(--muted);
        font-weight: 500;
        margin-top: 2px;
    }

    .chart-box {
        height: 260px;
    }

    /* =========================RECENT SCHOOLS========================= */

    .school-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 10px;
        border-radius: var(--radius);
        border-bottom: 1px solid #f1f5f9;
        transition: background .15s ease;
    }

    .school-item:hover {
        background: #f8fafc;
    }

    .school-item:last-child {
        border-bottom: none;
    }

    .school-item strong {
        font-size: 14px;
        font-weight: 600;
        color: var(--ink);
    }

    .school-item small {
        font-size: 12.5px;
    }

    .avatar {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: var(--radius);
        background: #eff6ff;
        color: var(--primary);
        border: 1px solid #dbeafe;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
    }

    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        font-size: 13px;
        font-weight: 600;
        color: var(--primary);
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: var(--radius);
        text-decoration: none;
        transition: background .18s ease, color .18s ease;
    }

    .btn-view-all:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    /* =========================QUICK ACTIONS========================= */

    .quick-action {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        text-decoration: none;
        transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
    }

    .quick-action:hover {
        border-color: var(--qa, #2563eb);
        box-shadow: 0 8px 20px rgba(15, 23, 42, .08);
        transform: translateY(-2px);
    }

    .quick-action-icon {
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: var(--radius);
        font-size: 18px;
        color: var(--qa, #2563eb);
        background: var(--qa-soft, #eff6ff);
    }

    .quick-action strong {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--ink);
    }

    .quick-action small {
        font-size: 12.5px;
        color: var(--muted);
    }

    .quick-action .bi-arrow-right {
        margin-left: auto;
        color: #cbd5e1;
        font-size: 16px;
        transition: color .18s ease, transform .18s ease;
    }

    .quick-action:hover .bi-arrow-right {
        color: var(--qa, #2563eb);
        transform: translateX(3px);
    }

    .qa-blue   { --qa: #2563eb; --qa-soft: #eff6ff; }
    .qa-green  { --qa: #16a34a; --qa-soft: #f0fdf4; }
    .qa-orange { --qa: #ea580c; --qa-soft: #fff7ed; }

    @media (max-width: 1199px) {
        .chart-box {
            height: 240px;
        }
    }
</style>

<!-- Statistics -->
<div class="row g-3">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-blue">
            <div class="stat-head">
                <span class="stat-label">Total Schools</span>
                <div class="stat-icon"><i class="bi bi-buildings"></i></div>
            </div>
            <div class="stat-value">{{ $totalSchools ?? 0 }}</div>
            <div class="stat-sub">Registered Schools</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-green">
            <div class="stat-head">
                <span class="stat-label">Total Users</span>
                <div class="stat-icon"><i class="bi bi-people"></i></div>
            </div>
            <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
            <div class="stat-sub">System Users</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-orange">
            <div class="stat-head">
                <span class="stat-label">Total Roles</span>
                <div class="stat-icon"><i class="bi bi-shield-lock"></i></div>
            </div>
            <div class="stat-value">{{ $totalRoles ?? 0 }}</div>
            <div class="stat-sub">Available Roles</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-purple">
            <div class="stat-head">
                <span class="stat-label">Active Schools</span>
                <div class="stat-icon"><i class="bi bi-patch-check"></i></div>
            </div>
            <div class="stat-value">{{ $activeSchools ?? 0 }}</div>
            <div class="stat-sub">Currently Active</div>
        </div>
    </div>
</div>

<!-- Recent Schools + Quick Actions -->
<div class="row">
    <!-- Recent Schools -->
    <div class="col-lg-6">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Recent Schools</h5>
                <a href="{{ route('schools.index') }}" class="btn-view-all">
                    View All
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            @forelse($recentSchools as $school)
            <div class="school-item">
                <div class="avatar">
                    {{ strtoupper(substr($school->name,0,1)) }}
                </div>
                <div>
                    <strong>{{ $school->name }}</strong>
                    <br>
                    <small class="text-muted">{{ $school->email }}</small>
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
            <h5 class="mb-0">Quick Actions</h5>
            <div class="card-hint mb-3">Frequently used shortcuts</div>
            <div class="d-grid gap-3">

                <a href="{{ route('schools.create') }}" class="quick-action qa-blue">
                    <div class="quick-action-icon"><i class="bi bi-building-add"></i></div>
                    <div>
                        <strong>Add New School</strong>
                        <small>Onboard a new school to the platform</small>
                    </div>
                    <i class="bi bi-arrow-right"></i>
                </a>

                <a href="{{ route('users.create') }}" class="quick-action qa-green">
                    <div class="quick-action-icon"><i class="bi bi-person-plus"></i></div>
                    <div>
                        <strong>Create User</strong>
                        <small>Add a new user account with a role</small>
                    </div>
                    <i class="bi bi-arrow-right"></i>
                </a>

                <a href="{{ route('roles.create') }}" class="quick-action qa-orange">
                    <div class="quick-action-icon"><i class="bi bi-shield-plus"></i></div>
                    <div>
                        <strong>Create Role</strong>
                        <small>Define a new role and its permissions</small>
                    </div>
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>
        </div>
    </div>
</div>

<!-- Recent Users -->
<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Recent Users</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
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
                    <td class="fw-semibold">{{ $user->name }}</td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-primary">
                            {{ $user->role->name ?? 'N/A' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        No Users Found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Analytics Charts -->
<div class="row">
    <div class="col-lg-7">
        <div class="content-card">
            <h5 class="mb-0">Schools Growth Analytics</h5>
            <div class="card-hint mb-3">Onboarded schools over the last 6 months</div>
            <div class="chart-box">
                <canvas id="schoolsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="content-card">
            <h5 class="mb-0">Platform Distribution</h5>
            <div class="card-hint mb-3">Schools, users and roles across the platform</div>
            <div class="chart-box">
                <canvas id="rolesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    Chart.defaults.color = '#64748b';

    const schoolCanvas = document.getElementById('schoolsChart');

    if (schoolCanvas) {
        new Chart(schoolCanvas, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Schools',
                    data: [2, 4, 5, 7, 10, {{ $totalSchools ?? 0 }}],
                    borderColor: '#2563eb',
                    backgroundColor: (context) => {
                        const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 260);
                        gradient.addColorStop(0, 'rgba(37, 99, 235, .18)');
                        gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');
                        return gradient;
                    },
                    borderWidth: 2.5,
                    tension: .4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointHoverRadius: 5
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
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9'
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            font: { size: 12 }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            font: { size: 12 }
                        }
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
                labels: ['Schools', 'Users', 'Roles'],
                datasets: [{
                    data: [
                        {{ $totalSchools ?? 0 }},
                        {{ $totalUsers ?? 0 }},
                        {{ $totalRoles ?? 0 }}
                    ],
                    backgroundColor: ['#2563eb', '#16a34a', '#ea580c'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 18,
                            font: { size: 12.5 }
                        }
                    }
                }
            }
        });
    }

});
</script>
@endsection

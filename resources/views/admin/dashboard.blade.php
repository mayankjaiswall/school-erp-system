@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Super Admin Dashboard')

@section('content')

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
            <h2>{{ $totalUsers ?? 1 }}</h2>
            <small>System Users</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="dashboard-card card-orange">
            <h6>Roles</h6>
            <h2>{{ $totalRoles ?? 7 }}</h2>
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

<div class="content-card">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            Recent Schools
        </h5>

        <a href="#" class="btn btn-primary btn-sm">
            Add School
        </a>
    </div>

    <table class="table table-hover align-middle">

        <thead>
            <tr>
                <th>#</th>
                <th>School Name</th>
                <th>Code</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td colspan="5" class="text-center text-muted">
                    No schools found.
                </td>
            </tr>

        </tbody>

    </table>

</div>

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

            <tr>
                <td>1</td>
                <td>Super Admin</td>
                <td>admin@eduerp.com</td>
                <td>
                    <span class="badge bg-primary">
                        Super Admin
                    </span>
                </td>
            </tr>

        </tbody>

    </table>

</div>

@endsection
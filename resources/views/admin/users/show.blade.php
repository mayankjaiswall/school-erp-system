@extends('layouts.admin')

@section('title', 'User Details')

@section('page-title', 'User Details')

@section('content')

<style>
    .user-profile-card{
        background: linear-gradient(135deg,#2563eb,#1d4ed8);
        color:#fff;
        padding:30px;
        border-radius:20px;
        margin-bottom:25px;
        box-shadow:0 15px 35px rgba(37,99,235,.25);
    }

    .user-name{
        margin:0;
        font-size:32px;
        font-weight:700;
    }

    .user-subtitle{
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

<!-- User Header -->

<div class="user-profile-card">

    <div class="d-flex justify-content-between align-items-center">

        <div>

            <h2 class="user-name">
                {{ $user->name }}
            </h2>

            <p class="user-subtitle">
                User Management Information & Details
            </p>

        </div>

        <div>

            @if($user->status)
                <span class="status-pill active">
                    Active User
                </span>
            @else
                <span class="status-pill inactive">
                    Inactive User
                </span>
            @endif

        </div>

    </div>

</div>

<!-- Quick Stats -->

<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ $user->id }}</h3>
            <span>User ID</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ $user->email }}</h3>
            <span>Email</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card">
            <h3>
                <i class="bi bi-envelope-fill"></i>
            </h3>
            <span>Email Registered</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stats-card">
            <h3>
                <i class="bi bi-telephone-fill"></i>
            </h3>
            <span>Phone Available</span>
        </div>
    </div>

</div>

<!-- Information Cards -->

<div class="row g-4">

    <div class="col-md-4">

        <div class="info-card">

            <small>Full Name</small>

            <h5>
                {{ $user->name }}
            </h5>

        </div>

    </div>

    <div class="col-md-4">

        <div class="info-card">

            <small>Email Address</small>

            <h5>
                {{ $user->email }}
            </h5>

        </div>

    </div>

    <div class="col-md-4">

        <div class="info-card">

            <small>Status</small>

            <h5>
                {{ $user->status ? 'Active' : 'Inactive' }}
            </h5>

        </div>

    </div>

    <div class="col-md-6">

        <div class="info-card">

            <small>Email Address</small>

            <h5>
                {{ $user->email ?? 'N/A' }}
            </h5>

        </div>

    </div>

    <div class="col-md-6">

        <div class="info-card">

            <small>Phone Number</small>

            <h5>
                {{ $user->phone ?? 'N/A' }}
            </h5>

        </div>

    </div>

</div>

<!-- Address Section -->

<div class="address-card">

    <h5>
        <i class="bi bi-geo-alt-fill text-primary"></i>
        Address Information
    </h5>

    <p>
        {{ $user->address ?? 'Address not available.' }}
    </p>

</div>

<!-- System Information -->

<div class="row g-4 mt-2">

    <div class="col-md-6">

        <div class="info-card">

            <small>Created At</small>

            <h5>
                {{ $user->created_at->format('d M Y, h:i A') }}
            </h5>

        </div>

    </div>

    <div class="col-md-6">

        <div class="info-card();">

            <small>Last Updated</small>

            <h5>
                {{ $user->updated_at->format('d M Y, h:i A') }}
            </h5>

        </div>

    </div>

</div>

<!-- Action Buttons -->

<div class="action-buttons">

    <a href="{{ route('users.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i>
        Back
    </a>

    <a href="{{ route('users.edit', $user->id) }}" class="btn-edit">
        <i class="bi bi-pencil-square"></i>
        Edit User
    </a>

</div>

@endsection
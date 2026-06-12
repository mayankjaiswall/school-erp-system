@extends('layouts.principal')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
<style>
    .profile-header {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        padding: 28px;
        border-radius: 18px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 15px 35px rgba(37, 99, 235, .22);
    }

    .profile-header h2 {
        margin: 0;
        font-weight: 700;
    }

    .info-card {
        height: 100%;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .05);
    }

    .info-card small {
        display: block;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .info-card h5 {
        color: #0f172a;
        margin: 0;
        font-weight: 700;
    }

    .status-pill {
        border-radius: 999px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 700;
    }

    .status-pill.active { background: #22c55e; color: #fff; }
    .status-pill.inactive { background: #ef4444; color: #fff; }

    .action-buttons {
        margin-top: 24px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
</style>

<div class="profile-header">
    <div>
        <h2>{{ $user->name }}</h2>
        <p class="mb-0 opacity-75">{{ $user->role->name ?? 'User' }}</p>
    </div>

    <span class="status-pill {{ $user->status ? 'active' : 'inactive' }}">
        {{ $user->status ? 'Active' : 'Inactive' }}
    </span>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="info-card">
            <small>Name</small>
            <h5>{{ $user->name }}</h5>
        </div>
    </div>

    <div class="col-md-4">
        <div class="info-card">
            <small>Role</small>
            <h5>{{ $user->role->name ?? 'N/A' }}</h5>
        </div>
    </div>

    <div class="col-md-4">
        <div class="info-card">
            <small>School</small>
            <h5>{{ $user->school->name ?? 'N/A' }}</h5>
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-card">
            <small>Email</small>
            <h5>{{ $user->email }}</h5>
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-card">
            <small>Phone</small>
            <h5>{{ $user->phone ?? 'N/A' }}</h5>
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-card">
            <small>Created At</small>
            <h5>{{ $user->created_at->format('d M Y, h:i A') }}</h5>
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-card">
            <small>Last Updated</small>
            <h5>{{ $user->updated_at->format('d M Y, h:i A') }}</h5>
        </div>
    </div>
</div>

<div class="action-buttons">
    <a href="{{ route('principal.users.index') }}" class="btn btn-light border">
        <i class="bi bi-arrow-left"></i>
        Back
    </a>

    @if($user->role && in_array($user->role->slug, ['admin', 'hod', 'teacher', 'parent', 'student']))
        <a href="{{ route('principal.users.edit', $user->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i>
            Edit User
        </a>
    @endif
</div>
@endsection

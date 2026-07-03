@extends($layout)

@section('title', 'View Profile')
@section('page-title', 'View Profile')

@section('content')
@php
    $avatarFallback = 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=2563eb&color=fff';
    $avatarUrl = $user->photo ? asset('storage/' . $user->photo) : $avatarFallback;
@endphp

<style>
    .account-header{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;box-shadow:0 15px 35px rgba(37,99,235,.25)}
    .account-card{background:#fff;border:1px solid #e2e8f0;border-radius:20px;box-shadow:0 8px 20px rgba(15,23,42,.05);padding:28px}
    .profile-avatar{width:96px;height:96px;min-width:96px;border-radius:50%;object-fit:cover;border:4px solid #dbeafe;background:#eff6ff;display:block;font-size:0}
    .form-label{color:#334155;font-weight:700;margin-bottom:8px}
    .form-control{border:1px solid #dbe2ea;border-radius:12px;min-height:48px}
    .form-control:focus{border-color:#2563eb;box-shadow:0 0 0 .2rem rgba(37,99,235,.12)}
    .locked-field{background:#f8fafc;color:#64748b}
    .profile-meta{background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:18px;height:100%}
    .profile-meta small{color:#64748b;display:block;font-weight:700;margin-bottom:6px;text-transform:uppercase}
    .profile-meta strong{color:#0f172a}
</style>

<div class="account-header">
    <h2 class="mb-2">View Profile</h2>
    <p class="mb-0 opacity-75">Update your account details. Email address cannot be changed from here.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="account-card">
    <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="d-flex flex-wrap align-items-center gap-4 mb-4">
            <img class="profile-avatar" src="{{ $avatarUrl }}" alt="{{ $user->name }}" onerror="this.onerror=null;this.src='{{ $avatarFallback }}';">
            <div>
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-0">{{ $user->role?->name ?? 'No Role Assigned' }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" value="{{ $user->email }}" class="form-control locked-field" readonly>
            </div>
            <div class="col-md-6 mb-4">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10)">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-4">
                <label for="photo" class="form-label">Profile Photo</label>
                <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="profile-meta">
                    <small>Role</small>
                    <strong>{{ $user->role?->name ?? 'Not Assigned' }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="profile-meta">
                    <small>School</small>
                    <strong>{{ $user->school?->name ?? 'Not Assigned' }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="profile-meta">
                    <small>Status</small>
                    <strong>{{ $user->status ? 'Active' : 'Inactive' }}</strong>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-circle"></i>
                Update Profile
            </button>
        </div>
    </form>
</div>
@endsection

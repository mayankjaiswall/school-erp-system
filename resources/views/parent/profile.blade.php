@extends('layouts.parent')

@section('title', 'Parent Profile')
@section('page-title', 'Profile')

@section('content')
<style>.profile-header{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;box-shadow:0 15px 35px rgba(37,99,235,.25)}.profile-photo{width:92px;height:92px;border-radius:50%;object-fit:cover;border:4px solid rgba(255,255,255,.55)}.info-card{background:#fff;padding:24px;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 8px 20px rgba(15,23,42,.05);height:100%}.info-card small{display:block;color:#64748b;font-weight:600;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px}.info-card h5{margin:0;color:#0f172a;font-weight:700}</style>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif
<div class="profile-header">
    <div class="d-flex flex-wrap gap-3 align-items-center">
        <img class="profile-photo" src="{{ $parent->user?->photo ? asset('storage/'.$parent->user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($parent->user?->name ?? 'Parent').'&background=2563eb&color=fff' }}" alt="{{ $parent->user?->name }}">
        <div>
            <h2 class="mb-1">{{ $parent->user?->name }}</h2>
            <p class="mb-0">{{ $parent->phone }}{{ $parent->email ? ' | '.$parent->email : '' }}</p>
        </div>
    </div>
</div>
<div class="row g-4">
    <div class="col-md-4"><div class="info-card"><small>Father Name</small><h5>{{ $parent->father_name ?? '-' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Mother Name</small><h5>{{ $parent->mother_name ?? '-' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Phone</small><h5>{{ $parent->phone }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Alternate Phone</small><h5>{{ $parent->alternate_phone ?? '-' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Email</small><h5>{{ $parent->email ?? '-' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Occupation</small><h5>{{ $parent->occupation ?? '-' }}</h5></div></div>
</div>
<div class="content-card">
    <h5 class="mb-3">Update Profile</h5>
    <form method="POST" action="{{ route('parent.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $parent->phone) }}" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $parent->email ?: $parent->user?->email) }}" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Profile Picture</label><input type="file" name="photo" class="form-control" accept="image/*"></div>
            <div class="col-12"><label class="form-label fw-semibold">Address</label><textarea name="address" class="form-control" rows="3">{{ old('address', $parent->address) }}</textarea></div>
            <div class="col-12"><button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Profile</button></div>
        </div>
    </form>
</div>
<div class="content-card">
    <h5 class="mb-3">Change Password</h5>
    <form method="POST" action="{{ route('parent.profile.password') }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Current Password</label><input type="password" name="current_password" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">New Password</label><input type="password" name="password" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Confirm Password</label><input type="password" name="password_confirmation" class="form-control" required></div>
            <div class="col-12"><button type="submit" class="btn btn-outline-primary"><i class="bi bi-key"></i> Change Password</button></div>
        </div>
    </form>
</div>
<div class="content-card">
    <h5 class="mb-3">Linked Children</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Student</th><th>Admission No</th><th>Class</th><th>Relationship</th></tr></thead>
            <tbody>
                @forelse($parent->students as $student)
                    <tr><td>{{ $student->name }}</td><td>{{ $student->admission_no }}</td><td>{{ $student->class?->name }}{{ $student->class?->section ? ' - '.$student->class->section : '' }}</td><td><span class="badge bg-success">{{ $student->pivot->relationship }}</span></td></tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No linked children found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="content-card">
    <h5 class="mb-2">Address</h5>
    <p class="mb-0 text-muted">{{ $parent->address ?? 'Address not available.' }}</p>
</div>
@endsection

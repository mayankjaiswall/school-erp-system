@extends('layouts.principal')

@section('title', 'Parent Profile')
@section('page-title', 'Parent Profile')

@section('content')
<style>.profile-header{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;box-shadow:0 15px 35px rgba(37,99,235,.25)}.info-card{background:#fff;padding:24px;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 8px 20px rgba(15,23,42,.05);height:100%}.info-card small{display:block;color:#64748b;font-weight:600;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px}.info-card h5{margin:0;color:#0f172a;font-weight:700}</style>
<div class="profile-header">
    <div class="d-flex justify-content-between align-items-center">
        <div><h2 class="mb-1">{{ $parent->user?->name }}</h2><p class="mb-0">{{ $parent->phone }}{{ $parent->email ? ' | '.$parent->email : '' }}</p></div>
        <a href="{{ route('principal.parents.edit', $parent->id) }}" class="btn btn-light"><i class="bi bi-pencil-square"></i> Edit</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-md-4"><div class="info-card"><small>Father Name</small><h5>{{ $parent->father_name ?? '-' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Mother Name</small><h5>{{ $parent->mother_name ?? '-' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Status</small><h5>{{ $parent->status ? 'Active' : 'Inactive' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Alternate Phone</small><h5>{{ $parent->alternate_phone ?? '-' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Email</small><h5>{{ $parent->email ?? '-' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Occupation</small><h5>{{ $parent->occupation ?? '-' }}</h5></div></div>
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
                    <tr><td colspan="4" class="text-center text-muted">No linked children.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="content-card"><h5 class="mb-2">Address</h5><p class="mb-0 text-muted">{{ $parent->address ?? 'Address not available.' }}</p></div>
@endsection

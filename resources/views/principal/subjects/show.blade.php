@extends('layouts.principal')

@section('title', 'Subject Details')
@section('page-title', 'Subject Details')

@section('content')
<style>
    .subject-profile-card{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;box-shadow:0 15px 35px rgba(37,99,235,.25)}
    .subject-name{margin:0;font-size:32px;font-weight:700}
    .subject-subtitle{margin-top:6px;opacity:.9}
    .status-pill{padding:10px 20px;border-radius:50px;font-size:14px;font-weight:600}
    .status-pill.active{background:#22c55e;color:#fff}.status-pill.inactive{background:#ef4444;color:#fff}
    .info-card,.description-card{background:#fff;padding:25px;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 8px 20px rgba(15,23,42,.05)}
    .info-card{height:100%}.info-card small{display:block;color:#64748b;font-weight:600;margin-bottom:10px;text-transform:uppercase;letter-spacing:.5px}
    .info-card h5{margin:0;color:#0f172a;font-weight:700}.description-card{margin-top:25px}.description-card p{margin:0;color:#64748b;line-height:1.8}
    .action-buttons{margin-top:25px;display:flex;justify-content:flex-end;gap:12px}
    .btn-back,.btn-edit{padding:10px 20px;border-radius:10px;text-decoration:none;font-weight:600}
    .btn-back{border:1px solid #d1d5db;background:#fff;color:#374151}.btn-edit{background:#2563eb;color:#fff}.btn-edit:hover{color:#fff;background:#1d4ed8}
</style>

<div class="subject-profile-card">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="subject-name">{{ $subject->name }}</h2>
            <p class="subject-subtitle">{{ $subject->class?->name }}{{ $subject->class?->section ? ' - '.$subject->class->section : '' }} | Code: {{ $subject->code }}</p>
        </div>
        <span class="status-pill {{ $subject->status ? 'active' : 'inactive' }}">{{ $subject->status ? 'Active Subject' : 'Inactive Subject' }}</span>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4"><div class="info-card"><small>Subject Code</small><h5>{{ $subject->code }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Class</small><h5>{{ $subject->class?->name }}{{ $subject->class?->section ? ' - '.$subject->class->section : '' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Status</small><h5>{{ $subject->status ? 'Active' : 'Inactive' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>School</small><h5>{{ $subject->school?->name ?? 'N/A' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Created At</small><h5>{{ $subject->created_at->format('d M Y, h:i A') }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Updated At</small><h5>{{ $subject->updated_at->format('d M Y, h:i A') }}</h5></div></div>
</div>

<div class="description-card">
    <h5><i class="bi bi-card-text text-primary"></i> Description</h5>
    <p>{{ $subject->description ?? 'Description not available.' }}</p>
</div>

<div class="action-buttons">
    <a href="{{ route('subjects.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Back</a>
    <a href="{{ route('subjects.edit', $subject->id) }}" class="btn-edit"><i class="bi bi-pencil-square"></i> Edit Subject</a>
</div>
@endsection

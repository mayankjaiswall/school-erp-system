@extends('layouts.principal')

@section('title', 'Teacher Subject Details')
@section('page-title', 'Teacher Subject Details')

@section('content')
<style>
    .assignment-profile-card{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;box-shadow:0 15px 35px rgba(37,99,235,.25)}
    .assignment-name{margin:0;font-size:32px;font-weight:700}
    .assignment-subtitle{margin-top:6px;opacity:.9}
    .info-card{background:#fff;padding:25px;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 8px 20px rgba(15,23,42,.05);height:100%}
    .info-card small{display:block;color:#64748b;font-weight:600;margin-bottom:10px;text-transform:uppercase;letter-spacing:.5px}
    .info-card h5{margin:0;color:#0f172a;font-weight:700}
    .flow-card{background:#fff;padding:25px;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 8px 20px rgba(15,23,42,.05);margin-bottom:25px}
    .flow-line{display:grid;grid-template-columns:1fr auto 1fr auto 1fr;gap:15px;align-items:center;text-align:center}
    .flow-node{background:#f8fafc;border-radius:14px;padding:18px}.flow-node i{font-size:28px;color:#2563eb}.flow-node strong{display:block;margin-top:8px;color:#0f172a}
    .flow-arrow{color:#94a3b8;font-size:24px}
    .action-buttons{margin-top:25px;display:flex;justify-content:flex-end;gap:12px}
    .btn-back,.btn-edit{padding:10px 20px;border-radius:10px;text-decoration:none;font-weight:600}
    .btn-back{border:1px solid #d1d5db;background:#fff;color:#374151}.btn-edit{background:#2563eb;color:#fff}.btn-edit:hover{color:#fff;background:#1d4ed8}
    @media(max-width:768px){.flow-line{grid-template-columns:1fr}.flow-arrow{display:none}}
</style>

<div class="assignment-profile-card">
    <div>
        <h2 class="assignment-name">{{ $assignment->teacher?->name ?? 'Teacher Assignment' }}</h2>
        <p class="assignment-subtitle">{{ $assignment->subject?->name ?? 'N/A' }} | {{ $assignment->schoolClass?->name }}{{ $assignment->schoolClass?->section ? ' - '.$assignment->schoolClass->section : '' }}</p>
    </div>
</div>

<div class="flow-card">
    <div class="flow-line">
        <div class="flow-node"><i class="bi bi-person-badge"></i><strong>{{ $assignment->teacher?->name ?? 'N/A' }}</strong><small>Teacher</small></div>
        <div class="flow-arrow"><i class="bi bi-arrow-right"></i></div>
        <div class="flow-node"><i class="bi bi-book"></i><strong>{{ $assignment->subject?->name ?? 'N/A' }}</strong><small>{{ $assignment->subject?->code ?? 'Subject' }}</small></div>
        <div class="flow-arrow"><i class="bi bi-arrow-right"></i></div>
        <div class="flow-node"><i class="bi bi-journal-bookmark"></i><strong>{{ $assignment->schoolClass?->name }}{{ $assignment->schoolClass?->section ? ' - '.$assignment->schoolClass->section : '' }}</strong><small>Class</small></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4"><div class="info-card"><small>Teacher Email</small><h5>{{ $assignment->teacher?->email ?? 'N/A' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>Subject Code</small><h5>{{ $assignment->subject?->code ?? 'N/A' }}</h5></div></div>
    <div class="col-md-4"><div class="info-card"><small>School</small><h5>{{ $assignment->school?->name ?? 'N/A' }}</h5></div></div>
    <div class="col-md-6"><div class="info-card"><small>Created At</small><h5>{{ $assignment->created_at->format('d M Y, h:i A') }}</h5></div></div>
    <div class="col-md-6"><div class="info-card"><small>Updated At</small><h5>{{ $assignment->updated_at->format('d M Y, h:i A') }}</h5></div></div>
</div>

<div class="action-buttons">
    <a href="{{ route('teacher-subjects.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Back</a>
    <a href="{{ route('teacher-subjects.edit', $assignment->id) }}" class="btn-edit"><i class="bi bi-pencil-square"></i> Edit Assignment</a>
</div>
@endsection

@extends('layouts.parent')

@section('title', 'My Children')
@section('page-title', 'My Children')

@section('content')
<style>.child-card{background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:24px;box-shadow:0 8px 20px rgba(15,23,42,.05)}.child-photo{width:92px;height:92px;border-radius:50%;object-fit:cover;border:4px solid #dbeafe}</style>
<div class="row g-4">
    @forelse($children as $child)
        <div class="col-lg-6">
            <div class="child-card">
                <div class="d-flex gap-3 align-items-center mb-3">
                    <img class="child-photo" src="{{ $child->photo ? asset($child->photo) : 'https://ui-avatars.com/api/?name='.urlencode($child->name).'&background=2563eb&color=fff' }}" alt="{{ $child->name }}">
                    <div>
                        <h4 class="mb-1">{{ $child->name }}</h4>
                        <span class="badge bg-success">{{ $child->pivot->relationship }}</span>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6"><small class="text-muted">Admission Number</small><div class="fw-semibold">{{ $child->admission_no }}</div></div>
                    <div class="col-md-6"><small class="text-muted">Class</small><div class="fw-semibold">{{ $child->class?->name ?? '-' }}</div></div>
                    <div class="col-md-6"><small class="text-muted">Section</small><div class="fw-semibold">{{ $child->class?->section ?? '-' }}</div></div>
                    <div class="col-md-6"><small class="text-muted">Roll Number</small><div class="fw-semibold">{{ $child->roll_no ?? '-' }}</div></div>
                    <div class="col-md-6"><small class="text-muted">Date of Birth</small><div class="fw-semibold">{{ $child->dob?->format('d M Y') ?? '-' }}</div></div>
                    <div class="col-md-6"><small class="text-muted">Academic Status</small><div class="fw-semibold">{{ $child->status ? 'Active' : 'Inactive' }}</div></div>
                </div>
            </div>
        </div>
    @empty
        <div class="content-card text-center text-muted">No linked children found.</div>
    @endforelse
</div>
@endsection

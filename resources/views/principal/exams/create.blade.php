@extends('layouts.principal')

@section('title', $exam ? 'Edit Exam' : 'Create Exam')
@section('page-title', $exam ? 'Edit Exam' : 'Create Exam')

@section('content')
<style>
    .form-page-header{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;box-shadow:0 15px 35px rgba(37,99,235,.25)}
    .form-page-header h2{font-weight:700}
    .form-card{background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:28px;box-shadow:0 8px 20px rgba(15,23,42,.05)}
    .form-label{font-weight:600;color:#334155}
    .action-footer{border-top:1px solid #e2e8f0;margin-top:25px;padding-top:22px}
</style>

<div class="form-page-header">
    <h2 class="mb-2">{{ $exam ? 'Edit Exam' : 'Create New Exam' }}</h2>
    <p class="mb-0 opacity-75">Manage exam schedules for marks entry and reports.</p>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-card">
    <form action="{{ $exam ? route('principal.exams.update', $exam->id) : route('principal.exams.store') }}" method="POST">
        @csrf
        @if($exam)
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-md-6">
                <label for="name" class="form-label">Exam Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $exam?->name) }}" placeholder="Unit Test 1" required>
            </div>
            <div class="col-md-6">
                <label for="exam_type" class="form-label">Exam Type</label>
                <input type="text" id="exam_type" name="exam_type" class="form-control" value="{{ old('exam_type', $exam?->exam_type) }}" placeholder="Unit Test, Half Yearly, Annual" required>
            </div>
            <div class="col-md-4">
                <label for="exam_date" class="form-label">Exam Date</label>
                <input type="date" id="exam_date" name="exam_date" class="form-control" value="{{ old('exam_date', $exam?->exam_date?->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
                <label for="academic_year" class="form-label">Academic Year</label>
                <input type="text" id="academic_year" name="academic_year" class="form-control" value="{{ old('academic_year', $exam?->academic_year ?? date('Y') . '-' . (date('Y') + 1)) }}" placeholder="2026-2027" required>
            </div>
            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="1" @selected(old('status', $exam?->status ?? 1) == 1)>Active</option>
                    <option value="0" @selected(old('status', $exam?->status ?? 1) == 0)>Inactive</option>
                </select>
            </div>
        </div>

        <div class="action-footer d-flex justify-content-between">
            <a href="{{ route('principal.exams.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> {{ $exam ? 'Update Exam' : 'Save Exam' }}</button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.principal')

@section('title', 'Assign Teacher Subject')
@section('page-title', 'Assign Teacher Subject')

@section('content')
@include('principal.teacher-subjects.partials.form-styles')

<div class="form-page-header">
    <h2 class="mb-2">Assign Teacher to Subject</h2>
    <p class="mb-0 opacity-75">Connect teacher, class, and subject into one teaching responsibility.</p>
</div>

<div class="form-card">
    <form id="teacherSubjectForm" action="{{ route('teacher-subjects.store') }}" method="POST">
        @csrf
        @include('principal.teacher-subjects.partials.fields', ['assignment' => null])
        <div class="action-footer d-flex justify-content-between">
            <a href="{{ route('teacher-subjects.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Assign Teacher</button>
        </div>
    </form>
</div>

@include('principal.teacher-subjects.partials.ajax-form', [
    'formId' => 'teacherSubjectForm',
    'redirectUrl' => route('teacher-subjects.index'),
    'successTitle' => 'Assigned!'
])
@endsection

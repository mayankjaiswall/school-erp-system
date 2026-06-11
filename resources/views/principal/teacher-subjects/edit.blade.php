@extends('layouts.principal')

@section('title', 'Edit Teacher Subject')
@section('page-title', 'Edit Teacher Subject')

@section('content')
@include('principal.teacher-subjects.partials.form-styles')

<div class="form-page-header">
    <h2 class="mb-2">Edit Teacher Subject Assignment</h2>
    <p class="mb-0 opacity-75">Update the teacher, class, or subject mapping.</p>
</div>

<div class="form-card">
    <form id="teacherSubjectEditForm" action="{{ route('teacher-subjects.update', $assignment->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('principal.teacher-subjects.partials.fields', ['assignment' => $assignment])
        <div class="action-footer d-flex justify-content-between">
            <a href="{{ route('teacher-subjects.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Assignment</button>
        </div>
    </form>
</div>

@include('principal.teacher-subjects.partials.ajax-form', [
    'formId' => 'teacherSubjectEditForm',
    'redirectUrl' => route('teacher-subjects.index'),
    'successTitle' => 'Updated!'
])
@endsection

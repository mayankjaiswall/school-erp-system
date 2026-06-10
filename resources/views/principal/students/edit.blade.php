@extends('layouts.principal')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student')

@section('content')
@include('principal.students.partials.form-styles')

<div class="form-page-header">
    <h2 class="mb-2">Edit Student</h2>
    <p class="mb-0 opacity-75">Update student information.</p>
</div>

<div class="form-card">
    <form id="studentEditForm" action="{{ route('students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('principal.students.partials.fields', ['student' => $student])
        <div class="action-footer d-flex justify-content-between">
            <a href="{{ route('students.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Student</button>
        </div>
    </form>
</div>

@include('principal.students.partials.ajax-form', [
    'formId' => 'studentEditForm',
    'redirectUrl' => route('students.index'),
    'successTitle' => 'Updated!'
])
@endsection

@extends('layouts.principal')

@section('title', 'Create Student')
@section('page-title', 'Create Student')

@section('content')
@include('principal.students.partials.form-styles')

<div class="form-page-header">
    <h2 class="mb-2">Create New Student</h2>
    <p class="mb-0 opacity-75">Enroll a new student in your school.</p>
</div>

<div class="form-card">
    <form id="studentForm" action="{{ route('students.store') }}" method="POST">
        @csrf
        @include('principal.students.partials.fields', ['student' => null])
        <div class="action-footer d-flex justify-content-between">
            <a href="{{ route('students.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Student</button>
        </div>
    </form>
</div>

@include('principal.students.partials.ajax-form', [
    'formId' => 'studentForm',
    'redirectUrl' => route('students.index'),
    'successTitle' => 'Success!'
])
@endsection

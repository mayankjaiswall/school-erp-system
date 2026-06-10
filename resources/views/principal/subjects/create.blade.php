@extends('layouts.principal')

@section('title', 'Create Subject')
@section('page-title', 'Create Subject')

@section('content')
@include('principal.subjects.partials.form-styles')

<div class="form-page-header">
    <h2 class="mb-2">Create New Subject</h2>
    <p class="mb-0 opacity-75">Add a subject to a class in your school.</p>
</div>

<div class="form-card">
    <form id="subjectForm" action="{{ route('subjects.store') }}" method="POST">
        @csrf
        @include('principal.subjects.partials.fields', ['subject' => null])
        <div class="action-footer d-flex justify-content-between">
            <a href="{{ route('subjects.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Subject</button>
        </div>
    </form>
</div>

@include('principal.subjects.partials.ajax-form', [
    'formId' => 'subjectForm',
    'redirectUrl' => route('subjects.index'),
    'successTitle' => 'Success!'
])
@endsection

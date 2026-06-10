@extends('layouts.principal')

@section('title', 'Edit Subject')
@section('page-title', 'Edit Subject')

@section('content')
@include('principal.subjects.partials.form-styles')

<div class="form-page-header">
    <h2 class="mb-2">Edit Subject</h2>
    <p class="mb-0 opacity-75">Update subject information.</p>
</div>

<div class="form-card">
    <form id="subjectEditForm" action="{{ route('subjects.update', $subject->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('principal.subjects.partials.fields', ['subject' => $subject])
        <div class="action-footer d-flex justify-content-between">
            <a href="{{ route('subjects.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Back</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Subject</button>
        </div>
    </form>
</div>

@include('principal.subjects.partials.ajax-form', [
    'formId' => 'subjectEditForm',
    'redirectUrl' => route('subjects.index'),
    'successTitle' => 'Updated!'
])
@endsection

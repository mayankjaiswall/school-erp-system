@extends('layouts.principal')

@section('title', 'Add Parent')
@section('page-title', 'Add Parent')

@section('content')
<div class="form-page-header">
    <h2 class="mb-2">Add Parent</h2>
    <p class="mb-0 opacity-75">Create a parent profile and link children in one place.</p>
</div>

<div class="content-card parent-form-card mt-0">
    <form id="parentForm" action="{{ route('principal.parents.store') }}" method="POST">
        @csrf
        @include('principal.parents.partials.fields')
        <div class="action-footer d-flex justify-content-end gap-2">
            <a href="{{ route('principal.parents.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Parent</button>
        </div>
    </form>
</div>
@include('principal.parents.partials.form-script')
@endsection

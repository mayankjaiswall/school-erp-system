@extends('layouts.principal')

@section('title', 'Edit Parent')
@section('page-title', 'Edit Parent')

@section('content')
<div class="form-page-header">
    <h2 class="mb-2">Edit Parent</h2>
    <p class="mb-0 opacity-75">Update parent information and manage linked children.</p>
</div>

<div class="content-card parent-form-card mt-0">
    <form id="parentForm" action="{{ route('principal.parents.update', $parent->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('principal.parents.partials.fields')
        <div class="action-footer d-flex justify-content-end gap-2">
            <a href="{{ route('principal.parents.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Parent</button>
        </div>
    </form>
</div>
@include('principal.parents.partials.form-script')
@endsection

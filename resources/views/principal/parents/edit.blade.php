@extends('layouts.principal')

@section('title', 'Edit Parent')
@section('page-title', 'Edit Parent')

@section('content')
<div class="content-card mt-0">
    <form id="parentForm" action="{{ route('principal.parents.update', $parent->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('principal.parents.partials.fields')
        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('principal.parents.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Parent</button>
        </div>
    </form>
</div>
@include('principal.parents.partials.form-script')
@endsection

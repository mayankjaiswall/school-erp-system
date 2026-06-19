@extends('layouts.principal')

@section('title', 'Add Parent')
@section('page-title', 'Add Parent')

@section('content')
<div class="content-card mt-0">
    <form id="parentForm" action="{{ route('principal.parents.store') }}" method="POST">
        @csrf
        @include('principal.parents.partials.fields')
        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('principal.parents.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Parent</button>
        </div>
    </form>
</div>
@include('principal.parents.partials.form-script')
@endsection

@extends('layouts.admin')

@section('title', 'Edit School')

@section('page-title', 'Edit School')

@section('content')

<div class="content-card">
    <h4 class="mb-4">Edit School</h4>
    <form action="{{ route('schools.update', $school->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>School Name</label>
                <input type="text" class="form-control" name="name" value="{{ $school->name }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>School Code</label>
                <input type="text" class="form-control" name="code" value="{{ $school->code }}" required class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" class="form-control" name="email" value="{{ $school->email }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Phone</label>
                <input type="text" class="form-control" name="phone" value="{{ $school->phone }}" required class="form-control">
            </div>

            <div class="col-12 mb-3">
                <label>Address</label>
                <textarea class="form-control" name="address" rows="3">{{ $school->address }}</textarea>
            </div>

            <div class="col-md-3 mb-3">
                <label>Status</label>
                <select class="form-select" name="status">
                    <option value="1" {{ $school->status ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$school->status ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('schools.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-save"></i> Update School
            </button>
        </div>

    </form>

</div>

@endsection
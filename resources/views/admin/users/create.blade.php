@extends('layouts.admin')
@section('title', 'Create User')
@section('page-title', 'Create User')
@section('content')
<style>
    .form-page-header{
        background: linear-gradient(135deg,#2563eb,#1d4ed8);
        color:#fff;
        padding:30px;
        border-radius:20px;
        margin-bottom:25px;
        box-shadow:0 15px 35px rgba(37,99,235,.25);
    }

    .form-card{
        background:#fff;
        border-radius:20px;
        padding:30px;
        border:1px solid #e2e8f0;
        box-shadow:0 8px 20px rgba(15,23,42,.05);
    }

    .form-label{
        font-weight:600;
        color:#334155;
        margin-bottom:8px;
    }

    .form-control,
    .form-select{
        border-radius:12px;
        min-height:48px;
        border:1px solid #dbe2ea;
    }

    .form-control:focus,
    .form-select:focus{
        box-shadow:none;
        border-color:#2563eb;
    }

    .action-footer{
        border-top:1px solid #e2e8f0;
        padding-top:20px;
        margin-top:30px;
    }
</style>
<div class="form-page-header">

    <h2 class="mb-2">
        Create New User
    </h2>

    <p class="mb-0 opacity-75">
        Add a new user to your ERP platform.
    </p>
</div>
<div class="form-card">
    <form action="{{ route('users.store') }}" method="POST" data-ajax-user-form>
        @csrf
        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="col-md-6 mb-4">
                <label for="school_id" class="form-label">School</label>
                <select name="school_id" id="school_id" class="form-select" required>
                    <option disabled selected value="">Select School</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}">
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-4">
                <label for="role" class="form-label">Role</label>
                <select name="role_id" id="role" class="form-select" required>
                    <option disabled selected value="">
                        Select Role
                    </option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-4">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control">
            </div>

            <div class="col-md-6 mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="col-md-6 mb-4">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>

            <div class="col-md-6 mb-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" accesskey="" id="status" class="form-select">
                    <option value="1"> Active</option>
                    <option value="0"> Inactive</option>
                </select>
            </div>
</div>
        <div class="action-footer text-end">
            <button type="submit" class="btn btn-primary">
                Create User
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-user-form.js') }}"></script>
@endpush

@extends('layouts.admin')

@section('title', 'Create Role')

@section('page-title', 'Add New Role')

@section('content')

<style>
.role-form-header{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;
    padding:30px;
    border-radius:20px;
    margin-bottom:25px;
    box-shadow:0 15px 35px rgba(37,99,235,.25);
}

.role-form-card{
    background:#fff;
    border-radius:20px;
    padding:30px;
    border:1px solid #e2e8f0;
    box-shadow:0 8px 20px rgba(15,23,42,.05);
}

.form-label{
    font-weight:600;
    color:#334155;
}

.form-control{
    border-radius:12px;
    padding:12px;
}
</style>

<div class="role-form-header">
    <h2 class="mb-1">Create Role</h2>
    <p class="mb-0 opacity-75">
        Create a new role for the ERP system.
    </p>
</div>

<div class="role-form-card">

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        <div class="row">

            <div class="col-md-6 mb-4">
                <label class="form-label">Role Name</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       placeholder="Enter role name"
                       required>
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label">Role Slug</label>
                <input type="text"
                       name="slug"
                       class="form-control"
                       placeholder="Enter role slug"
                       required>
            </div>

        </div>

        <div class="d-flex justify-content-between mt-4">

            <a href="{{ route('roles.index') }}"
               class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-save"></i>
                Save Role

            </button>

        </div>

    </form>

</div>

@endsection
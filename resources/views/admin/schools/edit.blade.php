@extends('layouts.admin')

@section('title', 'Edit School')

@section('page-title', 'Edit School')

@section('content')

<style>
    .form-page-header{
        background: linear-gradient(135deg,#4f0bf5,#d97706);
        color:#fff;
        padding:30px;
        border-radius:20px;
        margin-bottom:25px;
        box-shadow:0 15px 35px rgba(79,11,245,.25);
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
        border-color:#f59e0b;
    }

    .action-footer{
        border-top:1px solid #e2e8f0;
        padding-top:20px;
        margin-top:30px;
    }
</style>

<div class="form-page-header">

    <h2 class="mb-2">
        Edit School
    </h2>

    <p class="mb-0 opacity-75">
        Update school information and settings.
    </p>

</div>

<div class="form-card">

    <form action="{{ route('schools.update', $school->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="row g-4">

            <div class="col-md-6">

                <label class="form-label">
                    School Name
                </label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ $school->name }}"
                       required>

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    School Code
                </label>

                <input type="text"
                       name="code"
                       class="form-control"
                       value="{{ $school->code }}"
                       required>

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Email Address
                </label>

                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ $school->email }}">

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Phone Number
                </label>

                <input type="text"
                       name="phone"
                       class="form-control"
                       value="{{ $school->phone }}">

            </div>

            <div class="col-12">

                <label class="form-label">
                    Address
                </label>

                <textarea name="address"
                          rows="4"
                          class="form-control">{{ $school->address }}</textarea>

            </div>

            <div class="col-md-4">

                <label class="form-label">
                    Status
                </label>

                <select name="status"
                        class="form-select">

                    <option value="1"
                        {{ $school->status ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ !$school->status ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

            </div>

        </div>

        <div class="action-footer d-flex justify-content-between">

            <a href="{{ route('schools.index') }}"
               class="btn btn-light border">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

            <button type="submit"
                    class="btn btn-warning text-white">

                <i class="bi bi-check-circle"></i>
                Update School

            </button>

        </div>

    </form>

</div>

@endsection
@extends('layouts.admin')

@section('title', 'Create School')

@section('page-title', 'Create School')

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
        Create New School
    </h2>

    <p class="mb-0 opacity-75">
        Add a new school to your ERP platform.
    </p>

</div>

<div class="form-card">

    <form action="{{ route('schools.store') }}" method="POST">

        @csrf

        <div class="row g-4">

            <div class="col-md-6">

                <label class="form-label">
                    School Name
                </label>

                <input type="text"
                       name="name"
                       class="form-control"
                       placeholder="Enter school name"
                       required>

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    School Code
                </label>

                <input type="text"
                       name="code"
                       class="form-control"
                       placeholder="Enter school code"
                       required>

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Email Address
                </label>

                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="school@example.com">

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Phone Number
                </label>

                <input type="text"
                       name="phone"
                       class="form-control"
                       inputmode="numeric"
                       maxlength="10"
                       pattern="[0-9]{10}"
                       placeholder="10 digit phone number"
                       title="Enter exactly 10 digits"
                       oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10)">

            </div>

            <div class="col-12">

                <label class="form-label">
                    Address
                </label>

                <textarea name="address"
                          rows="4"
                          class="form-control"
                          placeholder="Enter school address"></textarea>

            </div>

            <div class="col-md-4">

                <label class="form-label">
                    Status
                </label>

                <select name="status" class="form-select">

                    <option value="1">
                        Active
                    </option>

                    <option value="0">
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
                    class="btn btn-primary">

                <i class="bi bi-save"></i>
                Save School

            </button>

        </div>

    </form>

</div>

@endsection

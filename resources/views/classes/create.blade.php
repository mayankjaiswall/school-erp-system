@extends('layouts.principal')

@section('title', 'Create Class')

@section('page-title', 'Create Class')

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
        Create New Class
    </h2>
    <p class="mb-0 opacity-75">
        Add a new class to your ERP platform.
    </p>
</div>

<div class="form-card">
    <form id="classForm" action="{{ route('classes.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">
                    Class Name
                </label>

                <input type="text" name="name" class="form-control" placeholder="Enter class name" required>
            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Class Code
                </label>

                <input type="text" name="class_code" class="form-control" placeholder="Enter class code">

            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Capacity
                </label>
                <input type="number" name="capacity" class="form-control" placeholder="Enter class capacity">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Gender
                </label>
                <select name="gender" class="form-select">
                    <option value="" disabled selected>
                        Select Gender
                    </option>
                    <option value="male">
                        Male
                    </option>
                    <option value="female">
                        Female
                    </option>
                    <option value="other">
                        Other
                    </option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">
                    Description
                </label>
                <input type="text" name="description" class="form-control" placeholder="Enter class description">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Experience (Years)
                </label>
                <input type="number" name="experience" class="form-control" placeholder="Enter class experience">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Status
                </label>
                <select name="status" class="form-select">
                <option value="" disabled selected>
                    Select Status
                </option>
                    <option value="1">
                        Active
                    </option>
                    <option value="0">
                        Inactive
                    </option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">
                    School
                </label>
                <input type="text" name="school_name" class="form-control" value="{{ $school->name }}" disabled>
                <input type="hidden" name="school_id" value="{{ $school->id }}">
            </div>
        </div>
        <div class="action-footer d-flex justify-content-between">
            <a href="{{ route('classes.index') }}"
               class="btn btn-light border">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
            <button type="submit"
                    class="btn btn-primary">
                <i class="bi bi-save"></i>
                Save Class
            </button>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#classForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('classes.index') }}";
                    }
                });
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    });
});
</script>
@endsection
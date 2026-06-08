@extends('layouts.principal')

@section('title', 'Edit Class')

@section('page-title', 'Edit Class')

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
        Edit Class
    </h2>
    <p class="mb-0 opacity-75">
        Update the details of your class.
    </p>
</div>

<div class="form-card">
    <form id="classForm" action="{{ route('classes.update', $class->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">
                    Class Name
                </label>

                <input type="text" name="name" class="form-control" placeholder="Enter class name" value="{{ $class->name }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Section
                </label>
                <input type="text" name="section" class="form-control" placeholder="Enter class section" value="{{ $class->section }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Class Code
                </label>
                <input type="text" name="class_code" class="form-control" placeholder="Enter class code" value="{{ $class->class_code }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Capacity
                </label>
                <input type="number" name="capacity" class="form-control" placeholder="Enter class capacity" value="{{ $class->capacity }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Description
                </label>
                <input type="text" name="description" class="form-control" placeholder="Enter class description" value="{{ $class->description }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Status
                </label>
                <select name="status" class="form-select">
                <option value="" disabled selected>
                    Select Status
                </option>
                    <option value="1" {{ $class->status == 1 ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0" {{ $class->status == 0 ? 'selected' : '' }}>
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
                Update Class
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
                    title: 'Updated!',
                    text: response.message
                }).then(() => {
                    window.location.href = "{{ route('classes.index') }}";
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
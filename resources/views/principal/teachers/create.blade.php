@extends('layouts.principal')

@section('title', 'Create Teacher')

@section('page-title', 'Create Teacher')

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
        Create New Teacher
    </h2>
    <p class="mb-0 opacity-75">
        Add a new teacher to your ERP platform.
    </p>
</div>

<div class="form-card">
    <form id="teacherForm" action="{{ route('teachers.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">
                    Teacher Name
                </label>

                <input type="text" name="name" class="form-control" placeholder="Enter teacher name" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Employee Code
                </label>

                <input type="text" name="employee_code" class="form-control" placeholder="EMP-001" required>
            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Email Address
                </label>

                <input type="email" name="email" class="form-control" placeholder="teacher@example.com" required>

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
                    Qualification
                </label>
                <input type="text" name="qualification" class="form-control" placeholder="MCA, B.Ed, M.Sc" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Experience (Years)
                </label>
                <input type="number" name="experience_years" class="form-control" min="0" max="60" placeholder="Enter teacher experience" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Joining Date
                </label>
                <input type="date" name="joining_date" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Designation
                </label>
                <input type="text" name="designation" class="form-control" placeholder="Computer Science Teacher" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Primary Subject
                </label>
                <select name="primary_subject_id" class="form-select" required>
                    <option value="" disabled selected>Select primary subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">
                            {{ $subject->name }} ({{ $subject->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Login Password
                </label>
                <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Confirm Password
                </label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password" required>
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
            </div>
        </div>
        <div class="action-footer d-flex justify-content-between">
            <a href="{{ route('teachers.index') }}"
               class="btn btn-light border">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
            <button type="submit"
                    class="btn btn-primary">
                <i class="bi bi-save"></i>
                Save Teacher
            </button>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#teacherForm').submit(function (e) {
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
                        window.location.href = "{{ route('teachers.index') }}";
                    }
                });
            },
            error: function (xhr) {
                let message = 'Please check the form and try again.';

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors)[0][0];
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Unable to save teacher',
                    text: message
                });
            }
        });
    });
});
</script>
@endsection

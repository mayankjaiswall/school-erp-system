@extends('layouts.principal')

@section('title', 'Edit Teacher')

@section('page-title', 'Edit Teacher')

@section('content')

<style>
    .form-page-header{
        background: linear-gradient(135deg,#2563eb,#1d4ed8);
        color:#fff;
        padding:34px 36px;
        border-radius:20px;
        margin-bottom:28px;
        box-shadow:0 15px 35px rgba(37,99,235,.25);
    }

    .form-card{
        background:#fff;
        border-radius:20px;
        padding:34px 36px;
        border:1px solid #e2e8f0;
        box-shadow:0 8px 20px rgba(15,23,42,.05);
    }

    .form-label{
        display:block;
        font-weight:600;
        color:#334155;
        margin-bottom:10px;
    }

    .form-control,
    .form-select{
        border-radius:12px;
        min-height:54px;
        padding:14px 16px;
        border:1px solid #dbe2ea;
    }

    .form-control:focus,
    .form-select:focus{
        box-shadow:none;
        border-color:#2563eb;
    }

    .action-footer{
        border-top:1px solid #e2e8f0;
        padding-top:24px;
        margin-top:36px;
    }

    @media (max-width: 768px) {
        .form-page-header,
        .form-card {
            padding:26px 22px;
        }
    }
</style>

<div class="form-page-header">
    <h2 class="mb-2">Edit Teacher</h2>
    <p class="mb-0 opacity-75">
        Update teacher information.
    </p>
</div>

<div class="form-card">

<form id="teacherEditForm" action="{{ route('teachers.update', $teacher->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row g-5">
        <div class="col-md-6">
            <label class="form-label">Teacher Name</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ $teacher->name }}"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Employee Code</label>
            <input type="text"
                   name="employee_code"
                   class="form-control"
                   value="{{ $teacher->employee_code }}"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Email Address</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   value="{{ $teacher->email }}"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Phone Number</label>
            <input type="text"
                   name="phone"
                   class="form-control"
                   value="{{ $teacher->phone }}"
                   inputmode="numeric"
                   maxlength="10"
                   pattern="[0-9]{10}"
                   title="Enter exactly 10 digits"
                   oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10)">
        </div>

        <div class="col-md-6">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
                <option value="male" {{ $teacher->gender == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ $teacher->gender == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ $teacher->gender == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Qualification</label>
            <input type="text"
                   name="qualification"
                   class="form-control"
                   value="{{ $teacher->qualification }}"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Experience (Years)</label>
            <input type="number"
                   name="experience_years"
                   class="form-control"
                   min="0"
                   max="60"
                   value="{{ $teacher->display_experience }}"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Joining Date</label>
            <input type="date"
                   name="joining_date"
                   class="form-control"
                   value="{{ optional($teacher->joining_date)->format('Y-m-d') }}"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Designation</label>
            <input type="text"
                   name="designation"
                   class="form-control"
                   value="{{ $teacher->designation }}"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Primary Subject</label>
            <select name="primary_subject_id" class="form-select" required>
                <option value="" disabled>Select primary subject</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ $teacher->primary_subject_id == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }} ({{ $subject->code }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">New Login Password</label>
            <input type="password"
                   name="password"
                   class="form-control"
                   placeholder="Leave blank to keep current password">
        </div>

        <div class="col-md-6">
            <label class="form-label">Confirm New Password</label>
            <input type="password"
                   name="password_confirmation"
                   class="form-control"
                   placeholder="Re-enter new password">
        </div>

        <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="1" {{ $teacher->status == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $teacher->status == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

    </div>

    <div class="action-footer d-flex justify-content-between">

        <a href="{{ route('teachers.index') }}"
           class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i>
            Update Teacher
        </button>
    </div>
</form>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function () {

    $('#teacherEditForm').submit(function (e) {

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
                    window.location.href = "{{ route('teachers.index') }}";
                });

            },

            error: function (xhr) {
                let message = 'Please check the form and try again.';

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors)[0][0];
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Unable to update teacher',
                    text: message
                });
            }
        });

    });

});
</script>

@endsection

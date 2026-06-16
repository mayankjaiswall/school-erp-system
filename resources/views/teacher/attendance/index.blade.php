@extends('layouts.teacher')

@section('title', 'Take Attendance')

@section('page-title', 'Take Attendance')

@section('content')
<style>
    .attendance-toolbar {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
        padding: 24px;
    }

    .status-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .status-options .form-check {
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        min-width: 92px;
        padding: 8px 14px 8px 36px;
    }

    .status-options .form-check-input:checked {
        background-color: #16a34a;
        border-color: #16a34a;
    }

    .empty-state {
        color: #64748b;
        padding: 48px 16px;
        text-align: center;
    }
</style>

<div class="attendance-toolbar">
    <div class="row g-3 align-items-end">
        <div class="col-lg-5">
            <label for="class_id" class="form-label fw-semibold">Class</label>
            <select id="class_id" class="form-select">
                <option value="">Select class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">
                        {{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <label for="attendance_date" class="form-label fw-semibold">Date</label>
            <input type="date" id="attendance_date" class="form-control" value="{{ now()->toDateString() }}">
        </div>
        <div class="col-lg-4 d-flex gap-2">
            <button type="button" id="loadStudentsBtn" class="btn btn-success px-4">
                <i class="bi bi-people"></i> Load Students
            </button>
            <a href="{{ route('teacher.attendance.report') }}" class="btn btn-outline-success px-4">
                <i class="bi bi-calendar2-check"></i> Reports
            </a>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-1">Student Attendance</h5>
            <small id="sessionStatus" class="text-muted">Select a class and date to load students.</small>
        </div>
        <button type="button" id="saveAttendanceBtn" class="btn btn-primary" disabled>
            <i class="bi bi-save"></i> Save Attendance
        </button>
    </div>

    <form id="attendanceForm">
        <input type="hidden" name="class_id" id="form_class_id">
        <input type="hidden" name="attendance_date" id="form_attendance_date">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width:70px">#</th>
                        <th>Student</th>
                        <th style="width:120px">Roll No</th>
                        <th style="min-width:330px">Status</th>
                        <th style="min-width:220px">Remarks</th>
                    </tr>
                </thead>
                <tbody id="studentsTable">
                    <tr>
                        <td colspan="5" class="empty-state">No students loaded.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    const studentsUrlTemplate = "{{ route('teacher.attendance.students', ['class' => '__CLASS__']) }}";
    const saveUrl = "{{ route('teacher.attendance.save') }}";
    const csrfToken = "{{ csrf_token() }}";

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

    function alertError(title, xhr, fallback) {
        let message = fallback;

        if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) {
                message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
            } else if (xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
        }

        Swal.fire({
            icon: 'error',
            title: title,
            html: message
        });
    }

    function statusRadio(studentId, value, label, current) {
        const id = `status_${studentId}_${value}`;
        const checked = (current || 'present') === value ? 'checked' : '';

        return `
            <div class="form-check">
                <input class="form-check-input" type="radio" name="attendance[${studentId}][status]" id="${id}" value="${value}" ${checked}>
                <label class="form-check-label text-capitalize" for="${id}">${label}</label>
            </div>
        `;
    }

    function loadStudents() {
        const classId = $('#class_id').val();
        const date = $('#attendance_date').val();

        if (!classId || !date) {
            Swal.fire({
                icon: 'warning',
                title: 'Class and date required',
                text: 'Select both class and attendance date.'
            });
            return;
        }

        $('#saveAttendanceBtn').prop('disabled', true);
        $('#studentsTable').html('<tr><td colspan="5" class="empty-state">Loading students...</td></tr>');

        $.ajax({
            url: studentsUrlTemplate.replace('__CLASS__', classId),
            type: 'GET',
            data: { date: date },
            dataType: 'json',
            success: function (response) {
                $('#form_class_id').val(classId);
                $('#form_attendance_date').val(date);

                if (!response.students.length) {
                    $('#studentsTable').html('<tr><td colspan="5" class="empty-state">No active students found in this class.</td></tr>');
                    $('#sessionStatus').text('No active students found.');
                    return;
                }

                const rows = response.students.map(function (student, index) {
                    const remarks = escapeHtml(student.remarks);

                    return `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <strong>${escapeHtml(student.name)}</strong>
                                <br>
                                <small class="text-muted">${escapeHtml(student.admission_no)}</small>
                            </td>
                            <td>${escapeHtml(student.roll_no) || '-'}</td>
                            <td>
                                <div class="status-options">
                                    ${statusRadio(student.id, 'present', 'Present', student.status)}
                                    ${statusRadio(student.id, 'absent', 'Absent', student.status)}
                                    ${statusRadio(student.id, 'late', 'Late', student.status)}
                                </div>
                            </td>
                            <td>
                                <input type="text" name="attendance[${student.id}][remarks]" class="form-control" maxlength="1000" value="${remarks}" placeholder="Optional">
                            </td>
                        </tr>
                    `;
                }).join('');

                $('#studentsTable').html(rows);
                $('#saveAttendanceBtn').prop('disabled', false);
                $('#sessionStatus').text(response.session_exists
                    ? 'Existing attendance loaded. You can edit and save changes.'
                    : 'New attendance session. Mark every student before saving.');
            },
            error: function (xhr) {
                $('#studentsTable').html('<tr><td colspan="5" class="empty-state">Unable to load students.</td></tr>');
                alertError('Unable to load students', xhr, 'Please select one of your assigned classes.');
            }
        });
    }

    $('#loadStudentsBtn').on('click', loadStudents);
    $('#class_id, #attendance_date').on('change', function () {
        $('#saveAttendanceBtn').prop('disabled', true);
        $('#studentsTable').html('<tr><td colspan="5" class="empty-state">Load students after changing class or date.</td></tr>');
        $('#sessionStatus').text('Selection changed. Load students again.');
    });

    $('#saveAttendanceBtn').on('click', function () {
        if ($(this).prop('disabled')) {
            return;
        }

        $.ajax({
            url: saveUrl,
            type: 'POST',
            data: $('#attendanceForm').serialize() + '&_token=' + encodeURIComponent(csrfToken),
            dataType: 'json',
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Attendance saved',
                    text: response.message
                });

                $('#sessionStatus').text(`Saved. Present: ${response.summary.present}, Absent: ${response.summary.absent}, Late: ${response.summary.late}`);
            },
            error: function (xhr) {
                alertError('Attendance not saved', xhr, 'Please check all attendance entries and try again.');
            }
        });
    });
});
</script>
@endpush

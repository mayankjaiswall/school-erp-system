@extends('layouts.teacher')

@section('title', 'Marks Entry')
@section('page-title', 'Marks Entry')

@section('content')
<style>
    .marks-toolbar{background:#fff;border:1px solid #e5e7eb;border-radius:18px;box-shadow:0 10px 30px rgba(15,23,42,.08);padding:24px}
    .empty-state{color:#64748b;padding:48px 16px;text-align:center}
    .marks-input{max-width:130px}
    .remarks-input{min-width:220px}
</style>

<div class="marks-toolbar">
    <div class="row g-3 align-items-end">
        <div class="col-lg-3">
            <label for="exam_id" class="form-label fw-semibold">Exam</label>
            <select id="exam_id" class="form-select">
                <option value="">Select exam</option>
                @foreach($exams as $exam)
                    <option value="{{ $exam->id }}">{{ $exam->name }} - {{ $exam->academic_year }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <label for="class_id" class="form-label fw-semibold">Class</label>
            <select id="class_id" class="form-select">
                <option value="">Select class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <label for="subject_id" class="form-label fw-semibold">Subject</label>
            <select id="subject_id" class="form-select" disabled>
                <option value="">Select subject</option>
            </select>
        </div>
        <div class="col-lg-3">
            <label for="max_marks" class="form-label fw-semibold">Max Marks</label>
            <input type="number" id="max_marks" class="form-control" value="100" min="1" max="9999" step="0.01">
        </div>
        <div class="col-12">
            <button type="button" id="loadStudentsBtn" class="btn btn-success px-4">
                <i class="bi bi-people"></i> Load Students
            </button>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-1">Student Marks</h5>
            <small id="marksStatus" class="text-muted">Select exam, class, and subject to load students.</small>
        </div>
        <button type="button" id="saveMarksBtn" class="btn btn-primary" disabled>
            <i class="bi bi-save"></i> Save Marks
        </button>
    </div>

    <form id="marksForm">
        <input type="hidden" name="exam_id" id="form_exam_id">
        <input type="hidden" name="class_id" id="form_class_id">
        <input type="hidden" name="subject_id" id="form_subject_id">
        <input type="hidden" name="max_marks" id="form_max_marks">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width:70px">#</th>
                        <th>Student</th>
                        <th style="width:120px">Roll No</th>
                        <th style="width:160px">Marks Obtained</th>
                        <th style="min-width:240px">Remarks</th>
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
    const subjects = @json($subjectOptions);
    const assignments = @json($assignmentMap);
    const loadUrl = "{{ route('teacher.marks.students') }}";
    const saveUrl = "{{ route('teacher.marks.save') }}";
    const csrfToken = "{{ csrf_token() }}";

    function escapeHtml(value) {
        return $('<div>').text(value === null || value === undefined ? '' : value).html();
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

        Swal.fire({icon:'error', title:title, html:message});
    }

    function resetTable(message) {
        $('#saveMarksBtn').prop('disabled', true);
        $('#studentsTable').html(`<tr><td colspan="5" class="empty-state">${message}</td></tr>`);
    }

    function populateSubjects() {
        const classId = Number($('#class_id').val());
        const allowedSubjectIds = assignments
            .filter((assignment) => Number(assignment.class_id) === classId)
            .map((assignment) => Number(assignment.subject_id));

        const options = subjects
            .filter((subject) => Number(subject.class_id) === classId && allowedSubjectIds.includes(Number(subject.id)))
            .map((subject) => `<option value="${subject.id}">${escapeHtml(subject.name)}${subject.code ? ' (' + escapeHtml(subject.code) + ')' : ''}</option>`)
            .join('');

        $('#subject_id').html('<option value="">Select subject</option>' + options);
        $('#subject_id').prop('disabled', !classId || options.length === 0);
    }

    function validateSelection() {
        const examId = $('#exam_id').val();
        const classId = $('#class_id').val();
        const subjectId = $('#subject_id').val();
        const maxMarks = Number($('#max_marks').val());

        if (!examId || !classId || !subjectId || !maxMarks || maxMarks <= 0) {
            Swal.fire({icon:'warning', title:'Selection required', text:'Select exam, class, subject, and valid max marks.'});
            return false;
        }

        return true;
    }

    function loadStudents() {
        if (!validateSelection()) {
            return;
        }

        resetTable('Loading students...');

        $.ajax({
            url: loadUrl,
            type: 'GET',
            dataType: 'json',
            data: {
                exam_id: $('#exam_id').val(),
                class_id: $('#class_id').val(),
                subject_id: $('#subject_id').val()
            },
            success: function (response) {
                if (response.max_marks) {
                    $('#max_marks').val(response.max_marks);
                }

                $('#form_exam_id').val($('#exam_id').val());
                $('#form_class_id').val($('#class_id').val());
                $('#form_subject_id').val($('#subject_id').val());
                $('#form_max_marks').val($('#max_marks').val());

                if (!response.students.length) {
                    resetTable('No active students found in this class.');
                    $('#marksStatus').text('No active students found.');
                    return;
                }

                const maxMarks = escapeHtml($('#max_marks').val());
                const rows = response.students.map(function (student, index) {
                    const marksObtained = student.marks_obtained === null || student.marks_obtained === undefined ? '' : student.marks_obtained;
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
                                <input type="number" name="marks[${student.id}][marks_obtained]" class="form-control marks-input" min="0" max="${maxMarks}" step="0.01" value="${escapeHtml(marksObtained)}" required>
                            </td>
                            <td>
                                <input type="text" name="marks[${student.id}][remarks]" class="form-control remarks-input" maxlength="1000" value="${remarks}" placeholder="Optional">
                            </td>
                        </tr>
                    `;
                }).join('');

                $('#studentsTable').html(rows);
                $('#saveMarksBtn').prop('disabled', false);
                $('#marksStatus').text(response.existing ? 'Existing marks loaded. You can edit and save changes.' : 'New marks entry. Enter marks for every student.');
            },
            error: function (xhr) {
                resetTable('Unable to load students.');
                alertError('Unable to load students', xhr, 'Please select only your assigned class and subject.');
            }
        });
    }

    $('#class_id').on('change', function () {
        populateSubjects();
        resetTable('Load students after changing class.');
        $('#marksStatus').text('Selection changed. Load students again.');
    });

    $('#exam_id, #subject_id, #max_marks').on('change input', function () {
        resetTable('Load students after changing selection.');
        $('#marksStatus').text('Selection changed. Load students again.');
    });

    $('#loadStudentsBtn').on('click', loadStudents);

    $('#saveMarksBtn').on('click', function () {
        const maxMarks = Number($('#max_marks').val());
        let hasInvalidMarks = false;

        $('.marks-input').each(function () {
            const value = Number($(this).val());
            if ($(this).val() === '' || value < 0 || value > maxMarks) {
                hasInvalidMarks = true;
            }
        });

        if (hasInvalidMarks) {
            Swal.fire({icon:'warning', title:'Invalid marks', text:'Marks must be between 0 and max marks.'});
            return;
        }

        $('#form_max_marks').val($('#max_marks').val());

        $.ajax({
            url: saveUrl,
            type: 'POST',
            data: $('#marksForm').serialize() + '&_token=' + encodeURIComponent(csrfToken),
            dataType: 'json',
            success: function (response) {
                Swal.fire({icon:'success', title:'Marks saved', text:response.message});
                $('#marksStatus').text('Saved successfully. Existing marks will load for editing next time.');
            },
            error: function (xhr) {
                alertError('Marks not saved', xhr, 'Please check all marks entries and try again.');
            }
        });
    });

    populateSubjects();
});
</script>
@endpush

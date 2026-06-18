@extends('layouts.principal')

@section('title', 'Report Cards')
@section('page-title', 'Report Cards')

@section('content')
<style>
    .report-toolbar{background:#fff;border:1px solid #e2e8f0;border-radius:20px;box-shadow:0 8px 20px rgba(15,23,42,.05);padding:25px}
    .report-actions{display:flex;gap:10px;flex-wrap:wrap}
    .empty-report{background:#fff;border:1px dashed #cbd5e1;border-radius:16px;color:#64748b;padding:48px 20px;text-align:center}
</style>

<div class="report-toolbar">
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
            <label for="student_id" class="form-label fw-semibold">Student</label>
            <select id="student_id" class="form-select" disabled>
                <option value="">All students</option>
            </select>
        </div>
        <div class="col-lg-3">
            <label class="form-label fw-semibold d-block">Actions</label>
            <div class="report-actions">
                <button type="button" id="generateBtn" class="btn btn-primary">
                    <i class="bi bi-eye"></i> View
                </button>
                <button type="button" id="printBtn" class="btn btn-outline-secondary" disabled>
                    <i class="bi bi-printer"></i> Print
                </button>
                <button type="button" id="downloadBtn" class="btn btn-success" disabled>
                    <i class="bi bi-download"></i> PDF
                </button>
            </div>
        </div>
    </div>
</div>

<div class="mt-4" id="reportCardContainer">
    <div class="empty-report">Select exam and class to load report cards.</div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    const studentsUrl = "{{ route('principal.report-cards.students') }}";
    const generateUrl = "{{ route('principal.report-cards.generate') }}";
    const printUrl = "{{ route('principal.report-cards.print') }}";
    const downloadUrl = "{{ route('principal.report-cards.download-pdf') }}";

    function escapeHtml(value) {
        return $('<div>').text(value === null || value === undefined ? '' : value).html();
    }

    function selectedParams() {
        return {
            exam_id: $('#exam_id').val(),
            class_id: $('#class_id').val(),
            student_id: $('#student_id').val()
        };
    }

    function queryString() {
        return $.param(selectedParams());
    }

    function setActionState(enabled) {
        $('#printBtn, #downloadBtn').prop('disabled', !enabled);
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

    function loadStudents() {
        const classId = $('#class_id').val();
        $('#student_id').html('<option value="">All students</option>').prop('disabled', true);
        setActionState(false);

        if (!classId) {
            $('#reportCardContainer').html('<div class="empty-report">Select exam and class to load report cards.</div>');
            return;
        }

        $.ajax({
            url: studentsUrl,
            type: 'GET',
            dataType: 'json',
            data: {class_id: classId},
            success: function (response) {
                const options = response.students.map(function (student) {
                    const label = `${escapeHtml(student.name)}${student.roll_no ? ' (' + escapeHtml(student.roll_no) + ')' : ''}`;
                    return `<option value="${student.id}">${label}</option>`;
                }).join('');

                $('#student_id').html('<option value="">All students</option>' + options).prop('disabled', false);
                loadReportCards();
            },
            error: function (xhr) {
                alertError('Unable to load students', xhr, 'Please try again.');
            }
        });
    }

    function validateSelection() {
        if (!$('#exam_id').val() || !$('#class_id').val()) {
            Swal.fire({icon:'warning', title:'Selection required', text:'Select exam and class first.'});
            return false;
        }

        return true;
    }

    function loadReportCards() {
        if (!$('#exam_id').val() || !$('#class_id').val()) {
            setActionState(false);
            return;
        }

        $('#reportCardContainer').html('<div class="empty-report">Generating report cards...</div>');
        setActionState(false);

        $.ajax({
            url: generateUrl,
            type: 'GET',
            dataType: 'json',
            data: selectedParams(),
            success: function (response) {
                $('#reportCardContainer').html(response.html);
                setActionState(true);
            },
            error: function (xhr) {
                $('#reportCardContainer').html('<div class="empty-report">No report card found for the selected filters.</div>');
                alertError('Unable to generate report cards', xhr, 'No report cards found.');
            }
        });
    }

    $('#class_id').on('change', loadStudents);
    $('#exam_id, #student_id').on('change', loadReportCards);
    $('#generateBtn').on('click', function () {
        if (validateSelection()) {
            loadReportCards();
        }
    });
    $('#printBtn').on('click', function () {
        if (validateSelection()) {
            window.open(printUrl + '?' + queryString(), '_blank');
        }
    });
    $('#downloadBtn').on('click', function () {
        if (validateSelection()) {
            window.location.href = downloadUrl + '?' + queryString();
        }
    });
});
</script>
@endpush

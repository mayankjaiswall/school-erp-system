@extends('layouts.parent')

@section('title', 'Report Cards')
@section('page-title', 'Report Cards')

@section('content')
<div class="content-card mt-0">
    <div class="row g-3 align-items-end">
        <div class="col-md-4"><label class="form-label fw-semibold">Child</label><select id="student_id" class="form-select">@foreach($children as $child)<option value="{{ $child->id }}">{{ $child->name }} - {{ $child->admission_no }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label fw-semibold">Exam</label><select id="exam_id" class="form-select"><option value="">Select Exam</option>@foreach($exams as $exam)<option value="{{ $exam->id }}">{{ $exam->name }} - {{ $exam->academic_year }}</option>@endforeach</select></div>
        <div class="col-md-4 d-flex gap-2"><button type="button" id="loadReport" class="btn btn-primary flex-fill"><i class="bi bi-eye"></i> View</button><button type="button" id="printReport" class="btn btn-outline-secondary"><i class="bi bi-printer"></i></button><button type="button" id="downloadReport" class="btn btn-success"><i class="bi bi-download"></i></button></div>
    </div>
</div>
<div class="content-card" id="reportOutput"><div class="text-center text-muted py-5">Select an exam to load the report card.</div></div>
@endsection

@push('scripts')
<script>
$(function () {
    const loadUrl = "{{ route('parent.report-cards') }}";
    const printUrl = "{{ route('parent.report-cards.print') }}";
    const downloadUrl = "{{ route('parent.report-cards.download-pdf') }}";
    function params() { return {student_id: $('#student_id').val(), exam_id: $('#exam_id').val()}; }
    function query() { return $.param(params()); }
    function validate() {
        if (!$('#student_id').val() || !$('#exam_id').val()) {
            Swal.fire({icon:'warning', title:'Selection required', text:'Select child and exam.'});
            return false;
        }
        return true;
    }
    $('#loadReport').on('click', function () {
        if (!validate()) return;
        $('#reportOutput').html('<div class="text-center text-muted py-5">Loading report card...</div>');
        $.getJSON(loadUrl, params()).done((response) => $('#reportOutput').html(response.html)).fail((xhr) => Swal.fire({icon:'error', title:'Report card not found', text:xhr.responseJSON?.message || 'No report card data found.'}));
    });
    $('#printReport').on('click', function () { if (validate()) window.open(printUrl + '?' + query(), '_blank'); });
    $('#downloadReport').on('click', function () { if (validate()) window.location.href = downloadUrl + '?' + query(); });
});
</script>
@endpush

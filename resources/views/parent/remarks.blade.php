@extends('layouts.parent')

@section('title', 'Teacher Remarks')
@section('page-title', 'Teacher Remarks')

@section('content')
<div class="content-card mt-0">
    <div class="row g-3 align-items-end">
        <div class="col-md-8"><label class="form-label fw-semibold">Child</label><select id="student_id" class="form-select">@foreach($children as $child)<option value="{{ $child->id }}">{{ $child->name }} - {{ $child->admission_no }}</option>@endforeach</select></div>
        <div class="col-md-4"><button type="button" id="loadRemarks" class="btn btn-primary w-100"><i class="bi bi-chat-left-text"></i> Load Remarks</button></div>
    </div>
</div>
<div class="content-card">
    <h5 class="mb-3">Remarks</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Date</th><th>Teacher</th><th>Remark</th></tr></thead>
            <tbody id="remarksBody"><tr><td colspan="3" class="text-center text-muted">Load remarks to view records.</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const url = "{{ route('parent.remarks') }}";
    function escapeHtml(value) { return $('<div>').text(value || '').html(); }
    function loadRemarks() {
        $.getJSON(url, {student_id: $('#student_id').val()})
            .done(function (response) {
                $('#remarksBody').html(response.remarks.map((remark) => `<tr><td>${escapeHtml(remark.date)}</td><td>${escapeHtml(remark.teacher)}</td><td>${escapeHtml(remark.remark)}</td></tr>`).join('') || '<tr><td colspan="3" class="text-center text-muted">No remarks found.</td></tr>');
            })
            .fail(function () { Swal.fire({icon:'error', title:'Unable to load remarks', text:'Please try again.'}); });
    }
    $('#loadRemarks, #student_id').on('click change', loadRemarks);
    loadRemarks();
});
</script>
@endpush

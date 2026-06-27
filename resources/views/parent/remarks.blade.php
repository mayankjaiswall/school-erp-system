@extends('layouts.parent')

@section('title', 'Teacher Remarks')
@section('page-title', 'Teacher Remarks')

@section('content')
<div class="content-card mt-0">
    <div class="row g-3 align-items-end">
        <div class="col-md-5"><label class="form-label fw-semibold">Child</label><select id="student_id" class="form-select">@foreach($children as $child)<option value="{{ $child->id }}">{{ $child->name }} - {{ $child->admission_no }}</option>@endforeach</select></div>
        <div class="col-md-5"><label class="form-label fw-semibold">Search</label><input type="search" id="search" class="form-control" placeholder="Teacher or remark"></div>
        <div class="col-md-2"><button type="button" id="loadRemarks" class="btn btn-primary w-100"><i class="bi bi-search"></i> Search</button></div>
    </div>
</div>
<div class="content-card">
    <h5 class="mb-3">Remarks</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Date</th><th>Teacher</th><th>Subject</th><th>Remark</th><th>Status</th></tr></thead>
            <tbody id="remarksBody"><tr><td colspan="5" class="text-center text-muted">Load remarks to view records.</td></tr></tbody>
        </table>
    </div>
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <small id="remarksMeta" class="text-muted"></small>
        <div class="btn-group">
            <button type="button" id="prevPage" class="btn btn-outline-secondary btn-sm">Previous</button>
            <button type="button" id="nextPage" class="btn btn-outline-secondary btn-sm">Next</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const url = "{{ route('parent.remarks') }}";
    let currentPage = 1;
    let lastPage = 1;
    function escapeHtml(value) { return $('<div>').text(value || '').html(); }
    function loadRemarks(page = 1) {
        $.getJSON(url, {student_id: $('#student_id').val(), search: $('#search').val(), page: page})
            .done(function (response) {
                currentPage = response.pagination.current_page;
                lastPage = response.pagination.last_page;
                $('#remarksBody').html(response.remarks.map((remark) => `<tr><td>${escapeHtml(remark.date)}</td><td>${escapeHtml(remark.teacher)}</td><td>${escapeHtml(remark.subject)}</td><td>${escapeHtml(remark.remark)}</td><td><span class="badge bg-success">${escapeHtml(remark.status)}</span></td></tr>`).join('') || '<tr><td colspan="5" class="text-center text-muted">No remarks found.</td></tr>');
                $('#remarksMeta').text(response.pagination.total + ' remarks');
                $('#prevPage').prop('disabled', currentPage <= 1);
                $('#nextPage').prop('disabled', currentPage >= lastPage);
            })
            .fail(function () { Swal.fire({icon:'error', title:'Unable to load remarks', text:'Please try again.'}); });
    }
    $('#loadRemarks').on('click', function () { loadRemarks(1); });
    $('#student_id').on('change', function () { loadRemarks(1); });
    $('#search').on('keyup', function (event) { if (event.key === 'Enter') loadRemarks(1); });
    $('#prevPage').on('click', function () { if (currentPage > 1) loadRemarks(currentPage - 1); });
    $('#nextPage').on('click', function () { if (currentPage < lastPage) loadRemarks(currentPage + 1); });
    loadRemarks();
});
</script>
@endpush

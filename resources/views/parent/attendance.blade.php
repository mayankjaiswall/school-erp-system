@extends('layouts.parent')

@section('title', 'Attendance')
@section('page-title', 'Attendance')

@section('content')
<div class="content-card mt-0">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Child</label>
            <select id="student_id" class="form-select">
                @foreach($children as $child)
                    <option value="{{ $child->id }}">{{ $child->name }} - {{ $child->admission_no }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Month</label>
            <select id="month" class="form-select">
                <option value="">All Months</option>
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Academic Year</label>
            <select id="academic_year" class="form-select">
                <option value="">Current Year</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" id="loadAttendance" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-md-3"><div class="stats-card card-green"><h6>Attendance %</h6><h2 id="percentage">0%</h2><small>Present + late days</small></div></div>
    <div class="col-md-3"><div class="stats-card card-blue"><h6>Present Days</h6><h2 id="presentDays">0</h2><small>Marked present</small></div></div>
    <div class="col-md-3"><div class="stats-card card-orange"><h6>Absent Days</h6><h2 id="absentDays">0</h2><small>Marked absent</small></div></div>
    <div class="col-md-3"><div class="stats-card card-purple"><h6>Late Days</h6><h2 id="lateDays">0</h2><small>Marked late</small></div></div>
</div>

<div class="content-card">
    <h5 class="mb-3">Daily Attendance</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Date</th><th>Status</th><th>Remarks</th></tr></thead>
            <tbody id="attendanceBody"><tr><td colspan="3" class="text-center text-muted">Load attendance to view records.</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const url = "{{ route('parent.attendance') }}";
    function escapeHtml(value) { return $('<div>').text(value || '').html(); }
    function loadAttendance() {
        $.getJSON(url, {student_id: $('#student_id').val(), month: $('#month').val(), academic_year: $('#academic_year').val()})
            .done(function (response) {
                $('#percentage').text(response.summary.percentage + '%');
                $('#presentDays').text(response.summary.present_days);
                $('#absentDays').text(response.summary.absent_days);
                $('#lateDays').text(response.summary.late_days);
                $('#attendanceBody').html(response.records.map((record) => `<tr><td>${escapeHtml(record.date)}</td><td><span class="badge bg-info">${escapeHtml(record.status)}</span></td><td>${escapeHtml(record.remarks)}</td></tr>`).join('') || '<tr><td colspan="3" class="text-center text-muted">No records found.</td></tr>');
            })
            .fail(function () { Swal.fire({icon:'error', title:'Unable to load attendance', text:'Please try again.'}); });
    }
    $('#loadAttendance, #student_id').on('click change', loadAttendance);
    loadAttendance();
});
</script>
@endpush

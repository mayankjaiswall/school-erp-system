@extends('layouts.teacher')

@section('title', 'Attendance Reports')

@section('page-title', 'Attendance Reports')

@section('content')
<style>
    .dashboard-card {
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
        color: #fff;
        padding: 25px;
    }

    .dashboard-card h2 {
        font-size: 34px;
        font-weight: 700;
        margin: 10px 0 0;
    }

    .card-blue {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .card-green {
        background: linear-gradient(135deg, #16a34a, #15803d);
    }

    .card-orange {
        background: linear-gradient(135deg, #ea580c, #c2410c);
    }
</style>

<div class="content-card mt-0">
    <div class="row g-3 align-items-end">
        <div class="col-lg-4">
            <label for="class_id" class="form-label fw-semibold">Class</label>
            <select id="class_id" class="form-select">
                <option value="">All assigned classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <label for="from_date" class="form-label fw-semibold">From</label>
            <input type="date" id="from_date" class="form-control">
        </div>
        <div class="col-lg-3">
            <label for="to_date" class="form-label fw-semibold">To</label>
            <input type="date" id="to_date" class="form-control">
        </div>
        <div class="col-lg-2">
            <button type="button" id="filterBtn" class="btn btn-success w-100">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-md-4">
        <div class="dashboard-card card-green">
            <h6>Total Present</h6>
            <h2 id="presentCount">0</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dashboard-card card-orange">
            <h6>Total Absent</h6>
            <h2 id="absentCount">0</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dashboard-card card-blue">
            <h6>Total Late</h6>
            <h2 id="lateCount">0</h2>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Class</th>
                    <th>Student</th>
                    <th>Roll No</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody id="reportTable">
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Use filters to load attendance records.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    const reportUrl = "{{ route('teacher.attendance.report') }}";

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

    function badge(status) {
        const classes = {
            present: 'bg-success',
            absent: 'bg-danger',
            late: 'bg-warning text-dark'
        };

        return `<span class="badge ${classes[status] || 'bg-secondary'} text-capitalize">${status}</span>`;
    }

    function loadReport() {
        $('#reportTable').html('<tr><td colspan="6" class="text-center text-muted py-4">Loading records...</td></tr>');

        $.ajax({
            url: reportUrl,
            type: 'GET',
            dataType: 'json',
            data: {
                class_id: $('#class_id').val(),
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val()
            },
            success: function (response) {
                $('#presentCount').text(response.summary.present);
                $('#absentCount').text(response.summary.absent);
                $('#lateCount').text(response.summary.late);

                if (!response.records.length) {
                    $('#reportTable').html('<tr><td colspan="6" class="text-center text-muted py-4">No attendance records found.</td></tr>');
                    return;
                }

                const rows = response.records.map(function (record) {
                    return `
                        <tr>
                            <td>${record.date}</td>
                            <td>${escapeHtml(record.class) || '-'}</td>
                            <td>${escapeHtml(record.student) || '-'}</td>
                            <td>${escapeHtml(record.roll_no) || '-'}</td>
                            <td>${badge(record.status)}</td>
                            <td>${escapeHtml(record.remarks) || '-'}</td>
                        </tr>
                    `;
                }).join('');

                $('#reportTable').html(rows);
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Unable to load report',
                    text: xhr.responseJSON?.message || 'Please try again.'
                });
            }
        });
    }

    $('#filterBtn').on('click', loadReport);
    loadReport();
});
</script>
@endpush

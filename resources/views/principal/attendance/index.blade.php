@extends('layouts.principal')

@section('title', 'Student Attendance')

@section('page-title', 'Student Attendance')

@section('content')
<div class="content-card mt-0">
    <div class="row g-3 align-items-end">
        <div class="col-lg-3">
            <label for="class_id" class="form-label fw-semibold">Class</label>
            <select id="class_id" class="form-select">
                <option value="">All classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <label for="date" class="form-label fw-semibold">Date</label>
            <input type="date" id="date" class="form-control">
        </div>
        <div class="col-lg-4">
            <label for="student_id" class="form-label fw-semibold">Student</label>
            <select id="student_id" class="form-select">
                <option value="">All students</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }}{{ $student->roll_no ? ' ('.$student->roll_no.')' : '' }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2">
            <button type="button" id="filterBtn" class="btn btn-primary w-100">
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
    <h5 class="mb-3">Attendance Records</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Class</th>
                    <th>Teacher</th>
                    <th>Student</th>
                    <th>Roll No</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody id="attendanceTable">
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Loading attendance records...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="content-card">
            <h5 class="mb-3">Student Attendance Percentage</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Roll No</th>
                            <th>Marked</th>
                            <th>Attendance %</th>
                        </tr>
                    </thead>
                    <tbody id="studentPercentTable">
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No data.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="content-card">
            <h5 class="mb-3">Class Attendance Percentage</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Marked</th>
                            <th>Attendance %</th>
                        </tr>
                    </thead>
                    <tbody id="classPercentTable">
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No data.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    const filterUrl = "{{ route('principal.attendance.filter') }}";
    const showUrlTemplate = "{{ route('principal.attendance.show', ['id' => '__ID__']) }}";

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

    function renderPercent(value) {
        const color = value >= 75 ? 'bg-success' : (value >= 50 ? 'bg-warning text-dark' : 'bg-danger');
        return `<span class="badge ${color}">${value}%</span>`;
    }

    function loadAttendance() {
        $('#attendanceTable').html('<tr><td colspan="8" class="text-center text-muted py-4">Loading attendance records...</td></tr>');

        $.ajax({
            url: filterUrl,
            type: 'GET',
            dataType: 'json',
            data: {
                class_id: $('#class_id').val(),
                date: $('#date').val(),
                student_id: $('#student_id').val()
            },
            success: function (response) {
                $('#presentCount').text(response.summary.present);
                $('#absentCount').text(response.summary.absent);
                $('#lateCount').text(response.summary.late);

                if (!response.records.length) {
                    $('#attendanceTable').html('<tr><td colspan="8" class="text-center text-muted py-4">No attendance records found.</td></tr>');
                } else {
                    const rows = response.records.map(function (record) {
                        return `
                            <tr>
                                <td>${record.date}</td>
                                <td>${escapeHtml(record.class) || '-'}</td>
                                <td>${escapeHtml(record.teacher) || '-'}</td>
                                <td>${escapeHtml(record.student) || '-'}</td>
                                <td>${escapeHtml(record.roll_no) || '-'}</td>
                                <td>${badge(record.status)}</td>
                                <td>${escapeHtml(record.remarks) || '-'}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary view-session" data-id="${record.session_id}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('');

                    $('#attendanceTable').html(rows);
                }

                renderStudentPercentages(response.student_percentages);
                renderClassPercentages(response.class_percentages);
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Unable to load attendance',
                    text: xhr.responseJSON?.message || 'Please try again.'
                });
            }
        });
    }

    function renderStudentPercentages(rows) {
        if (!rows.length) {
            $('#studentPercentTable').html('<tr><td colspan="4" class="text-center text-muted py-4">No data.</td></tr>');
            return;
        }

        $('#studentPercentTable').html(rows.map(function (row) {
            return `
                <tr>
                    <td>${escapeHtml(row.student) || '-'}</td>
                    <td>${escapeHtml(row.roll_no) || '-'}</td>
                    <td>${row.attended}/${row.total}</td>
                    <td>${renderPercent(row.percentage)}</td>
                </tr>
            `;
        }).join(''));
    }

    function renderClassPercentages(rows) {
        if (!rows.length) {
            $('#classPercentTable').html('<tr><td colspan="3" class="text-center text-muted py-4">No data.</td></tr>');
            return;
        }

        $('#classPercentTable').html(rows.map(function (row) {
            return `
                <tr>
                    <td>${escapeHtml(row.class) || '-'}</td>
                    <td>${row.attended}/${row.total}</td>
                    <td>${renderPercent(row.percentage)}</td>
                </tr>
            `;
        }).join(''));
    }

    $(document).on('click', '.view-session', function () {
        const id = $(this).data('id');

        $.ajax({
            url: showUrlTemplate.replace('__ID__', id),
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const session = response.session;
                const rows = session.students.map(function (student) {
                    return `
                        <tr>
                            <td>${escapeHtml(student.name) || '-'}</td>
                            <td>${escapeHtml(student.roll_no) || '-'}</td>
                            <td>${badge(student.status)}</td>
                            <td>${escapeHtml(student.remarks) || '-'}</td>
                        </tr>
                    `;
                }).join('');

                Swal.fire({
                    title: `${escapeHtml(session.class)} - ${session.date}`,
                    width: 800,
                    html: `
                        <div class="text-start mb-3">
                            <strong>Teacher:</strong> ${escapeHtml(session.teacher) || '-'}<br>
                            <strong>Present:</strong> ${session.summary.present}
                            <strong class="ms-3">Absent:</strong> ${session.summary.absent}
                            <strong class="ms-3">Late:</strong> ${session.summary.late}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Roll No</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                    `
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Unable to load details',
                    text: xhr.responseJSON?.message || 'Please try again.'
                });
            }
        });
    });

    $('#filterBtn').on('click', loadAttendance);
    $('#class_id, #date, #student_id').on('change', loadAttendance);
    loadAttendance();
});
</script>
@endpush

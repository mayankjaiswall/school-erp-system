@extends('layouts.parent')

@section('title', 'Marks')
@section('page-title', 'Marks')

@section('content')
<div class="content-card mt-0">
    <div class="row g-3 align-items-end">
        <div class="col-md-4"><label class="form-label fw-semibold">Child</label><select id="student_id" class="form-select">@foreach($children as $child)<option value="{{ $child->id }}">{{ $child->name }} - {{ $child->admission_no }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label fw-semibold">Exam</label><select id="exam_id" class="form-select"><option value="">All Exams</option>@foreach($exams as $exam)<option value="{{ $exam->id }}">{{ $exam->name }} - {{ $exam->academic_year }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label fw-semibold">Subject</label><select id="subject_id" class="form-select"><option value="">All Subjects</option>@foreach($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach</select></div>
        <div class="col-md-2"><button type="button" id="loadResults" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button></div>
    </div>
</div>
<div class="content-card">
    <h5 class="mb-3">Marks</h5>
    <div id="resultsBody" class="table-responsive"><div class="text-center text-muted py-4">Load results to view records.</div></div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const url = "{{ route('parent.results') }}";
    function escapeHtml(value) { return $('<div>').text(value === null || value === undefined ? '' : value).html(); }
    function loadResults() {
        $.getJSON(url, {student_id: $('#student_id').val(), exam_id: $('#exam_id').val(), subject_id: $('#subject_id').val()})
            .done(function (response) {
                if (!response.results.length) {
                    $('#resultsBody').html('<div class="text-center text-muted py-4">No results found.</div>');
                    return;
                }
                $('#resultsBody').html(response.results.map((result) => `
                    <div class="mb-4">
                        <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
                            <div><h5 class="mb-1">${escapeHtml(result.exam)}</h5><small class="text-muted">${escapeHtml(result.exam_type)} | ${escapeHtml(result.academic_year)}</small></div>
                            <div><span class="badge bg-primary">${escapeHtml(result.percentage)}%</span> <span class="badge ${result.result === 'PASS' ? 'bg-success' : 'bg-danger'}">${escapeHtml(result.result)}</span></div>
                        </div>
                        <table class="table align-middle"><thead><tr><th>Subject</th><th>Max Marks</th><th>Obtained Marks</th><th>Percentage</th><th>Grade</th><th>Remarks</th></tr></thead><tbody>
                            ${result.subjects.map((subject) => `<tr><td>${escapeHtml(subject.subject)}</td><td>${escapeHtml(subject.max_marks)}</td><td>${escapeHtml(subject.marks_obtained)}</td><td>${escapeHtml(subject.percentage)}%</td><td>${escapeHtml(subject.grade)}</td><td>${escapeHtml(subject.remarks || '-')}</td></tr>`).join('')}
                        </tbody><tfoot><tr><th>Total</th><th>${escapeHtml(result.total_marks)}</th><th>${escapeHtml(result.obtained_marks)}</th><th>${escapeHtml(result.percentage)}%</th><th>${escapeHtml(result.grade)}</th><th>Attendance: ${escapeHtml(result.attendance_percentage)}%</th></tr></tfoot></table>
                    </div>`).join(''));
            })
            .fail(function () { Swal.fire({icon:'error', title:'Unable to load results', text:'Please try again.'}); });
    }
    $('#loadResults').on('click', loadResults);
    $('#student_id, #exam_id, #subject_id').on('change', loadResults);
    loadResults();
});
</script>
@endpush

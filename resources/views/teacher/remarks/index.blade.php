@extends('layouts.teacher')

@section('title', 'Teacher Remarks')
@section('page-title', 'Teacher Remarks')

@section('content')
<style>.remarks-toolbar{background:#fff;border:1px solid #e5e7eb;border-radius:18px;box-shadow:0 10px 30px rgba(15,23,42,.08);padding:24px}.empty-state{color:#64748b;padding:32px 16px;text-align:center}</style>
<div class="remarks-toolbar">
    <form id="remarkForm" action="{{ route('teacher.remarks.store') }}" method="POST">
        @csrf
        <div class="row g-3 align-items-end">
            <div class="col-lg-4">
                <label class="form-label fw-semibold">Student</label>
                <select name="student_id" class="form-select" required>
                    <option value="">Select student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} - {{ $student->class?->name }}{{ $student->class?->section ? ' '.$student->class->section : '' }} ({{ $student->admission_no }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3"><label class="form-label fw-semibold">Remark Date</label><input type="date" name="remark_date" value="{{ now()->toDateString() }}" class="form-control" required></div>
            <div class="col-lg-5"><label class="form-label fw-semibold">Remark</label><textarea name="remark" class="form-control" rows="2" required maxlength="3000" placeholder="Enter teacher remark"></textarea></div>
            <div class="col-12"><button type="submit" class="btn btn-success px-4"><i class="bi bi-save"></i> Save Remark</button></div>
        </div>
    </form>
</div>

<div class="content-card">
    <h5 class="mb-3">Recent Remarks</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Date</th><th>Student</th><th>Class</th><th>Remark</th></tr></thead>
            <tbody>
                @forelse($remarks as $remark)
                    <tr><td>{{ $remark->remark_date?->format('Y-m-d') }}</td><td>{{ $remark->student?->name }}</td><td>{{ $remark->student?->class?->name }}{{ $remark->student?->class?->section ? ' - '.$remark->student->class->section : '' }}</td><td>{{ $remark->remark }}</td></tr>
                @empty
                    <tr><td colspan="4" class="empty-state">No remarks added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    $('#remarkForm').on('submit', function (event) {
        event.preventDefault();
        const form = $(this);
        const button = form.find('button[type="submit"]');
        button.prop('disabled', true);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                Swal.fire({icon:'success', title:'Saved', text:response.message, timer:1300, showConfirmButton:false})
                    .then(() => window.location.reload());
            },
            error: function (xhr) {
                button.prop('disabled', false);
                let message = 'Please check the form and try again.';
                if (xhr.responseJSON?.errors) message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                else if (xhr.responseJSON?.message) message = xhr.responseJSON.message;
                Swal.fire({icon:'error', title:'Unable to save remark', html:message});
            }
        });
    });
});
</script>
@endpush

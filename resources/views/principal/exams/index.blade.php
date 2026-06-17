@extends('layouts.principal')

@section('title', 'Exams')
@section('page-title', 'Exam Management')

@section('content')
<style>
    .exam-list-header{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 15px 35px rgba(37,99,235,.25)}
    .exam-list-header h2{margin:0;font-weight:700}
    .stats-card,.table-card{background:#fff;border:1px solid #e2e8f0;box-shadow:0 8px 20px rgba(15,23,42,.05)}
    .stats-card{padding:20px;border-radius:16px;text-align:center}
    .stats-card h3{margin:0;color:#2563eb;font-weight:700}
    .stats-card span{color:#64748b;font-size:14px}
    .table-card{border-radius:20px;padding:25px}
    .status-pill{padding:6px 14px;border-radius:30px;font-size:13px;font-weight:600}
    .status-pill.active{background:#dcfce7;color:#166534}
    .status-pill.inactive{background:#fee2e2;color:#991b1b}
    .btn-action{width:38px;height:38px;border:0;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;margin-right:5px;transition:.3s}
    .btn-edit{background:#fef3c7;color:#d97706}.btn-edit:hover{background:#d97706;color:#fff}
    .btn-delete{background:#fee2e2;color:#dc2626}.btn-delete:hover{background:#dc2626;color:#fff}
    .empty-state{padding:50px;text-align:center;color:#64748b}.empty-state i{font-size:50px;margin-bottom:15px;display:block;color:#cbd5e1}
</style>

<div class="exam-list-header">
    <div>
        <h2>Exam Management</h2>
        <p class="mb-0 opacity-75">Create and manage school exams for marks entry.</p>
    </div>
    <a href="{{ route('principal.exams.create') }}" class="btn btn-light">
        <i class="bi bi-plus-circle"></i> Add Exam
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <h3 id="totalExamsCount">{{ $exams->count() }}</h3>
            <span>Total Exams</span>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h5 class="mb-0">Exams {{ $exams->count() }}</h5>
        <form action="{{ route('principal.exams.index') }}" method="GET" class="d-flex gap-2" role="search">
            <input type="search"
                   name="search"
                   value="{{ $search ?? '' }}"
                   class="form-control"
                   placeholder="Search exams..."
                   style="min-width:260px">
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Exam</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Academic Year</th>
                    <th>Status</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody id="examsTableBody">
            @forelse($exams as $exam)
                <tr id="exam-row-{{ $exam->id }}">
                    <td class="exam-row-number">{{ $loop->iteration }}</td>
                    <td><strong>{{ $exam->name }}</strong></td>
                    <td>{{ $exam->exam_type }}</td>
                    <td>{{ $exam->exam_date?->format('d M Y') }}</td>
                    <td>{{ $exam->academic_year }}</td>
                    <td><span class="status-pill {{ $exam->status ? 'active' : 'inactive' }}">{{ $exam->status ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('principal.exams.edit', $exam->id) }}" class="btn-action btn-edit" title="Edit"><i class="bi bi-pencil-square"></i></a>
                        <form id="delete-form-{{ $exam->id }}" action="{{ route('principal.exams.destroy', $exam->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-action btn-delete" title="Delete" onclick="confirmDelete('{{ $exam->id }}','{{ addslashes($exam->name) }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-file-earmark-text"></i>
                            <h5>No Exam Found</h5>
                            <p>Start by creating Unit Test, Half Yearly, or Annual exams.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id, examName)
{
    Swal.fire({
        title:'Delete Exam?',
        html:`<div style="padding:10px"><div style="width:80px;height:80px;margin:auto;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center"><i class="bi bi-trash-fill" style="font-size:35px;color:#dc2626"></i></div><div style="margin-top:20px"><p class="text-muted">You are about to delete</p><h4 style="color:#2563eb;font-weight:700">${examName}</h4><small style="color:#dc2626;font-weight:600">Related marks will also be deleted.</small></div></div>`,
        showCancelButton:true,
        confirmButtonColor:'#dc2626',
        cancelButtonColor:'#64748b',
        confirmButtonText:'Delete Exam',
        cancelButtonText:'Cancel',
        reverseButtons:true,
        customClass:{popup:'rounded-4'}
    }).then((result) => {
        if(result.isConfirmed) {
            deleteExam(id);
        }
    });
}

function deleteExam(id)
{
    const form = document.getElementById('delete-form-' + id);
    const button = form.querySelector('.btn-delete');
    const formData = new FormData(form);

    button.disabled = true;

    fetch(form.action, {method:'POST', body:formData, headers:{'Accept':'application/json'}})
        .then((response) => response.json())
        .then((data) => {
            if (!data.success) {
                throw new Error(data.message || 'Unable to delete exam.');
            }

            document.getElementById('exam-row-' + id).remove();
            updateExamCounts();
            Swal.fire({icon:'success', title:'Deleted', text:data.message, timer:1600, showConfirmButton:false});
        })
        .catch((error) => {
            button.disabled = false;
            Swal.fire({icon:'error', title:'Delete failed', text:error.message});
        });
}

function updateExamCounts()
{
    const rows = document.querySelectorAll('#examsTableBody tr[id^="exam-row-"]');
    rows.forEach((row, index) => {
        row.querySelector('.exam-row-number').textContent = index + 1;
    });
    document.getElementById('totalExamsCount').textContent = rows.length;
}
</script>
@endpush

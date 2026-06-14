@extends('layouts.principal')

@section('title', 'Teacher Subjects')
@section('page-title', 'Teacher Subject Assignment')

@section('content')
<style>
    .assignment-list-header{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 15px 35px rgba(37,99,235,.25)}
    .assignment-list-header h2{margin:0;font-weight:700}
    .stats-card,.table-card{background:#fff;border:1px solid #e2e8f0;box-shadow:0 8px 20px rgba(15,23,42,.05)}
    .stats-card{padding:20px;border-radius:16px;text-align:center}
    .stats-card h3{margin:0;color:#2563eb;font-weight:700}
    .stats-card span{color:#64748b;font-size:14px}
    .table-card{border-radius:20px;padding:25px}
    .teacher-avatar{width:45px;height:45px;border-radius:50%;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}
    .assignment-chain{display:flex;align-items:center;gap:8px;color:#64748b}
    .assignment-chain strong{color:#0f172a}
    .chain-arrow{color:#94a3b8}
    .btn-action{width:38px;height:38px;border:0;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;margin-right:5px;transition:.3s}
    .btn-view{background:#dbeafe;color:#2563eb}.btn-view:hover{background:#2563eb;color:#fff}
    .btn-edit{background:#fef3c7;color:#d97706}.btn-edit:hover{background:#d97706;color:#fff}
    .btn-delete{background:#fee2e2;color:#dc2626}.btn-delete:hover{background:#dc2626;color:#fff}
    .empty-state{padding:50px;text-align:center;color:#64748b}.empty-state i{font-size:50px;margin-bottom:15px;display:block;color:#cbd5e1}
</style>

<div class="assignment-list-header">
    <div>
        <h2>Teacher Subject Assignment</h2>
        <p class="mb-0 opacity-75">Map teachers to class-wise subjects.</p>
    </div>
    <a href="{{ route('teacher-subjects.create') }}" class="btn btn-light">
        <i class="bi bi-plus-circle"></i> Assign Teacher
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <h3 id="totalAssignmentsCount">{{ $assignments->count() }}</h3>
            <span>Total Assignments</span>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h5 class="mb-0">Assignments Directory</h5>
        <form action="{{ route('teacher-subjects.index') }}" method="GET" class="d-flex gap-2" role="search">
            <input type="search"
                   name="search"
                   value="{{ $search ?? '' }}"
                   class="form-control"
                   placeholder="Search assignments..."
                   style="min-width:260px">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
                Search
            </button>
            @if(!empty($search))
                <a href="{{ route('teacher-subjects.index') }}" class="btn btn-light border">Clear</a>
            @endif
        </form>
        <div class="text-muted" id="recordsFoundCount">{{ $assignments->count() }} Records Found</div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Teacher</th>
                    <th>Assignment</th>
                    <th>Created</th>
                    <th width="170">Actions</th>
                </tr>
            </thead>
            <tbody id="assignmentsTableBody">
            @forelse($assignments as $assignment)
                <tr id="assignment-row-{{ $assignment->id }}">
                    <td class="assignment-row-number">{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="teacher-avatar">{{ strtoupper(substr($assignment->teacher?->name ?? 'T',0,1)) }}</div>
                            <div>
                                <strong>{{ $assignment->teacher?->name ?? 'N/A' }}</strong>
                                <div class="small text-muted">{{ $assignment->teacher?->email ?? 'No email' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="assignment-chain">
                            <strong>{{ $assignment->subject?->name ?? 'N/A' }}</strong>
                            <i class="bi bi-arrow-right chain-arrow"></i>
                            <span>{{ $assignment->schoolClass?->name }}{{ $assignment->schoolClass?->section ? ' - '.$assignment->schoolClass->section : '' }}</span>
                        </div>
                        <div class="small text-muted">{{ $assignment->subject?->code ?? 'N/A' }}</div>
                    </td>
                    <td>{{ $assignment->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('teacher-subjects.show', $assignment->id) }}" class="btn-action btn-view" title="View"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('teacher-subjects.edit', $assignment->id) }}" class="btn-action btn-edit" title="Edit"><i class="bi bi-pencil-square"></i></a>
                        <form id="delete-form-{{ $assignment->id }}" action="{{ route('teacher-subjects.destroy', $assignment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-action btn-delete" title="Delete" onclick="confirmDelete('{{ $assignment->id }}','{{ addslashes($assignment->teacher?->name ?? 'Teacher') }}','{{ addslashes($assignment->subject?->name ?? 'Subject') }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-diagram-3"></i>
                            <h5>No Assignment Found</h5>
                            <p>Start by assigning a teacher to a class subject.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete(id, teacherName, subjectName)
{
    Swal.fire({
        title:'Delete Assignment?',
        html:`<div style="padding:10px"><div style="width:80px;height:80px;margin:auto;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center"><i class="bi bi-trash-fill" style="font-size:35px;color:#dc2626"></i></div><div style="margin-top:20px"><p class="text-muted">You are about to remove</p><h4 style="color:#2563eb;font-weight:700">${teacherName}</h4><p class="mb-1">from <strong>${subjectName}</strong></p><small style="color:#dc2626;font-weight:600">This action cannot be undone.</small></div></div>`,
        showCancelButton:true,
        confirmButtonColor:'#dc2626',
        cancelButtonColor:'#64748b',
        confirmButtonText:'Delete Assignment',
        cancelButtonText:'Cancel',
        reverseButtons:true,
        customClass:{popup:'rounded-4'}
    }).then((result) => {
        if(result.isConfirmed) {
            deleteAssignment(id);
        }
    });
}

function deleteAssignment(id)
{
    const form = document.getElementById('delete-form-' + id);
    const button = form.querySelector('.btn-delete');
    const formData = new FormData(form);

    button.disabled = true;

    fetch(form.action, {method:'POST', body:formData, headers:{'Accept':'application/json'}})
        .then((response) => response.json())
        .then((data) => {
            if (!data.success) {
                throw new Error(data.message || 'Unable to delete assignment.');
            }

            document.getElementById('assignment-row-' + id).remove();
            updateAssignmentCounts();

            Swal.fire({icon:'success', title:'Deleted', text:data.message, timer:1600, showConfirmButton:false});
        })
        .catch((error) => {
            button.disabled = false;
            Swal.fire({icon:'error', title:'Delete failed', text:error.message});
        });
}

function updateAssignmentCounts()
{
    const rows = document.querySelectorAll('#assignmentsTableBody tr[id^="assignment-row-"]');
    const total = rows.length;

    rows.forEach((row, index) => {
        row.querySelector('.assignment-row-number').textContent = index + 1;
    });

    document.getElementById('totalAssignmentsCount').textContent = total;
    document.getElementById('recordsFoundCount').textContent = total + ' Records Found';
}
</script>
@endsection

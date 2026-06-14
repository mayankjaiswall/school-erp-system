@extends('layouts.principal')

@section('title', 'Subjects')
@section('page-title', 'Subject Management')

@section('content')
<style>
    .subject-list-header{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 15px 35px rgba(37,99,235,.25)}
    .subject-list-header h2{margin:0;font-weight:700}
    .stats-card,.table-card{background:#fff;border:1px solid #e2e8f0;box-shadow:0 8px 20px rgba(15,23,42,.05)}
    .stats-card{padding:20px;border-radius:16px;text-align:center}
    .stats-card h3{margin:0;color:#2563eb;font-weight:700}
    .stats-card span{color:#64748b;font-size:14px}
    .table-card{border-radius:20px;padding:25px}
    .subject-avatar{width:45px;height:45px;border-radius:50%;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}
    .status-pill{padding:6px 14px;border-radius:30px;font-size:13px;font-weight:600}
    .status-pill.active{background:#dcfce7;color:#166534}
    .status-pill.inactive{background:#fee2e2;color:#991b1b}
    .btn-action{width:38px;height:38px;border:0;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;margin-right:5px;transition:.3s}
    .btn-view{background:#dbeafe;color:#2563eb}.btn-view:hover{background:#2563eb;color:#fff}
    .btn-edit{background:#fef3c7;color:#d97706}.btn-edit:hover{background:#d97706;color:#fff}
    .btn-delete{background:#fee2e2;color:#dc2626}.btn-delete:hover{background:#dc2626;color:#fff}
    .empty-state{padding:50px;text-align:center;color:#64748b}.empty-state i{font-size:50px;margin-bottom:15px;display:block;color:#cbd5e1}
</style>

<div class="subject-list-header">
    <div>
        <h2>Subject Management</h2>
        <p class="mb-0 opacity-75">Manage subjects class-wise from one dashboard.</p>
    </div>
    <a href="{{ route('subjects.create') }}" class="btn btn-light">
        <i class="bi bi-plus-circle"></i> Add Subject
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <h3 id="totalSubjectsCount">{{ $subjects->count() }}</h3>
            <span>Total Subjects</span>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h5 class="mb-0">Subjects Directory</h5>
        <form action="{{ route('subjects.index') }}" method="GET" class="d-flex gap-2" role="search">
            <input type="search"
                   name="search"
                   value="{{ $search ?? '' }}"
                   class="form-control"
                   placeholder="Search subjects..."
                   style="min-width:260px">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
                Search
            </button>
            @if(!empty($search))
                <a href="{{ route('subjects.index') }}" class="btn btn-light border">Clear</a>
            @endif
        </form>
        <div class="text-muted" id="recordsFoundCount">{{ $subjects->count() }} Records Found</div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Class</th>
                    <th>Code</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th width="170">Actions</th>
                </tr>
            </thead>
            <tbody id="subjectsTableBody">
            @forelse($subjects as $subject)
                <tr id="subject-row-{{ $subject->id }}">
                    <td class="subject-row-number">{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="subject-avatar">{{ strtoupper(substr($subject->name,0,1)) }}</div>
                            <div>
                                <strong>{{ $subject->name }}</strong>
                                <div class="small text-muted">{{ $subject->description ? Str::limit($subject->description, 45) : 'No description' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $subject->class?->name }}{{ $subject->class?->section ? ' - '.$subject->class->section : '' }}</td>
                    <td>{{ $subject->code }}</td>
                    <td><span class="status-pill {{ $subject->status ? 'active' : 'inactive' }}">{{ $subject->status ? 'Active' : 'Inactive' }}</span></td>
                    <td>{{ $subject->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('subjects.show', $subject->id) }}" class="btn-action btn-view" title="View"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('subjects.edit', $subject->id) }}" class="btn-action btn-edit" title="Edit"><i class="bi bi-pencil-square"></i></a>
                        <form id="delete-form-{{ $subject->id }}" action="{{ route('subjects.destroy', $subject->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-action btn-delete" title="Delete" onclick="confirmDelete('{{ $subject->id }}','{{ addslashes($subject->name) }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-book"></i>
                            <h5>No Subject Found</h5>
                            <p>Start by creating your first subject.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete(id, subjectName)
{
    Swal.fire({
        title:'Delete Subject?',
        html:`<div style="padding:10px"><div style="width:80px;height:80px;margin:auto;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center"><i class="bi bi-trash-fill" style="font-size:35px;color:#dc2626"></i></div><div style="margin-top:20px"><p class="text-muted">You are about to delete</p><h4 style="color:#2563eb;font-weight:700">${subjectName}</h4><small style="color:#dc2626;font-weight:600">This action cannot be undone.</small></div></div>`,
        showCancelButton:true,
        confirmButtonColor:'#dc2626',
        cancelButtonColor:'#64748b',
        confirmButtonText:'Delete Subject',
        cancelButtonText:'Cancel',
        reverseButtons:true,
        customClass:{popup:'rounded-4'}
    }).then((result) => {
        if(result.isConfirmed) {
            deleteSubject(id);
        }
    });
}

function deleteSubject(id)
{
    const form = document.getElementById('delete-form-' + id);
    const button = form.querySelector('.btn-delete');
    const formData = new FormData(form);

    button.disabled = true;

    fetch(form.action, {method:'POST', body:formData, headers:{'Accept':'application/json'}})
        .then((response) => response.json())
        .then((data) => {
            if (!data.success) {
                throw new Error(data.message || 'Unable to delete subject.');
            }

            document.getElementById('subject-row-' + id).remove();
            updateSubjectCounts();

            Swal.fire({icon:'success', title:'Deleted', text:data.message, timer:1600, showConfirmButton:false});
        })
        .catch((error) => {
            button.disabled = false;
            Swal.fire({icon:'error', title:'Delete failed', text:error.message});
        });
}

function updateSubjectCounts()
{
    const rows = document.querySelectorAll('#subjectsTableBody tr[id^="subject-row-"]');
    const total = rows.length;

    rows.forEach((row, index) => {
        row.querySelector('.subject-row-number').textContent = index + 1;
    });

    document.getElementById('totalSubjectsCount').textContent = total;
    document.getElementById('recordsFoundCount').textContent = total + ' Records Found';
}
</script>
@endsection

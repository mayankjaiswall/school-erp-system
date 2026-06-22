@extends('layouts.principal')

@section('title', 'Students')
@section('page-title', 'Student Management')

@section('content')
<style>
    .student-list-header{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 15px 35px rgba(37,99,235,.25)}
    .student-list-header h2{margin:0;font-weight:700}
    .stats-card,.table-card{background:#fff;border:1px solid #e2e8f0;box-shadow:0 8px 20px rgba(15,23,42,.05)}
    .stats-card{padding:20px;border-radius:16px;text-align:center}
    .stats-card h3{margin:0;color:#2563eb;font-weight:700}
    .stats-card span{color:#64748b;font-size:14px}
    .table-card{border-radius:20px;padding:25px}
    .student-avatar{width:45px;height:45px;border-radius:50%;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}
    .status-pill{padding:6px 14px;border-radius:30px;font-size:13px;font-weight:600}
    .status-pill.active{background:#dcfce7;color:#166534}
    .status-pill.inactive{background:#fee2e2;color:#991b1b}
    .btn-action{width:38px;height:38px;border:0;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;margin-right:5px;transition:.3s}
    .btn-view{background:#dbeafe;color:#2563eb}.btn-view:hover{background:#2563eb;color:#fff}
    .btn-edit{background:#fef3c7;color:#d97706}.btn-edit:hover{background:#d97706;color:#fff}
    .btn-delete{background:#fee2e2;color:#dc2626}.btn-delete:hover{background:#dc2626;color:#fff}
    .empty-state{padding:50px;text-align:center;color:#64748b}.empty-state i{font-size:50px;margin-bottom:15px;display:block;color:#cbd5e1}
    .student-header-actions{display:flex;gap:12px;align-items:center;flex-wrap:wrap}
    .student-table-tools{display:flex;gap:12px;align-items:center;flex-wrap:nowrap}
    .student-table-tools form[role="search"]{max-width:none;width:auto;margin:0}
    .student-import-btn{border:2px solid #16a34a;color:#fff;background:#16a34a;font-weight:600;min-width:130px}
    .student-import-btn:hover{background:#15803d;border-color:#15803d;color:#fff}
    .import-help{background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;color:#475569;font-size:14px}
    @media(max-width:576px){.student-table-tools{width:100%;flex-wrap:wrap}.student-table-tools form[role="search"],.student-table-tools input[type="search"]{width:100%;min-width:0!important}.student-import-btn{width:100%}}
</style>

<div class="student-list-header">
    <div>
        <h2>Student Management</h2>
        <p class="mb-0 opacity-75">Manage all enrolled students from one dashboard.</p>
    </div>
    <div class="student-header-actions">
        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#studentImportModal">
            <i class="bi bi-upload"></i> Import
        </button>
        <a href="{{ route('students.create') }}" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Add Student
        </a>
    </div>
</div>

@if(session('success') || session('error'))
    <div class="alert alert-{{ session('success') ? 'success' : 'danger' }}">
        {{ session('success') ?? session('error') }}
        @if(session('import_skipped_count'))
            <div class="mt-2 small">
                {{ session('import_skipped_count') }} row{{ session('import_skipped_count') === 1 ? '' : 's' }} skipped.
                @foreach(session('import_skipped', []) as $skipped)
                    <div>{{ $skipped }}</div>
                @endforeach
            </div>
        @endif
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <h3 id="totalStudentsCount">{{ $students->count() }}</h3>
            <span>Total Students</span>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h5 class="mb-0">Students <span id="recordsFoundCount">{{ $students->count() }}</span></h5>
        <div class="student-table-tools">
            <button type="button" class="btn student-import-btn" data-bs-toggle="modal" data-bs-target="#studentImportModal">
                <i class="bi bi-upload"></i> Import
            </button>
            <form action="{{ route('students.index') }}" method="GET" class="d-flex gap-2" role="search">
                <input type="search"
                       name="search"
                       value="{{ $search ?? '' }}"
                       class="form-control"
                       placeholder="Search students..."
                       style="min-width:260px">
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Roll No</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th width="170">Actions</th>
                </tr>
            </thead>
            <tbody id="studentsTableBody">
            @forelse($students as $student)
                <tr id="student-row-{{ $student->id }}">
                    <td class="student-row-number">{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="student-avatar">{{ strtoupper(substr($student->name,0,1)) }}</div>
                            <div>
                                <strong>{{ $student->name }}</strong>
                                <div class="small text-muted">{{ $student->admission_no }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $student->class?->name }}{{ $student->class?->section ? ' - '.$student->class->section : '' }}</td>
                    <td>{{ $student->roll_no ?? 'N/A' }}</td>
                    <td><span class="status-pill {{ $student->status ? 'active' : 'inactive' }}">{{ $student->status ? 'Active' : 'Inactive' }}</span></td>
                    <td>{{ $student->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('students.show', $student->id) }}" class="btn-action btn-view" title="View"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('students.edit', $student->id) }}" class="btn-action btn-edit" title="Edit"><i class="bi bi-pencil-square"></i></a>
                        <form id="delete-form-{{ $student->id }}" action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-action btn-delete" title="Delete" onclick="confirmDelete('{{ $student->id }}','{{ addslashes($student->name) }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-mortarboard"></i>
                            <h5>No Student Found</h5>
                            <p>Start by creating your first student.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="studentImportModal" tabindex="-1" aria-labelledby="studentImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="studentImportModalLabel">Import Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <a href="{{ route('students.import-template') }}" class="btn btn-outline-success w-100 mb-3">
                    <i class="bi bi-download"></i> Download Import Template
                </a>
                <label for="students_file" class="form-label fw-semibold">Excel or CSV file</label>
                <input type="file" name="students_file" id="students_file" class="form-control" accept=".xlsx,.csv,.txt" required>
                <div class="import-help mt-3">
                    Required columns: name, admission_no, class or class_id. Optional columns: section, roll_no, email, phone, gender, dob, address, status.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Import Students</button>
            </div>
        </form>
    </div>
</div>

<script>
function confirmDelete(id, studentName)
{
    Swal.fire({
        title:'Delete Student?',
        html:`<div style="padding:10px"><div style="width:80px;height:80px;margin:auto;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center"><i class="bi bi-trash-fill" style="font-size:35px;color:#dc2626"></i></div><div style="margin-top:20px"><p class="text-muted">You are about to delete</p><h4 style="color:#2563eb;font-weight:700">${studentName}</h4><small style="color:#dc2626;font-weight:600">This action cannot be undone.</small></div></div>`,
        showCancelButton:true,
        confirmButtonColor:'#dc2626',
        cancelButtonColor:'#64748b',
        confirmButtonText:'Delete Student',
        cancelButtonText:'Cancel',
        reverseButtons:true,
        customClass:{popup:'rounded-4'}
    }).then((result) => {
        if(result.isConfirmed) {
            deleteStudent(id);
        }
    });
}

function deleteStudent(id)
{
    const form = document.getElementById('delete-form-' + id);
    const button = form.querySelector('.btn-delete');
    const formData = new FormData(form);

    button.disabled = true;

    fetch(form.action, {method:'POST', body:formData, headers:{'Accept':'application/json'}})
        .then((response) => response.json())
        .then((data) => {
            if (!data.success) {
                throw new Error(data.message || 'Unable to delete student.');
            }

            document.getElementById('student-row-' + id).remove();
            updateStudentCounts();

            Swal.fire({icon:'success', title:'Deleted', text:data.message, timer:1600, showConfirmButton:false});
        })
        .catch((error) => {
            button.disabled = false;
            Swal.fire({icon:'error', title:'Delete failed', text:error.message});
        });
}

function updateStudentCounts()
{
    const rows = document.querySelectorAll('#studentsTableBody tr[id^="student-row-"]');
    const total = rows.length;

    rows.forEach((row, index) => {
        row.querySelector('.student-row-number').textContent = index + 1;
    });

    document.getElementById('totalStudentsCount').textContent = total;
    document.getElementById('recordsFoundCount').textContent = total;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection

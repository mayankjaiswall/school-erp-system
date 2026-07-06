@extends('layouts.principal')

@section('title', 'Teachers')

@section('page-title', 'Teacher Management')

@section('content')

<style>
    /* Banner header removed — Import/Add buttons will be placed beside search for consistent layout */

    .stats-card{
        background: #fff;
        padding: 20px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 20px rgba(15,23,42,.05);
        text-align: center;
    }

    .stats-card h3{
        margin: 0;
        color: #2563eb;
        font-weight: 700;
    }

    .stats-card span{
        color: #64748b;
        font-size: 14px;
    }

    .table-card{
        background: #fff;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 8px 20px rgba(15,23,42,.05);
        border: 1px solid #e2e8f0;
    }

    .teacher-avatar{
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #2563eb;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .status-pill{
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-pill.active{
        background: #dcfce7;
        color: #166534;
    }

    .status-pill.inactive{
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-action{
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        margin-right: 5px;
        transition: .3s;
    }

    .btn-view{
        background: #dbeafe;
        color: #2563eb;
    }

    .btn-view:hover{
        background: #2563eb;
        color: #fff;
    }

    .btn-edit{
        background: #fef3c7;
        color: #d97706;
    }

    .btn-edit:hover{
        background: #d97706;
        color: #fff;
    }

    .btn-delete{
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover{
        background: #dc2626;
        color: #fff;
    }

    .table thead th{
        border: none;
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
    }

    .table tbody tr:hover{
        background: #f8fafc;
    }

    .empty-state{
        padding: 50px;
        text-align: center;
        color: #64748b;
    }

    .empty-state i{
        font-size: 50px;
        margin-bottom: 15px;
        display: block;
        color: #cbd5e1;
    }

    .teacher-table-tools{
        display:flex;
        gap:12px;
        align-items:center;
        flex-wrap:nowrap;
    }

    .teacher-table-tools form[role="search"]{
        max-width:none;
        width:auto;
        margin:0;
    }

    .teacher-import-btn{
        border:2px solid #16a34a;
        color:#fff;
        background:#16a34a;
        font-weight:600;
        min-width:130px;
    }

    .teacher-import-btn:hover{
        background:#15803d;
        border-color:#15803d;
        color:#fff;
    }

    .import-help{
        background:#f8fafc;
        border:1px solid #e2e8f0;
        border-radius:12px;
        padding:14px;
        color:#475569;
        font-size:14px;
    }

    @media(max-width:576px){
        .teacher-table-tools{width:100%;flex-wrap:wrap}
        .teacher-table-tools form[role="search"],
        .teacher-table-tools input[type="search"]{width:100%;min-width:0!important}
        .teacher-import-btn{width:100%}
    }
</style>

<!-- Header removed; Import/Add buttons moved beside search below -->

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

<!-- Stats -->

<div class="row mb-4">

    <div class="col-md-3">

        <div class="stats-card">

            <h3>{{ $teachers->count() }}</h3>

            <span>Total Teachers</span>

        </div>

    </div>

</div>

<!-- Table -->

<div class="table-card">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <h5 class="mb-0">
            Teachers  {{ $teachers->count() }}
        </h5>

        <div class="teacher-table-tools">
            <button type="button" class="btn teacher-import-btn" data-bs-toggle="modal" data-bs-target="#teacherImportModal">
                <i class="bi bi-upload"></i> Import
            </button>

            <a href="{{ route('teachers.create') }}" class="btn btn-light d-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                Add Teacher
            </a>

            <form action="{{ route('teachers.index') }}" method="GET" class="d-flex gap-2" role="search">
                <input type="search"
                       name="search"
                       value="{{ $search ?? '' }}"
                       class="form-control"
                       placeholder="Search teachers..."
                       style="min-width:260px">
            </form>
        </div>

    </div>

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>

                <tr>
                    <th>#</th>
                    <th>Teacher</th>
                    <th>Employee Code</th>
                    <th>Primary Subject</th>
                    <th>Qualification</th>
                    <th>Experience</th>
                    <th>Phone</th>
                    <th>Login</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th width="170">Actions</th>
                </tr>

            </thead>

            <tbody>

            @forelse($teachers as $teacher)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>

                        <div class="d-flex align-items-center gap-3">

                            <div class="teacher-avatar">
                                {{ strtoupper(substr($teacher->name,0,1)) }}
                            </div>

                            <div>

                                <strong>{{ $teacher->name }}</strong>

                                <div class="small text-muted">
                                    {{ $teacher->email }}
                                </div>

                            </div>

                        </div>

                    </td>

                    <td>{{ $teacher->employee_code ?? 'N/A' }}</td>

                    <td>{{ $teacher->primarySubject?->name ?? 'N/A' }}</td>

                    <td>{{ $teacher->qualification ?? 'N/A' }}</td>

                    <td>{{ is_null($teacher->display_experience) ? 'N/A' : $teacher->display_experience . ' Years' }}</td>

                    <td>{{ $teacher->phone }}</td>

                    <td>
                        @if($teacher->user_id)
                            <span class="status-pill active">
                                Linked
                            </span>
                        @else
                            <span class="status-pill inactive">
                                Not Linked
                            </span>
                        @endif
                    </td>

                    <td>

                        @if($teacher->status)

                            <span class="status-pill active">
                                Active
                            </span>

                        @else

                            <span class="status-pill inactive">
                                Inactive
                            </span>

                        @endif

                    </td>

                    <td>
                        {{ $teacher->created_at->format('d M Y') }}
                    </td>

                    <td>
                        <a href="{{ route('teachers.show', $teacher->id) }}"
                        class="btn-action btn-view"
                        title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('teachers.edit', $teacher->id) }}"
                        class="btn-action btn-edit"
                        title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form id="delete-form-{{ $teacher->id }}"
                            action="{{ route('teachers.destroy', $teacher->id) }}"
                            method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn-action btn-delete"
                                    title="Delete"
                                    onclick="confirmDelete(
                                        '{{ $teacher->id }}',
                                        '{{ addslashes($teacher->name) }}'
                                    )">

                                <i class="bi bi-trash"></i>

                            </button>

                        </form>

                    </td>

                </tr>

                <!-- Delete Modal -->

                <div class="modal fade"
                     id="deleteTeacherModal{{ $teacher->id }}"
                     tabindex="-1"
                     aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered">

                        <div class="modal-content border-0 shadow-lg">

                            <div class="modal-header bg-danger text-white">

                                <h5 class="modal-title">
                                    Confirm Deletion
                                </h5>

                                <button type="button"
                                        class="btn-close btn-close-white"
                                        data-bs-dismiss="modal">
                                </button>

                            </div>

                            <div class="modal-body text-center py-4">

                                <i class="bi bi-exclamation-triangle-fill text-danger"
                                   style="font-size:60px;">
                                </i>

                                <h4 class="mt-3">
                                    Delete Teacher?
                                </h4>

                                <p class="text-muted">
                                    You are about to delete
                                </p>

                                <h5 class="fw-bold text-primary">
                                    {{ $teacher->name }}
                                </h5>

                                <p class="text-danger mb-0 mt-3">
                                    This action cannot be undone.
                                </p>

                            </div>

                            <div class="modal-footer">

                                <button type="button"
                                        class="btn btn-light border"
                                        data-bs-dismiss="modal">

                                    Cancel

                                </button>

                                <form action="#"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger">

                                        <i class="bi bi-trash"></i>
                                        Delete Teacher

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <tr>

                    <td colspan="11">

                        <div class="empty-state">

                            <i class="bi bi-person"></i>

                            <h5>No Teacher Found</h5>

                            <p>
                                Start by creating your first teacher.
                            </p>

                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="modal fade" id="teacherImportModal" tabindex="-1" aria-labelledby="teacherImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('teachers.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="teacherImportModalLabel">Import Teachers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <a href="{{ route('teachers.import-template') }}" class="btn btn-outline-success w-100 mb-3">
                    <i class="bi bi-download"></i> Download Import Template
                </a>
                <label for="teachers_file" class="form-label fw-semibold">Excel or CSV file</label>
                <input type="file" name="teachers_file" id="teachers_file" class="form-control" accept=".xlsx,.csv,.txt" required>
                <div class="import-help mt-3">
                    Required columns: name, email, employee_code, primary_subject, qualification, experience_years, joining_date, designation, password. Optional columns: phone, gender, status.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success"><i class="bi bi-upload"></i> Import Teachers</button>
            </div>
        </form>
    </div>
</div>

<script>

function confirmDelete(id, schoolName)
{
    Swal.fire({

        title: 'Delete School?',

        html: `
            <div style="padding:10px">

                <div style="
                    width:80px;
                    height:80px;
                    margin:auto;
                    border-radius:50%;
                    background:#fee2e2;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                ">
                    <i class="bi bi-trash-fill"
                       style="font-size:35px;color:#dc2626">
                    </i>
                </div>

                <div style="margin-top:20px">

                    <p class="text-muted">
                        You are about to delete
                    </p>

                    <h4 style="
                        color:#2563eb;
                        font-weight:700;
                    ">
                        ${schoolName}
                    </h4>

                    <small style="
                        color:#dc2626;
                        font-weight:600;
                    ">
                        This action cannot be undone.
                    </small>

                </div>

            </div>
        `,

        showCancelButton: true,

        confirmButtonColor: '#dc2626',

        cancelButtonColor: '#64748b',

        confirmButtonText: 'Delete School',

        cancelButtonText: 'Cancel',

        reverseButtons: true,

        customClass: {
            popup: 'rounded-4'
        }

    }).then((result) => {

        if(result.isConfirmed)
        {
            document
                .getElementById('delete-form-' + id)
                .submit();
        }

    });
}

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection

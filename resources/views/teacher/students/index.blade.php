@extends('layouts.teacher')

@section('title', 'My Students')
@section('page-title', 'My Students')

@section('content')
<style>
    .student-list-header{background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 15px 35px rgba(22,163,74,.22)}
    .student-list-header h2{margin:0;font-weight:700}
    .stats-card,.table-card{background:#fff;border:1px solid #e2e8f0;box-shadow:0 8px 20px rgba(15,23,42,.05)}
    .stats-card{padding:20px;border-radius:16px;text-align:center}
    .stats-card h3{margin:0;color:#16a34a;font-weight:700}
    .stats-card span{color:#64748b;font-size:14px}
    .table-card{border-radius:20px;padding:25px}
    .student-avatar{width:45px;height:45px;border-radius:50%;background:#16a34a;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}
    .status-pill{padding:6px 14px;border-radius:30px;font-size:13px;font-weight:600}
    .status-pill.active{background:#dcfce7;color:#166534}
    .status-pill.inactive{background:#fee2e2;color:#991b1b}
    .empty-state{padding:50px;text-align:center;color:#64748b}.empty-state i{font-size:50px;margin-bottom:15px;display:block;color:#cbd5e1}
    .student-table-tools{display:flex;gap:12px;align-items:center;flex-wrap:nowrap}
    .student-table-tools form[role="search"]{max-width:none;width:auto;margin:0}
    .student-table-tools input[type="search"]{border:2px solid #16a34a;border-radius:6px;min-width:260px}
    .student-import-btn{border:2px solid #16a34a;color:#fff;background:#16a34a;font-weight:600;min-width:130px}
    .student-import-btn:hover{background:#15803d;border-color:#15803d;color:#fff}
    .import-help{background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;color:#475569;font-size:14px}
    @media(max-width:576px){.student-table-tools{width:100%;flex-wrap:wrap}.student-table-tools form[role="search"],.student-table-tools input[type="search"]{width:100%;min-width:0!important}.student-import-btn{width:100%}}
</style>

<div class="student-list-header">
    <div>
        <h2>My Students</h2>
        <p class="mb-0 opacity-75">View and import students only for your assigned classes.</p>
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
            <h3>{{ $students->count() }}</h3>
            <span>Total Students</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ $classes->count() }}</h3>
            <span>Assigned Classes</span>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h5 class="mb-0">Students {{ $students->count() }}</h5>
        <div class="student-table-tools">
            <button type="button" class="btn student-import-btn" data-bs-toggle="modal" data-bs-target="#studentImportModal">
                <i class="bi bi-upload"></i> Import
            </button>
            <form action="{{ route('teacher.students.index') }}" method="GET" class="d-flex gap-2" role="search">
                <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Search students...">
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
                </tr>
            </thead>
            <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
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
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-mortarboard"></i>
                            <h5>No Student Found</h5>
                            <p>Import students for your assigned classes.</p>
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
        <form action="{{ route('teacher.students.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="studentImportModalLabel">Import Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <a href="{{ route('teacher.students.import-template') }}" class="btn btn-outline-success w-100 mb-3">
                    <i class="bi bi-download"></i> Download Import Template
                </a>
                <label for="students_file" class="form-label fw-semibold">Excel or CSV file</label>
                <input type="file" name="students_file" id="students_file" class="form-control" accept=".xlsx,.csv,.txt" required>
                <div class="import-help mt-3">
                    Use only your assigned classes. Required columns: name, admission_no, class or class_id. Optional columns: section, roll_no, email, phone, gender, dob, address, status.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success"><i class="bi bi-upload"></i> Import Students</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@extends('layouts.principal')

@section('title', 'Parents')
@section('page-title', 'Parent Management')

@section('content')
<style>
    .table-card{background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:25px;box-shadow:0 8px 20px rgba(15,23,42,.05)}
    .avatar{width:45px;height:45px;border-radius:50%;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}.btn-action{width:38px;height:38px;border:0;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;margin-right:5px}.btn-view{background:#dbeafe;color:#2563eb}.btn-edit{background:#fef3c7;color:#d97706}.btn-delete{background:#fee2e2;color:#dc2626}
    .table-subtitle{color:#64748b;font-size:14px;margin:6px 0 0}
</style>
<div class="table-card">
    <div class="index-toolbar-row mb-4">
        <div>
            <h5 class="mb-0">Parents Directory</h5>
            <p class="table-subtitle">Create parent logins and link children.</p>
        </div>
        <div class="index-toolbar-actions">
            <form action="{{ route('principal.parents.index') }}" method="GET" class="d-flex gap-2 mb-0" role="search">
                <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Search parents..." style="min-width:260px">
            </form>
            <a href="{{ route('principal.parents.create') }}" class="btn-add-record">
                <i class="bi bi-plus-lg"></i>
                Add Parent
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>#</th><th>Parent Name</th><th>Phone</th><th>Email</th><th>Number of Children</th><th>Status</th><th width="170">Actions</th></tr></thead>
            <tbody id="parentsTableBody">
                @forelse($parents as $parent)
                    <tr id="parent-row-{{ $parent->id }}">
                        <td class="row-number">{{ $loop->iteration }}</td>
                        <td><div class="d-flex align-items-center gap-3"><div class="avatar">{{ strtoupper(substr($parent->user?->name ?? 'P', 0, 1)) }}</div><div><strong>{{ $parent->user?->name }}</strong><div class="small text-muted">{{ $parent->father_name ? 'Father: '.$parent->father_name : '' }} {{ $parent->mother_name ? 'Mother: '.$parent->mother_name : '' }}</div></div></div></td>
                        <td>{{ $parent->phone }}</td>
                        <td>{{ $parent->email ?? 'No email' }}</td>
                        <td><span class="badge bg-primary">{{ $parent->students->count() }}</span></td>
                        <td><span class="badge {{ $parent->status ? 'bg-success' : 'bg-danger' }}">{{ $parent->status ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <a href="{{ route('principal.parents.show', $parent->id) }}" class="btn-action btn-view" title="View"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('principal.parents.edit', $parent->id) }}" class="btn-action btn-edit" title="Edit"><i class="bi bi-pencil-square"></i></a>
                            <form id="delete-form-{{ $parent->id }}" action="{{ route('principal.parents.destroy', $parent->id) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button type="button" class="btn-action btn-delete" onclick="confirmDelete('{{ $parent->id }}','{{ addslashes($parent->user?->name ?? 'Parent') }}')"><i class="bi bi-trash"></i></button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">No parents found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script>
function confirmDelete(id, parentName) {
    Swal.fire({title:'Delete Parent?', text:`Delete ${parentName} and linked login access?`, icon:'warning', showCancelButton:true, confirmButtonColor:'#dc2626', confirmButtonText:'Delete'})
        .then((result) => { if (result.isConfirmed) deleteParent(id); });
}
function deleteParent(id) {
    const form = document.getElementById('delete-form-' + id);
    fetch(form.action, {method:'POST', body:new FormData(form), headers:{'Accept':'application/json'}})
        .then((response) => response.json())
        .then((data) => {
            if (!data.success) throw new Error(data.message || 'Unable to delete parent.');
            document.getElementById('parent-row-' + id).remove();
            document.querySelectorAll('.row-number').forEach((cell, index) => cell.textContent = index + 1);
            Swal.fire({icon:'success', title:'Deleted', text:data.message, timer:1400, showConfirmButton:false});
        })
        .catch((error) => Swal.fire({icon:'error', title:'Delete failed', text:error.message}));
}
</script>
@endsection

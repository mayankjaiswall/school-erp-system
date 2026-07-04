@extends('layouts.admin')

@section('title', 'Roles Management')

@section('page-title', 'Roles Management')

@section('content')

<style>
    /* Banner header removed — button moved inline with search for consistent layout */

    .stats-card{
        background:#fff;
        padding:20px;
        border-radius:16px;
        border:1px solid #e2e8f0;
        box-shadow:0 8px 20px rgba(15,23,42,.05);
        text-align:center;
    }

    .stats-card h3{
        margin:0;
        color:#2563eb;
        font-weight:700;
    }

    .stats-card span{
        color:#64748b;
        font-size:14px;
    }

    .table-card{
        background:#fff;
        border-radius:20px;
        padding:25px;
        box-shadow:0 8px 20px rgba(15,23,42,.05);
        border:1px solid #e2e8f0;
    }

    .role-avatar{
        width:45px;
        height:45px;
        border-radius:50%;
        background:#2563eb;
        color:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:700;
        font-size:16px;
    }

    .status-pill{
        padding:6px 14px;
        border-radius:30px;
        font-size:13px;
        font-weight:600;
        background:#dcfce7;
        color:#166534;
    }

    .btn-action{
        width:38px;
        height:38px;
        border:none;
        border-radius:10px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        text-decoration:none;
        margin-right:5px;
        transition:.3s;
    }

    .btn-view{
        background:#dbeafe;
        color:#2563eb;
    }

    .btn-view:hover{
        background:#2563eb;
        color:#fff;
    }

    .btn-edit{
        background:#fef3c7;
        color:#d97706;
    }

    .btn-edit:hover{
        background:#d97706;
        color:#fff;
    }

    .btn-delete{
        background:#fee2e2;
        color:#dc2626;
    }

    .btn-delete:hover{
        background:#dc2626;
        color:#fff;
    }

    .table thead th{
        background:#f8fafc;
        color:#475569;
        font-weight:600;
        border:none;
        padding:16px;
        vertical-align:middle;
    }

    .table tbody td{
        padding:16px;
        vertical-align:middle;
    }

    .table tbody tr:hover{
        background:#f8fafc;
    }

    .empty-state{
        padding:50px;
        text-align:center;
        color:#64748b;
    }

    .empty-state i{
        font-size:50px;
        margin-bottom:15px;
        display:block;
        color:#cbd5e1;
    }
</style>

<!-- Header removed; Add button moved beside search below -->

<!-- Stats -->

<div class="row mb-4">

    <div class="col-md-3">

        <div class="stats-card">

            <h3>{{ $roles->count() }}</h3>

            <span>Total Roles</span>

        </div>

    </div>

</div>

<!-- Table -->

<div class="table-card">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <h5 class="mb-0">
            Roles Directory
        </h5>

        <div class="d-flex align-items-center gap-2">

            <form action="{{ route('roles.index') }}" method="GET" class="d-flex gap-2 mb-0" role="search">
                <input type="search"
                       name="search"
                       value="{{ $search ?? '' }}"
                       class="form-control"
                       placeholder="Search roles..."
                       style="min-width:260px">
            </form>

            <a href="{{ route('roles.create') }}"
               class="btn btn-light d-flex align-items-center gap-1">

                <i class="bi bi-plus-circle"></i>
                Add Role

            </a>

        </div>

        <div class="text-muted">
            {{ $roles->count() }} Records Found
        </div>

    </div>

    <div class="table-responsive">

        <table class="table align-middle">

            <thead class="table-light">

                <tr>
                    <th width="60">#</th>
                    <th>Role</th>
                    <th>Slug</th>
                    <th>Created</th>
                    <th class="text-center" width="180">
                        Actions
                    </th>
                </tr>

            </thead>

            <tbody>

            @forelse($roles as $role)

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>

                    <td>

                        <div class="d-flex align-items-center gap-3">

                            <div>

                                <strong>
                                    {{ $role->name }}
                                </strong>

                            </div>

                        </div>

                    </td>

                    <td>

                        <span class="status-pill">
                            {{ $role->slug }}
                        </span>

                    </td>

                    <td>

                        {{ $role->created_at->format('d M Y') }}

                    </td>

                    <td class="text-center">

                        <a href="{{ route('roles.show', $role->id) }}"
                           class="btn-action btn-view"
                           title="View">

                            <i class="bi bi-eye"></i>

                        </a>

                        <a href="{{ route('roles.edit', $role->id) }}"
                           class="btn-action btn-edit"
                           title="Edit">

                            <i class="bi bi-pencil-square"></i>

                        </a>

                        <form id="delete-form-{{ $role->id }}"
                              action="{{ route('roles.destroy', $role->id) }}"
                              method="POST"
                              class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button type="button"
                                    class="btn-action btn-delete"
                                    title="Delete"
                                    onclick="confirmDelete('{{ $role->id }}','{{ addslashes($role->name) }}')">

                                <i class="bi bi-trash"></i>

                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5">

                        <div class="empty-state">

                            <i class="bi bi-shield-lock"></i>

                            <h5>No Roles Found</h5>

                            <p>
                                Start by creating your first role.
                            </p>

                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>

function confirmDelete(id, roleName)
{
    Swal.fire({
        title: 'Delete Role?',
        html: `
            <div style="padding:15px">

                <div style="
                    width:80px;
                    height:80px;
                    border-radius:50%;
                    background:#fee2e2;
                    margin:auto;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                ">
                    <i class="bi bi-trash-fill"
                       style="font-size:35px;color:#dc2626">
                    </i>
                </div>

                <div class="mt-4">

                    <p class="text-muted mb-2">
                        You are about to delete
                    </p>

                    <h4 style="
                        color:#2563eb;
                        font-weight:700;
                    ">
                        ${roleName}
                    </h4>

                    <small style="
                        color:#dc2626;
                        font-weight:600;
                    ">
                        This action cannot be undone
                    </small>

                </div>

            </div>
        `,

        showCancelButton:true,
        confirmButtonText:'Delete Role',
        cancelButtonText:'Cancel',
        confirmButtonColor:'#dc2626',
        cancelButtonColor:'#64748b',
        reverseButtons:true
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

@endsection

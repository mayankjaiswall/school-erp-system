@extends('layouts.admin')

@section('title', 'Users Management')

@section('page-title', 'Users Management')

@section('content')

<style>
    .user-list-header{
        background: linear-gradient(135deg,#2563eb,#1d4ed8);
        color: #fff;
        padding: 30px;
        border-radius: 20px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 15px 35px rgba(37,99,235,.25);
    }

    .user-list-header h2{
        margin: 0;
        font-weight: 700;
    }

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

    .school-avatar{
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
</style>
<!-- Header -->
<div class="user-list-header">
    <div>
        <h2>Users Management</h2>
        <p class="mb-0 opacity-75">
            Manage all registered users from one dashboard.
        </p>
    </div>
    <a href="{{ route('users.create') }}"
       class="btn btn-light">
        <i class="bi bi-plus-circle"></i>
        Add User
    </a>
</div>
<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <h3>{{ $users->count() }}</h3>
            <span>Total Users</span>
        </div>
    </div>
</div>
<!-- Table -->
<div class="table-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h5 class="mb-0">
            Users Directory
        </h5>
        <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2" role="search">
            <input type="search"
                   name="search"
                   value="{{ $search ?? '' }}"
                   class="form-control"
                   placeholder="Search users..."
                   style="min-width:260px">
        </form>
        <div class="text-muted">
            {{ $users->count() }} Records Found
        </div>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th width="170">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <strong>{{ $user->name }}</strong>
                                <div class="small text-muted">
                                    {{ $user->email }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>
                        @if($user->status)
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
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}"
                        class="btn-action btn-view"
                        title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('users.edit', $user->id) }}"
                        class="btn-action btn-edit"
                        title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form id="delete-form-{{ $user->id }}"
                            action="{{ route('users.destroy', $user->id) }}"
                            method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn-action btn-delete"
                                    title="Delete"
                                    onclick="confirmDelete(
                                        '{{ $user->id }}',
                                        '{{ addslashes($user->name) }}'
                                    )">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <!-- Delete Modal -->
                <div class="modal fade"
                     id="deleteUserModal{{ $user->id }}"
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
                                    Delete User?
                                </h4>
                                <p class="text-muted">
                                    You are about to delete
                                </p>
                                <h5 class="fw-bold text-primary">
                                    {{ $user->name }}
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
                                <form action="{{ route('users.destroy', $user->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger">
                                        <i class="bi bi-trash"></i>
                                        Delete User
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-person"></i>
                            <h5>No Users Found</h5>
                            <p>
                                Start by creating your first user.
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

function confirmDelete(id, userName)
{
    Swal.fire({

        title: 'Delete User?',

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
                        ${userName}
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

        confirmButtonText: 'Delete User',

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
@endsection

@extends('layouts.principal')

@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
<style>
    .users-header {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        padding: 28px;
        border-radius: 18px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 15px 35px rgba(37, 99, 235, .22);
    }

    .users-header h2 {
        margin: 0;
        font-weight: 700;
    }

    .table-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .05);
    }

    .role-badge {
        background: #e0f2fe;
        color: #0369a1;
        border-radius: 999px;
        padding: 7px 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-pill {
        border-radius: 999px;
        padding: 7px 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-pill.active {
        background: #dcfce7;
        color: #166534;
    }

    .status-pill.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border: 0;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        margin-right: 4px;
    }

    .btn-view { background: #dbeafe; color: #2563eb; }
    .btn-edit { background: #fef3c7; color: #d97706; }
    .btn-delete { background: #fee2e2; color: #dc2626; }

    .empty-state {
        padding: 44px;
        text-align: center;
        color: #64748b;
    }

    .empty-state i {
        display: block;
        color: #cbd5e1;
        font-size: 46px;
        margin-bottom: 12px;
    }
</style>

<div class="users-header">
    <div>
        <h2>Users Management</h2>
        <p class="mb-0 opacity-75">Manage users attached to your school.</p>
    </div>

    <a href="{{ route('principal.users.create') }}" class="btn btn-light">
        <i class="bi bi-plus-circle"></i>
        Add User
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h5 class="mb-0">School Users</h5>
        <form action="{{ route('principal.users.index') }}" method="GET" class="d-flex gap-2" role="search">
            <input type="search"
                   name="search"
                   value="{{ $search ?? '' }}"
                   class="form-control"
                   placeholder="Search users..."
                   style="min-width:260px">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
                Search
            </button>
            @if(!empty($search))
                <a href="{{ route('principal.users.index') }}" class="btn btn-light border">Clear</a>
            @endif
        </form>
        <span class="text-muted">{{ $users->count() }} Records Found</span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th width="160">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $user->name }}</strong>
                        </td>
                        <td>
                            <span class="role-badge">{{ $user->role->name ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="status-pill {{ $user->status ? 'active' : 'inactive' }}">
                                {{ $user->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('principal.users.show', $user->id) }}"
                               class="btn-action btn-view"
                               title="View">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if($user->role && in_array($user->role->slug, ['admin', 'hod', 'teacher', 'parent', 'student']))
                                <a href="{{ route('principal.users.edit', $user->id) }}"
                                   class="btn-action btn-edit"
                                   title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form id="delete-form-{{ $user->id }}"
                                      action="{{ route('principal.users.destroy', $user->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn-action btn-delete"
                                            title="Delete"
                                            onclick="confirmDelete('{{ $user->id }}', '{{ addslashes($user->name) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <h5>No Users Found</h5>
                                <p class="mb-0">Create your first school user.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete(id, userName) {
    Swal.fire({
        icon: 'warning',
        title: 'Delete User?',
        text: 'This will delete ' + userName + '.',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Delete User',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endsection

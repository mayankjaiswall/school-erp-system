@extends('layouts.principal')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<style>
    .form-page-header {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        padding: 28px;
        border-radius: 18px;
        margin-bottom: 24px;
        box-shadow: 0 15px 35px rgba(37, 99, 235, .22);
    }

    .form-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .05);
    }

    .form-label {
        color: #334155;
        font-weight: 600;
    }

    .form-control,
    .form-select {
        min-height: 48px;
        border-radius: 12px;
        border: 1px solid #dbe2ea;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2563eb;
        box-shadow: none;
    }

    .action-footer {
        border-top: 1px solid #e2e8f0;
        padding-top: 20px;
        margin-top: 12px;
    }
</style>

<div class="form-page-header">
    <h2 class="mb-2">Edit User</h2>
    <p class="mb-0 opacity-75">Update this school user account.</p>
</div>

<div class="form-card">
    <form action="{{ route('principal.users.update', $user->id) }}" method="POST" data-ajax-user-form>
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-4">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
            </div>

            <div class="col-md-6 mb-4">
                <label for="role_id" class="form-label">Role</label>
                <select name="role_id" id="role_id" class="form-select" required>
                    <option disabled value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>

            <div class="col-md-6 mb-4">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>

            <div class="col-md-6 mb-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="action-footer text-end">
            <a href="{{ route('principal.users.index') }}" class="btn btn-light border me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Update User</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-user-form.js') }}"></script>
@endpush

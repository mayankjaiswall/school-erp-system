@extends($layout)

@section('title', 'Reset Password')
@section('page-title', 'Reset Password')

@section('content')
<style>
    .password-header{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;padding:30px;border-radius:20px;margin-bottom:25px;box-shadow:0 15px 35px rgba(37,99,235,.25)}
    .password-card{background:#fff;border:1px solid #e2e8f0;border-radius:20px;box-shadow:0 8px 20px rgba(15,23,42,.05);max-width:760px;padding:28px}
    .form-label{color:#334155;font-weight:700;margin-bottom:8px}
    .form-control{border:1px solid #dbe2ea;border-radius:12px;min-height:48px}
    .form-control:focus{border-color:#2563eb;box-shadow:0 0 0 .2rem rgba(37,99,235,.12)}
</style>

<div class="password-header">
    <h2 class="mb-2">Reset Password</h2>
    <p class="mb-0 opacity-75">Use your current password before setting a new one.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="password-card">
    <form action="{{ route('account.password.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">New Password</label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" minlength="8" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" minlength="8" required>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-key"></i>
                Reset Password
            </button>
        </div>
    </form>
</div>
@endsection

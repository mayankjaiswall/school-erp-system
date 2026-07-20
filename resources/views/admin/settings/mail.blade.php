@extends('layouts.admin')

@section('title', 'SMTP Mail Settings')

@section('page-title', 'SMTP Mail Setup')
@section('page-subtitle', 'Outgoing email server configuration')

@section('content')

<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: var(--muted);
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 14px;
        transition: color .18s ease;
    }

    .back-link:hover { color: var(--primary); }

    .mail-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04), 0 8px 24px rgba(15, 23, 42, .04);
        overflow: hidden;
    }

    .mail-card-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 24px;
        border-bottom: 1px solid var(--line);
        background: #f8fafc;
    }

    .mail-card-header .icon-wrap {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: var(--radius);
        background: #dbeafe;
        color: #2563eb;
        font-size: 19px;
    }

    .mail-card-header h6 {
        margin: 0;
        font-weight: 700;
        font-size: 15px;
    }

    .mail-card-header p {
        margin: 2px 0 0;
        font-size: 12.5px;
        color: var(--muted);
    }

    .mail-card-body { padding: 24px; }

    .form-label {
        font-weight: 600;
        font-size: 13px;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-label .req { color: #dc2626; }

    .form-control, .form-select {
        border-radius: var(--radius);
        border-color: var(--line);
        font-size: 14px;
        padding: 9px 12px;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .12);
    }

    .form-hint {
        font-size: 12px;
        color: var(--muted);
        margin-top: 5px;
    }

    .password-group { position: relative; }

    .password-group .toggle-password {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: #94a3b8;
        cursor: pointer;
        padding: 0;
    }

    .section-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 26px 0 18px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #64748b;
    }

    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--line);
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 14px;
        padding: 10px 22px;
        box-shadow: 0 6px 16px rgba(37, 99, 235, .25);
        transition: background .18s ease;
    }

    .btn-save:hover { background: var(--primary-dark); color: #fff; }

    .test-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        padding: 22px 24px;
        margin-top: 20px;
    }

    .test-card h6 {
        font-weight: 700;
        font-size: 14.5px;
        margin-bottom: 4px;
    }

    .test-card p {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 14px;
    }

    .btn-test {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 14px;
        padding: 9px 18px;
        white-space: nowrap;
        transition: background .18s ease, border-color .18s ease;
    }

    .btn-test:hover { background: #e2e8f0; border-color: #cbd5e1; color: #0f172a; }

    .btn-test:disabled { opacity: .6; cursor: not-allowed; }
</style>

<a href="{{ route('settings.index') }}" class="back-link">
    <i class="bi bi-arrow-left"></i>
    Back to Settings
</a>

@if($errors->any())
    <div class="alert alert-danger" style="border-radius: var(--radius); font-size: 13.5px;">
        <strong>Please fix the following:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form id="mailSettingsForm" action="{{ route('settings.mail.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mail-card">
        <div class="mail-card-header">
            <div class="icon-wrap">
                <i class="bi bi-envelope-at"></i>
            </div>
            <div>
                <h6>SMTP Server Configuration</h6>
                <p>These settings are used to send all outgoing emails from the platform.</p>
            </div>
        </div>
        <div class="mail-card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">SMTP Host <span class="req">*</span></label>
                    <input type="text" name="mail_host" class="form-control"
                           value="{{ old('mail_host', $settings['mail_host']) }}"
                           placeholder="e.g. smtp.gmail.com" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">SMTP Port <span class="req">*</span></label>
                    <input type="number" name="mail_port" class="form-control"
                           value="{{ old('mail_port', $settings['mail_port']) }}"
                           placeholder="587" min="1" max="65535" required>
                    <div class="form-hint">Common: 587 (TLS), 465 (SSL), 25</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">SMTP Username</label>
                    <input type="text" name="mail_username" class="form-control"
                           value="{{ old('mail_username', $settings['mail_username']) }}"
                           placeholder="e.g. noreply@yourschool.com" autocomplete="off">
                </div>
                <div class="col-md-6">
                    <label class="form-label">SMTP Password</label>
                    <div class="password-group">
                        <input type="password" name="mail_password" id="mailPassword" class="form-control"
                               value="" placeholder="{{ $settings['mail_password'] ? '••••••••  (saved — leave blank to keep)' : 'Enter SMTP password' }}"
                               autocomplete="new-password">
                        <button type="button" class="toggle-password" tabindex="-1" aria-label="Show password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @if($settings['mail_password'])
                        <div class="form-hint">Leave blank to keep the currently saved password.</div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">Encryption <span class="req">*</span></label>
                    <select name="mail_encryption" class="form-select" required>
                        <option value="tls" {{ old('mail_encryption', $settings['mail_encryption']) == 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ old('mail_encryption', $settings['mail_encryption']) == 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="none" {{ old('mail_encryption', $settings['mail_encryption']) == 'none' ? 'selected' : '' }}>None</option>
                    </select>
                </div>
            </div>

            <div class="section-divider">Sender Identity</div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">From Email Address <span class="req">*</span></label>
                    <input type="email" name="mail_from_address" class="form-control"
                           value="{{ old('mail_from_address', $settings['mail_from_address']) }}"
                           placeholder="e.g. noreply@yourschool.com" required>
                    <div class="form-hint">Emails will appear to come from this address.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">From Name <span class="req">*</span></label>
                    <input type="text" name="mail_from_name" class="form-control"
                           value="{{ old('mail_from_name', $settings['mail_from_name']) }}"
                           placeholder="e.g. EduERP" required>
                    <div class="form-hint">The display name shown in the recipient's inbox.</div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check2-circle"></i>
                    Save Settings
                </button>
            </div>
        </div>
    </div>
</form>

<div class="test-card">
    <h6><i class="bi bi-send me-1"></i> Send Test Email</h6>
    <p>Verify your configuration by sending a test email. The values currently entered in the form above will be used.</p>
    <div class="row g-2">
        <div class="col-sm-8 col-md-6 col-lg-5">
            <input type="email" id="testEmail" class="form-control" placeholder="Enter recipient email address">
        </div>
        <div class="col-auto">
            <button type="button" id="sendTestBtn" class="btn-test">
                <i class="bi bi-send"></i>
                Send Test
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle password visibility
        const toggleBtn = document.querySelector('.toggle-password');
        const passwordInput = document.getElementById('mailPassword');

        toggleBtn.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            toggleBtn.querySelector('i').className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
        });

        // Saved success flash
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Saved',
                text: @json(session('success')),
                timer: 2200,
                showConfirmButton: false
            });
        @endif

        // Send test email
        const sendTestBtn = document.getElementById('sendTestBtn');
        const form = document.getElementById('mailSettingsForm');

        sendTestBtn.addEventListener('click', async () => {
            const testEmail = document.getElementById('testEmail').value.trim();

            if (!testEmail) {
                Swal.fire({ icon: 'warning', title: 'Recipient required', text: 'Please enter an email address to send the test to.' });
                return;
            }

            const formData = new FormData(form);
            formData.delete('_method');
            formData.append('test_email', testEmail);

            sendTestBtn.disabled = true;
            sendTestBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';

            try {
                const response = await fetch(@json(route('settings.mail.test')), {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire({ icon: 'success', title: 'Test email sent', text: data.message });
                } else {
                    let message = data.message || 'Failed to send test email.';

                    if (data.errors) {
                        message = Object.values(data.errors).flat().join('\n');
                    }

                    Swal.fire({ icon: 'error', title: 'Sending failed', text: message });
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong while sending the test email.' });
            } finally {
                sendTestBtn.disabled = false;
                sendTestBtn.innerHTML = '<i class="bi bi-send"></i> Send Test';
            }
        });
    });
</script>
@endpush

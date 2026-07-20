@extends('layouts.admin')

@section('title', 'Settings')

@section('page-title', 'Settings')
@section('page-subtitle', 'Configure how your platform works')

@section('content')

<style>
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 18px;
    }

    .setting-card {
        position: relative;
        display: block;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 22px;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
    }

    a.setting-card:hover {
        border-color: #bfdbfe;
        box-shadow: 0 10px 26px rgba(37, 99, 235, .10);
        transform: translateY(-2px);
        color: inherit;
    }

    .setting-card .icon-wrap {
        width: 44px;
        height: 44px;
        display: grid;
        place-items: center;
        border-radius: var(--radius);
        font-size: 20px;
        margin-bottom: 14px;
    }

    .icon-blue { background: #dbeafe; color: #2563eb; }
    .icon-green { background: #dcfce7; color: #16a34a; }
    .icon-orange { background: #ffedd5; color: #ea580c; }
    .icon-purple { background: #f3e8ff; color: #9333ea; }
    .icon-slate { background: #f1f5f9; color: #475569; }

    .setting-card h6 {
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 15px;
    }

    .setting-card p {
        margin: 0;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.5;
    }

    .setting-card .card-status {
        position: absolute;
        top: 16px;
        right: 16px;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        letter-spacing: .03em;
    }

    .card-status.configured { background: #dcfce7; color: #166534; }
    .card-status.pending { background: #fef3c7; color: #92400e; }
    .card-status.soon { background: #f1f5f9; color: #64748b; }

    .setting-card.disabled {
        opacity: .65;
        cursor: not-allowed;
    }
</style>

<div class="settings-grid" style="margin-top: 4px;">

    {{-- SMTP / Mail settings --}}
    <a href="{{ route('settings.mail') }}" class="setting-card">
        <span class="card-status {{ $mailConfigured ? 'configured' : 'pending' }}">
            {{ $mailConfigured ? 'Configured' : 'Not configured' }}
        </span>
        <div class="icon-wrap icon-blue">
            <i class="bi bi-envelope-at"></i>
        </div>
        <h6>SMTP Mail Setup</h6>
        <p>Configure the SMTP server, credentials and sender identity used for all outgoing emails.</p>
    </a>

    {{-- Placeholders for upcoming settings --}}
    <div class="setting-card disabled">
        <span class="card-status soon">Coming soon</span>
        <div class="icon-wrap icon-slate">
            <i class="bi bi-sliders"></i>
        </div>
        <h6>General Settings</h6>
        <p>Application name, logo, timezone and other platform-wide preferences.</p>
    </div>

    <div class="setting-card disabled">
        <span class="card-status soon">Coming soon</span>
        <div class="icon-wrap icon-green">
            <i class="bi bi-credit-card"></i>
        </div>
        <h6>Payment Gateway</h6>
        <p>Configure payment providers and keys used for subscription billing.</p>
    </div>

    <div class="setting-card disabled">
        <span class="card-status soon">Coming soon</span>
        <div class="icon-wrap icon-orange">
            <i class="bi bi-bell"></i>
        </div>
        <h6>Notifications</h6>
        <p>Control which events trigger email and in-app notifications.</p>
    </div>

    <div class="setting-card disabled">
        <span class="card-status soon">Coming soon</span>
        <div class="icon-wrap icon-purple">
            <i class="bi bi-shield-check"></i>
        </div>
        <h6>Security</h6>
        <p>Password policy, session management and login security options.</p>
    </div>

</div>

@endsection

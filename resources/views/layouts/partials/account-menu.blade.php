@php
    $accountUser = auth()->user();
    $avatarName = urlencode($accountUser->name ?? 'User');
    $avatarFallback = "https://ui-avatars.com/api/?name={$avatarName}&background=2563eb&color=fff";
    $avatarUrl = $accountUser?->photo
        ? route('account.profile.photo', ['v' => optional($accountUser->updated_at)->timestamp])
        : $avatarFallback;
@endphp

@once
    <style>
        .account-menu{position:relative}
        .account-menu-toggle{align-items:center;background:#fff;border:1px solid transparent;border-radius:12px;display:flex;gap:10px;padding:6px 8px;transition:.2s ease}
        .account-menu-toggle:hover,.account-menu.open .account-menu-toggle{background:#f8fafc;border-color:#dbeafe;box-shadow:0 8px 20px rgba(15,23,42,.08)}
        .account-menu-toggle img{width:38px;height:38px;min-width:38px;border-radius:50%;border:2px solid #dbeafe;object-fit:cover;background:#eff6ff;display:block;font-size:0}
        .account-menu-toggle strong{color:#0f172a;font-size:14px;white-space:nowrap}
        .account-menu-toggle small{color:#64748b;display:block;font-size:12px;font-weight:500;line-height:1.2;text-align:left}
        .account-menu-toggle .account-chevron{color:#64748b;font-size:12px;transition:transform .2s ease}
        .account-menu.open .account-chevron{transform:rotate(180deg)}
        .account-menu-dropdown{background:#fff;border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 18px 45px rgba(15,23,42,.16);display:none;min-width:230px;padding:8px;position:absolute;right:0;top:calc(100% + 10px);z-index:1200}
        .account-menu.open .account-menu-dropdown{display:block}
        .account-menu-dropdown a{align-items:center;border-radius:10px;color:#334155;display:flex;gap:10px;padding:11px 12px;text-decoration:none;font-weight:600}
        .account-menu-dropdown a:hover{background:#eff6ff;color:#1d4ed8}
        .account-menu-dropdown i{font-size:17px;color:inherit}
        @media(max-width:768px){.account-menu-toggle strong,.account-menu-toggle small,.account-menu-toggle .account-chevron{display:none}.account-menu-dropdown{right:-6px}}
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-account-menu]').forEach((menu) => {
                const toggle = menu.querySelector('[data-account-menu-toggle]');

                toggle.addEventListener('click', (event) => {
                    event.stopPropagation();
                    document.querySelectorAll('[data-account-menu]').forEach((item) => {
                        if (item !== menu) {
                            item.classList.remove('open');
                        }
                    });
                    menu.classList.toggle('open');
                });
            });

            document.addEventListener('click', () => {
                document.querySelectorAll('[data-account-menu]').forEach((menu) => menu.classList.remove('open'));
            });
        });
    </script>
@endonce

<div class="account-menu" data-account-menu>
    <button type="button" class="account-menu-toggle" data-account-menu-toggle aria-label="Open account menu">
        <img src="{{ $avatarUrl }}" alt="{{ $accountUser->name ?? 'User' }}" onerror="this.onerror=null;this.src='{{ $avatarFallback }}';">
        <span>
            <strong>{{ $accountUser->name ?? 'User' }}</strong>
            <small>{{ $accountUser?->role?->name ?? 'Account' }}</small>
        </span>
        <i class="bi bi-chevron-down account-chevron"></i>
    </button>
    <div class="account-menu-dropdown">
        <a href="{{ route('account.profile') }}">
            <i class="bi bi-person-circle"></i>
            <span>View Profile</span>
        </a>
        <a href="{{ route('account.password') }}">
            <i class="bi bi-key"></i>
            <span>Reset Password</span>
        </a>
    </div>
</div>

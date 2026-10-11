<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EduERP')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --radius: 10px;
            --radius-sm: 4px;
            --sidebar-bg: #f8fbff;
            --sidebar-w: 256px;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --surface: #ffffff;
            --page-bg: #f4f7fb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .07), transparent 30rem),
                radial-gradient(circle at top right, rgba(14, 165, 233, .05), transparent 28rem),
                linear-gradient(180deg, #fbfdff 0%, var(--page-bg) 100%);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            font-size: 14.5px;
            color: var(--ink);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* =========================SIDEBAR========================= */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, #f8fbff 0%, #eef6ff 54%, #eaf4ff 100%);
            color: var(--ink);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            border-right: 1px solid #dbeafe;
            box-shadow: 10px 0 30px rgba(37, 99, 235, .08);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px;
            border-bottom: 1px solid #dbeafe;
        }

        .logo-mark {
            width: 36px;
            height: 36px;
            min-width: 36px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: var(--radius);
            font-size: 18px;
            color: #fff;
            box-shadow: 0 6px 16px rgba(37, 99, 235, .35);
        }

        .logo-text {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.02em;
            line-height: 1.1;
        }

        .logo-text span {
            color: #60a5fa;
        }

        .logo-sub {
            display: block;
            font-size: 10.5px;
            font-weight: 500;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #64748b;
            margin-top: 2px;
        }

        .sidebar-menu {
            flex: 1;
            padding: 14px 12px 20px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #bfdbfe transparent;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #bfdbfe;
        }

        .menu-label {
            display: block;
            padding: 16px 12px 7px;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: .13em;
            text-transform: uppercase;
            color: #64748b;
        }

        .menu-label:first-child {
            padding-top: 4px;
        }

        .sidebar-menu a {
            position: relative;
            display: flex;
            align-items: center;
            gap: 11px;
            color: #475569;
            text-decoration: none;
            padding: 10px 12px;
            border-radius: var(--radius);
            margin-bottom: 2px;
            transition: background .18s ease, color .18s ease;
            font-weight: 500;
            font-size: 14px;
        }

        .sidebar-menu a i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: #64748b;
            transition: color .18s ease;
        }

        .sidebar-menu a:hover {
            background: #eaf4ff;
            color: #1d4ed8;
        }

        .sidebar-menu a:hover i {
            color: #2563eb;
        }

        .sidebar-menu a.active-menu {
            background: linear-gradient(135deg, #dbeafe, #eff6ff);
            color: #1d4ed8;
            font-weight: 600;
            box-shadow: inset 0 0 0 1px #bfdbfe, 0 8px 18px rgba(37, 99, 235, .10);
        }

        .sidebar-menu a.active-menu::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 8px;
            bottom: 8px;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: #3b82f6;
        }

        .sidebar-menu a.active-menu i {
            color: #2563eb;
        }

        .sidebar-dropdown {
            margin-bottom: 2px;
        }

        .sidebar-dropdown-toggle {
            align-items: center;
            background: transparent;
            border: none;
            border-radius: var(--radius);
            color: #475569;
            display: flex;
            font-weight: 500;
            font-size: 14px;
            gap: 11px;
            padding: 10px 12px;
            text-align: left;
            transition: background .18s ease, color .18s ease;
            width: 100%;
        }

        .sidebar-dropdown-toggle i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: #64748b;
        }

        .sidebar-dropdown-toggle:hover,
        .sidebar-dropdown.open .sidebar-dropdown-toggle,
        .sidebar-dropdown.active .sidebar-dropdown-toggle {
            background: #eaf4ff;
            color: #1d4ed8;
        }

        .sidebar-dropdown-toggle .dropdown-arrow {
            font-size: 12px;
            width: auto;
            margin-left: auto;
            transition: transform .22s ease;
        }

        .sidebar-dropdown.open .dropdown-arrow {
            transform: rotate(180deg);
        }

        .sidebar-submenu {
            display: grid;
            grid-template-rows: 0fr;
            overflow: hidden;
            transition: grid-template-rows .22s ease;
        }

        .sidebar-dropdown.open .sidebar-submenu {
            grid-template-rows: 1fr;
        }

        .sidebar-submenu-inner {
            min-height: 0;
            padding: 4px 0 4px 21px;
            margin-left: 21px;
            border-left: 1px solid #bfdbfe;
        }

        .sidebar-menu .sidebar-submenu a {
            border-radius: var(--radius);
            font-size: 13.5px;
            margin-bottom: 2px;
            padding: 8px 10px;
        }

        .sidebar-menu .sidebar-submenu a.active-menu {
            background: #dbeafe;
        }

        .sidebar-menu .sidebar-submenu a.active-menu::before {
            left: -22px;
        }

        .logout-wrapper {
            padding: 14px 12px;
            border-top: 1px solid #dbeafe;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            border-radius: var(--radius);
            padding: 10px;
            font-weight: 600;
            font-size: 14px;
            color: #dc2626;
            background: #fff;
            border: 1px solid #fecaca;
            transition: background .18s ease, color .18s ease;
        }

        .logout-btn:hover {
            background: #dc2626;
            border-color: #dc2626;
            color: #fff;
        }

        /* =========================MAIN CONTENT========================= */

        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }

        /* =========================TOPBAR========================= */

        .topbar {
            position: sticky;
            top: 0;
            z-index: 900;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, .96), rgba(239, 246, 255, .92));
            backdrop-filter: blur(12px);
            padding: 12px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(191, 219, 254, .75);
            box-shadow: 0 8px 30px rgba(37, 99, 235, .06);
        }

        .topbar h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -.01em;
            color: var(--ink);
        }

        .topbar-subtitle {
            display: block;
            margin-top: 2px;
            font-size: 12.5px;
            font-weight: 500;
            color: var(--muted);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-icon-btn {
            position: relative;
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: linear-gradient(135deg, #fff, #f8fbff);
            cursor: pointer;
            transition: background .18s ease, border-color .18s ease;
        }

        .topbar-icon-btn i {
            font-size: 16px;
            color: #475569;
        }

        .topbar-icon-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .topbar-icon-btn .notif-dot {
            position: absolute;
            top: 9px;
            right: 10px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ef4444;
            border: 1.5px solid #fff;
        }

        .topbar-divider {
            width: 1px;
            height: 24px;
            background: var(--line);
            margin: 0 6px;
        }

        /* =========================PAGE CONTENT========================= */

        .page-content {
            padding: 26px 28px 40px;
        }

        /* =========================CONTENT CARD========================= */

        .content-card {
            background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(248,251,255,.98));
            border-radius: var(--radius);
            padding: 24px;
            margin-top: 22px;
            box-shadow: 0 12px 34px rgba(37, 99, 235, .08);
            border: 1px solid rgba(191, 219, 254, .70);
        }

        /* =========================DASHBOARD CARDS========================= */

        .dashboard-card {
            color: #fff;
            padding: 20px;
            border-radius: var(--radius);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .10);
        }

        .card-blue {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .card-green {
            background: linear-gradient(135deg, #16a34a, #15803d);
        }

        .card-orange {
            background: linear-gradient(135deg, #0891b2, #0e7490);
        }

        .card-purple {
            background: linear-gradient(135deg, #9333ea, #7e22ce);
        }

        /* =========================TABLE========================= */
        .table thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--line);
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .table tbody td {
            border-color: #f1f5f9;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .badge {
            padding: 6px 10px;
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: .02em;
            border-radius: var(--radius-sm);
        }

        .btn {
            border-radius: var(--radius);
        }

        form[role="search"] {
            max-width: 360px;
            width: 100%;
        }

        form[role="search"] input[type="search"] {
            border: 1px solid var(--line);
            border-radius: var(--radius);
            min-width: 260px;
            width: 100%;
            background: #fff;
        }

        form[role="search"] input[type="search"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .12);
        }

        form[role="search"] button[type="submit"],
        form[role="search"] .btn {
            display: none;
        }

        .index-toolbar-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .index-toolbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: nowrap;
            justify-content: flex-end;
        }

        .btn-add-record {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 0 18px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            box-shadow: 0 6px 16px rgba(37, 99, 235, .25);
            transition: background .18s ease, box-shadow .18s ease;
        }

        .btn-add-record:hover {
            background: var(--primary-dark);
            color: #fff;
            box-shadow: 0 8px 20px rgba(37, 99, 235, .32);
        }

        .btn-add-record i {
            font-size: 16px;
        }

        /* =========================RESPONSIVE========================= */

        @media(max-width:991px) {

            .sidebar {
                width: 230px;
            }

            .main-content {
                margin-left: 230px;
            }

        }

        @media(max-width:768px) {

            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar,
            .page-content {
                padding-left: 16px;
                padding-right: 16px;
            }
        }

        @media(max-width:576px) {

            .index-toolbar-row {
                align-items: stretch;
            }

            .index-toolbar-actions {
                flex-direction: column;
                align-items: stretch;
                width: 100%;
            }

            form[role="search"],
            form[role="search"] input[type="search"] {
                max-width: none;
                min-width: 0 !important;
                width: 100%;
            }

            .btn-add-record {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <div class="logo-mark">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div>
                <div class="logo-text">Edu<span>ERP</span></div>
                <span class="logo-sub">Admin Console</span>
            </div>
        </div>
        <div class="sidebar-menu">
            <span class="menu-label">Overview</span>
            <a href="{{ url('/admin/dashboard') }}"
                class="{{ request()->is('admin/dashboard') ? 'active-menu' : '' }}">
                <i class="bi bi-grid-1x2"></i>
                <span>Dashboard</span>
            </a>

            @if(auth()->user()->role->slug == 'super_admin')
            <span class="menu-label">Management</span>
            <a href="{{ route('schools.index') }}"
                class="{{ request()->routeIs('schools.*') ? 'active-menu' : '' }}">
                <i class="bi bi-buildings"></i>
                <span>Schools</span>
            </a>
            <a href="{{ route('roles.index') }}"
                class="{{ request()->routeIs('roles.*') ? 'active-menu' : '' }}">
                <i class="bi bi-shield-lock"></i>
                <span>Roles</span>
            </a>
            <a href="{{ route('users.index') }}"
                class="{{ request()->routeIs('users.*') ? 'active-menu' : '' }}">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>

            <span class="menu-label">Billing</span>
            @php($subscriptionsOpen = request()->routeIs('subscription-plans.*'))
            <div class="sidebar-dropdown {{ $subscriptionsOpen ? 'open active' : '' }}" data-sidebar-dropdown>
                <button type="button"
                        class="sidebar-dropdown-toggle"
                        aria-expanded="{{ $subscriptionsOpen ? 'true' : 'false' }}">
                    <i class="bi bi-credit-card-2-front"></i>
                    <span>Subscriptions</span>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </button>
                <div class="sidebar-submenu">
                    <div class="sidebar-submenu-inner">
                        <a href="{{ route('subscription-plans.index') }}"
                            class="{{ request()->routeIs('subscription-plans.*') ? 'active-menu' : '' }}">
                            <i class="bi bi-card-checklist"></i>
                            <span>Plans</span>
                        </a>
                        <a href="#">
                            <i class="bi bi-receipt"></i>
                            <span>Payments</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <span class="menu-label">System</span>
            <a href="#">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Reports</span>
            </a>
            <a href="{{ route('settings.index') }}"
                class="{{ request()->routeIs('settings.*') ? 'active-menu' : '' }}">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </div>
        <div class="logout-wrapper">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn logout-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
    <!-- Main -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div>
                <h4>@yield('page-title')</h4>
                @hasSection('page-subtitle')
                    <span class="topbar-subtitle">@yield('page-subtitle')</span>
                @endif
            </div>
            <div class="topbar-right">
                <button type="button" class="topbar-icon-btn" aria-label="Notifications">
                    <i class="bi bi-bell"></i>
                    <span class="notif-dot"></span>
                </button>
                <div class="topbar-divider"></div>
                @include('layouts.partials.account-menu')
            </div>
        </div>
        <!-- Page Content -->
        <div class="page-content">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-sidebar-dropdown]').forEach((dropdown) => {
                const toggle = dropdown.querySelector('.sidebar-dropdown-toggle');

                toggle.addEventListener('click', () => {
                    const isOpen = dropdown.classList.toggle('open');
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            });

            document.querySelectorAll('form[role="search"]').forEach((form) => {
                const input = form.querySelector('input[type="search"][name="search"]');

                if (!input) {
                    return;
                }

                let timer = null;

                input.addEventListener('input', () => {
                    clearTimeout(timer);

                    timer = setTimeout(() => {
                        const value = input.value.trim();

                        if (value === '') {
                            window.location.href = form.action;
                            return;
                        }

                        form.submit();
                    }, 450);
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>

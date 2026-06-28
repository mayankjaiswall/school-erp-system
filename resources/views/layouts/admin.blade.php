<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EduERP')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8fafc;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        /* =========================SIDEBAR========================= */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 270px;
            height: 100vh;
            background: #0f172a;
            color: #fff;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            box-shadow: 8px 0 30px rgba(0, 0, 0, .15);
        }

        .logo {
            padding: 28px 24px;
            font-size: 28px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .logo span {
            color: #3b82f6;
        }

        .sidebar-menu {
            flex: 1;
            padding: 20px 15px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            color: #94a3b8;
            text-decoration: none;
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 8px;
            transition: .25s ease;
            font-weight: 500;
        }

        .sidebar-menu a i {
            font-size: 18px;
        }

        .sidebar-menu a:hover {
            background: #1e293b;
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar-menu a.active-menu {
            background: linear-gradient(135deg,
                    #2563eb,
                    #3b82f6);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(37, 99, 235, .35);
        }

        .sidebar-menu a.active-menu i {
            color: #fff;
        }

        .sidebar-dropdown {
            margin-bottom: 8px;
        }

        .sidebar-dropdown-toggle {
            align-items: center;
            background: transparent;
            border: none;
            border-radius: 14px;
            color: #94a3b8;
            display: flex;
            font-weight: 500;
            gap: 14px;
            padding: 14px 18px;
            text-align: left;
            transition: .25s ease;
            width: 100%;
        }

        .sidebar-dropdown-toggle i {
            font-size: 18px;
        }

        .sidebar-dropdown-toggle:hover,
        .sidebar-dropdown.open .sidebar-dropdown-toggle,
        .sidebar-dropdown.active .sidebar-dropdown-toggle {
            background: #1e293b;
            color: #fff;
        }

        .sidebar-dropdown-toggle .dropdown-arrow {
            font-size: 14px;
            margin-left: auto;
            transition: transform .25s ease;
        }

        .sidebar-dropdown.open .dropdown-arrow {
            transform: rotate(180deg);
        }

        .sidebar-submenu {
            display: grid;
            grid-template-rows: 0fr;
            overflow: hidden;
            transition: grid-template-rows .25s ease;
        }

        .sidebar-dropdown.open .sidebar-submenu {
            grid-template-rows: 1fr;
        }

        .sidebar-submenu-inner {
            min-height: 0;
            padding: 6px 0 0 18px;
        }

        .sidebar-menu .sidebar-submenu a {
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 6px;
            padding: 11px 14px;
        }

        .sidebar-menu .sidebar-submenu a.active-menu {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
        }

        .logout-wrapper {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, .08);
        }

        .logout-btn {
            width: 100%;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
        }

        /* =========================MAIN CONTENT========================= */

        .main-content {
            margin-left: 270px;
            min-height: 100vh;
        }

        /* =========================TOPBAR========================= */

        .topbar {
            background: #fff;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .04);
        }

        .topbar h4 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .topbar-right i {
            font-size: 20px;
            cursor: pointer;
            color: #475569;
        }

        .topbar-right img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 3px solid #dbeafe;
        }

        /* =========================PAGE CONTENT========================= */

        .page-content {
            padding: 30px;
        }

        /* =========================CONTENT CARD========================= */

        .content-card {
            background: #fff;
            border-radius: 18px;
            padding: 30px;
            margin-top: 25px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
            border: 1px solid #e5e7eb;
        }

        /* =========================DASHBOARD CARDS========================= */

        .dashboard-card {
            color: #fff;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
        }

        .card-blue {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .card-green {
            background: linear-gradient(135deg, #16a34a, #15803d);
        }

        .card-orange {
            background: linear-gradient(135deg, #ea580c, #c2410c);
        }

        .card-purple {
            background: linear-gradient(135deg, #9333ea, #7e22ce);
        }

        /* =========================TABLE========================= */
        .table thead th {
            background: #f8fafc;
            color: #334155;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .badge {
            padding: 8px 12px;
            font-size: 12px;
        }

        form[role="search"] {
            max-width: 360px;
            width: 100%;
        }

        form[role="search"] input[type="search"] {
            border: 2px solid #2563eb;
            border-radius: 6px;
            min-width: 260px;
            width: 100%;
        }

        form[role="search"] input[type="search"]:focus {
            border-color: #1d4ed8;
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .15);
        }

        form[role="search"] button[type="submit"],
        form[role="search"] .btn {
            display: none;
        }

        /* =========================RESPONSIVE========================= */

        @media(max-width:991px) {

            .sidebar {
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
            }

        }

        @media(max-width:768px) {

            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            Edu<span>ERP</span>
        </div>
        <div class="sidebar-menu">
            <a href="{{ url('/admin/dashboard') }}"
                class="{{ request()->is('admin/dashboard') ? 'active-menu' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            @if(auth()->user()->role->slug == 'super_admin')
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
            <a href="#">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Reports</span>
            </a>
            <a href="#">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </div>
        <div class="logout-wrapper">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger logout-btn">
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
            <h4>@yield('page-title')</h4>
            <div class="topbar-right">
                <i class="bi bi-bell fs-5"></i>
                <img src="https://ui-avatars.com/api/?name=Admin&background=2563eb&color=fff">
                <strong>
                    {{ auth()->user()->name ?? 'Admin' }}
                </strong>
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

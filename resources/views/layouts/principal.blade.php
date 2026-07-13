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
            font-size: 15px;
            overflow-x: hidden;
            -webkit-user-select: none;
            user-select: none;
        }

        input,
        textarea,
        select,
        [contenteditable="true"] {
            -webkit-user-select: text;
            user-select: text;
        }

        label,
        button,
        .btn,
        .sidebar,
        .topbar {
            -webkit-user-select: none;
            user-select: none;
        }

        /* =========================SIDEBAR========================= */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background:
                linear-gradient(180deg, rgba(30, 41, 59, .95), rgba(15, 23, 42, 1)),
                #0f172a;
            color: #fff;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            box-shadow: 8px 0 30px rgba(0, 0, 0, .15);
        }

        .logo {
            padding: 22px 22px 18px;
            font-size: 24px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            line-height: 1;
        }

        .logo span {
            color: #3b82f6;
        }

        .logo small {
            display: block;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .08em;
            margin-top: 8px;
            text-transform: uppercase;
        }

        .sidebar-menu {
            flex: 1;
            padding: 18px 14px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent;
        }

        .sidebar-section {
            margin-bottom: 16px;
        }

        .sidebar-section-title {
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            margin: 18px 12px 8px;
            text-transform: uppercase;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
            text-decoration: none;
            padding: 11px 14px;
            border-radius: 10px;
            margin-bottom: 4px;
            transition: .25s ease;
            font-weight: 500;
            min-height: 42px;
            position: relative;
        }

        .sidebar-menu a i {
            align-items: center;
            background: rgba(148, 163, 184, .12);
            border-radius: 8px;
            color: #93c5fd;
            display: inline-flex;
            flex: 0 0 30px;
            font-size: 16px;
            height: 30px;
            justify-content: center;
            width: 30px;
        }

        .sidebar-menu a:hover {
            background: rgba(30, 41, 59, .9);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar-menu a:hover i {
            background: rgba(59, 130, 246, .18);
            color: #bfdbfe;
        }

        .sidebar-menu a.active-menu {
            background: #2563eb;
            color: #fff;
            font-weight: 600;
            box-shadow: 0 10px 24px rgba(37, 99, 235, .28);
        }

        .sidebar-menu a.active-menu i {
            background: rgba(255, 255, 255, .16);
            color: #fff;
        }

        .sidebar-menu a.disabled-link {
            color: #64748b;
            cursor: default;
            pointer-events: none;
        }

        .sidebar-menu a.disabled-link i {
            background: rgba(100, 116, 139, .12);
            color: #64748b;
        }

        .sidebar-menu a.disabled-link::after {
            content: "Soon";
            color: #94a3b8;
            font-size: 10px;
            font-weight: 700;
            margin-left: auto;
            text-transform: uppercase;
        }

        .logout-wrapper {
            padding: 18px;
            border-top: 1px solid rgba(255, 255, 255, .08);
        }

        .logout-btn {
            width: 100%;
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
        }

        /* =========================MAIN CONTENT========================= */

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        /* =========================TOPBAR========================= */

        .topbar {
            background: #fff;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .04);
        }

        .topbar h4 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .topbar-right i {
            font-size: 18px;
            cursor: pointer;
            color: #475569;
        }

        .topbar-right img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 2px solid #dbeafe;
        }

        /* =========================PAGE CONTENT========================= */

        .page-content {
            padding: 24px;
        }

        /* =========================CONTENT CARD========================= */

        .content-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            margin-top: 22px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .07);
            border: 1px solid #e5e7eb;
        }

        /* =========================DASHBOARD CARDS========================= */

        .dashboard-card {
            color: #fff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .10);
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
            flex: 0 0 320px;
            max-width: 320px;
            width: 320px;
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

        .index-toolbar-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: nowrap;
        }

        .index-toolbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: nowrap;
            justify-content: flex-end;
            flex: 0 0 auto;
        }

        .btn-add-record {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            padding: 0 18px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #fff;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            box-shadow: 0 10px 24px rgba(37, 99, 235, .22);
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease, color .2s ease;
        }

        .btn-add-record:hover {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(37, 99, 235, .28);
        }

        .btn-add-record i {
            font-size: 16px;
        }

        /* =========================RESPONSIVE========================= */

        @media(max-width:991px) {

            .sidebar {
                width: 245px;
            }

            .main-content {
                margin-left: 245px;
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
            <small>Principal Panel</small>
        </div>
        <div class="sidebar-menu">
            <div class="sidebar-section">
                <a href="{{ route('principal.dashboard') }}"
                    class="{{ request()->routeIs('principal.dashboard') ? 'active-menu' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">School Administration</div>
                <a href="{{ route('principal.users.index') }}"
                    class="{{ request()->routeIs('principal.users.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
                <a href="{{ route('teachers.index') }}"
                    class="{{ request()->routeIs('teachers.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-person-badge"></i>
                    <span>Teachers</span>
                </a>
                <a href="{{ route('students.index') }}"
                    class="{{ request()->routeIs('students.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-mortarboard"></i>
                    <span>Students</span>
                </a>
                <a href="{{ route('principal.parents.index') }}"
                    class="{{ request()->routeIs('principal.parents.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-person-hearts"></i>
                    <span>Parents</span>
                </a>
                <!-- <a href="#" class="disabled-link" aria-disabled="true">
                    <i class="bi bi-person-workspace"></i>
                    <span>HODs</span>
                </a> -->
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">Academics</div>
                <a href="{{ route('classes.index') }}"
                    class="{{ request()->routeIs('classes.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-journal-bookmark"></i>
                    <span>Classes</span>
                </a>
                <a href="{{ route('subjects.index') }}"
                    class="{{ request()->routeIs('subjects.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-book"></i>
                    <span>Subjects</span>
                </a>
                <a href="{{ route('teacher-subjects.index') }}"
                    class="{{ request()->routeIs('teacher-subjects.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-diagram-3"></i>
                    <span>Teacher Subjects</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">Attendance</div>
                <a href="{{ route('principal.attendance.index') }}"
                    class="{{ request()->routeIs('principal.attendance.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-clipboard2-check"></i>
                    <span>Student Attendance</span>
                </a>
                <a href="#" class="disabled-link" aria-disabled="true">
                    <i class="bi bi-calendar2-check"></i>
                    <span>Teacher Attendance</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">Examination</div>
                <a href="{{ route('principal.exams.index') }}"
                    class="{{ request()->routeIs('principal.exams.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Exams</span>
                </a>
                <a href="{{ route('principal.reports.results') }}"
                    class="{{ request()->routeIs('principal.reports.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-pencil-square"></i>
                    <span>Results</span>
                </a>
                <a href="{{ route('principal.report-cards.index') }}"
                    class="{{ request()->routeIs('principal.report-cards.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-award"></i>
                    <span>Report Cards</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">Reports</div>
                <a href="{{ route('principal.attendance.index') }}"
                    class="{{ request()->routeIs('principal.attendance.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Attendance Reports</span>
                </a>
                <a href="{{ route('principal.reports.results') }}"
                    class="{{ request()->routeIs('principal.reports.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Academic Reports</span>
                </a>
            </div>

            <div class="sidebar-section">
                <a href="#" class="disabled-link" aria-disabled="true">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </div>
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
            const interactiveSelector = 'a, button, input, textarea, select, option, label[for], [role="button"], [contenteditable="true"]';

            const clearStaticSelection = () => {
                const selection = window.getSelection();

                if (selection) {
                    selection.removeAllRanges();
                }
            };

            document.addEventListener('pointerdown', (event) => {
                if (!event.target.closest(interactiveSelector)) {
                    event.preventDefault();
                    clearStaticSelection();
                }
            });

            document.addEventListener('selectionchange', () => {
                if (!document.activeElement || document.activeElement === document.body) {
                    clearStaticSelection();
                }
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

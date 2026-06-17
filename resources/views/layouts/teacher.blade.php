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
            box-sizing: border-box
        }

        body {
            background: #f8fafc;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 290px;
            height: 100vh;
            background: linear-gradient(180deg, rgba(20, 83, 45, .96), rgba(15, 23, 42, 1)), #0f172a;
            color: #fff;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            box-shadow: 8px 0 30px rgba(0, 0, 0, .15)
        }

        .logo {
            padding: 24px 24px 20px;
            font-size: 28px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            line-height: 1
        }

        .logo span {
            color: #22c55e
        }

        .logo small {
            display: block;
            color: #bbf7d0;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .08em;
            margin-top: 8px;
            text-transform: uppercase
        }

        .sidebar-menu {
            flex: 1;
            padding: 18px 14px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent
        }

        .sidebar-section {
            margin-bottom: 16px
        }

        .sidebar-section-title {
            color: #86efac;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            margin: 18px 12px 8px;
            text-transform: uppercase
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #d1fae5;
            text-decoration: none;
            padding: 11px 14px;
            border-radius: 10px;
            margin-bottom: 4px;
            transition: .25s ease;
            font-weight: 500;
            min-height: 42px;
            position: relative
        }

        .sidebar-menu a i {
            align-items: center;
            background: rgba(187, 247, 208, .12);
            border-radius: 8px;
            color: #bbf7d0;
            display: inline-flex;
            flex: 0 0 30px;
            font-size: 16px;
            height: 30px;
            justify-content: center;
            width: 30px
        }

        .sidebar-menu a:hover {
            background: rgba(21, 128, 61, .35);
            color: #fff;
            transform: translateX(4px)
        }

        .sidebar-menu a.active-menu {
            background: #16a34a;
            color: #fff;
            font-weight: 600;
            box-shadow: 0 10px 24px rgba(22, 163, 74, .28)
        }

        .sidebar-menu a.active-menu i {
            background: rgba(255, 255, 255, .16);
            color: #fff
        }

        .sidebar-menu a.disabled-link {
            color: #6ee7b7;
            cursor: default;
            pointer-events: none;
            opacity: .62
        }

        .sidebar-menu a.disabled-link::after {
            content: "Soon";
            color: #bbf7d0;
            font-size: 10px;
            font-weight: 700;
            margin-left: auto;
            text-transform: uppercase
        }

        .logout-wrapper {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, .08)
        }

        .logout-btn {
            width: 100%;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600
        }

        .main-content {
            margin-left: 290px;
            min-height: 100vh
        }

        .topbar {
            background: #fff;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .04)
        }

        .topbar h4 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #0f172a
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 18px
        }

        .topbar-right i {
            font-size: 20px;
            cursor: pointer;
            color: #475569
        }

        .topbar-right img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 3px solid #dcfce7
        }

        .page-content {
            padding: 30px
        }

        .content-card {
            background: #fff;
            border-radius: 18px;
            padding: 30px;
            margin-top: 25px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
            border: 1px solid #e5e7eb
        }

        .table thead th {
            background: #f8fafc;
            color: #334155;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0
        }

        .table tbody tr:hover {
            background: #f8fafc
        }

        .badge {
            padding: 8px 12px;
            font-size: 12px
        }

        @media(max-width:991px) {
            .sidebar {
                width: 245px
            }

            .main-content {
                margin-left: 245px
            }
        }

        @media(max-width:768px) {
            .sidebar {
                display: none
            }

            .main-content {
                margin-left: 0
            }

            .topbar {
                padding: 16px 20px
            }

            .page-content {
                padding: 20px
            }

            .topbar h4 {
                font-size: 22px
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            Edu<span>ERP</span>
            <small>Teacher Panel</small>
        </div>
        <div class="sidebar-menu">
            <div class="sidebar-section">
                <a href="{{ route('teacher.dashboard') }}" class="{{ request()->routeIs('teacher.dashboard') ? 'active-menu' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-section-title">My Work</div>
                <a href="{{ route('teacher.classes.index') }}" class="{{ request()->routeIs('teacher.classes.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-journal-bookmark"></i>
                    <span>My Classes</span>
                </a>
                <a href="#" class="disabled-link" aria-disabled="true">
                    <i class="bi bi-book"></i>
                    <span>My Subjects</span>
                </a>
                <a href="#" class="disabled-link" aria-disabled="true">
                    <i class="bi bi-mortarboard"></i>
                    <span>My Students</span>
                </a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-section-title">Attendance</div>
                <a href="{{ route('teacher.attendance.index') }}" class="{{ request()->routeIs('teacher.attendance.index') ? 'active-menu' : '' }}">
                    <i class="bi bi-clipboard2-check"></i>
                    <span>Take Attendance</span>
                </a>
                <a href="{{ route('teacher.attendance.report') }}" class="{{ request()->routeIs('teacher.attendance.report') ? 'active-menu' : '' }}">
                    <i class="bi bi-calendar2-check"></i>
                    <span>Attendance Reports</span>
                </a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-section-title">Examination</div>
                <a href="{{ route('teacher.marks.index') }}" class="{{ request()->routeIs('teacher.marks.*') ? 'active-menu' : '' }}">
                    <i class="bi bi-pencil-square"></i>
                    <span>Marks Entry</span>
                </a>
                <a href="#" class="disabled-link" aria-disabled="true">
                    <i class="bi bi-award"></i>
                    <span>Report Cards</span>
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
    <div class="main-content">
        <div class="topbar">
            <h4>@yield('page-title')</h4>
            <div class="topbar-right">
                <i class="bi bi-bell fs-5"></i>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Teacher') }}&background=16a34a&color=fff">
                <strong>{{ auth()->user()->name ?? 'Teacher' }}</strong>
            </div>
        </div>
        <div class="page-content">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>

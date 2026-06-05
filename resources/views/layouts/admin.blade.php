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

        /* Sidebar */

        .sidebar {
            position: fixed;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #0f172a, #1e293b);
            color: white;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        .logo {
            padding: 22px;
            font-size: 24px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .logo span {
            color: #60a5fa;
        }

        .sidebar-menu {
            padding-top: 15px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 22px;
            color: #cbd5e1;
            text-decoration: none;
            transition: .3s;
        }

        .sidebar-menu a:hover {
            background: #2563eb;
            color: white;
        }

        .sidebar-menu i {
            font-size: 18px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 22px;
            color: #cbd5e1;
            text-decoration: none;
            transition: .3s;
        }

        .sidebar-menu a:hover {
            background: #2563eb;
            color: white;
        }

        .sidebar-menu a.active-menu {
            background: linear-gradient(90deg,#2563eb,#3b82f6);
            color: white;
            /* border-radius: 0 30px 30px 0; */
            /* margin-right: 10px; */
            font-weight: 600;
        }

        .sidebar-menu a.active-menu i {
            color: white;
        }

        /* Main Content */

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* Topbar */

        .topbar {
            background: white;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
        }

        .topbar h4 {
            margin: 0;
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar-right img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
        }

        /* Content */

        .page-content {
            padding: 30px;
        }

        /* Cards */

        .dashboard-card {
            color: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,.08);
        }

        .card-blue {
            background: linear-gradient(135deg,#2563eb,#1d4ed8);
        }

        .card-green {
            background: linear-gradient(135deg,#16a34a,#15803d);
        }

        .card-orange {
            background: linear-gradient(135deg,#ea580c,#c2410c);
        }

        .card-purple {
            background: linear-gradient(135deg,#9333ea,#7e22ce);
        }

        /* Table Card */

        .content-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-top: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,.05);
        }

        /* Logout */

        .logout-btn {
            width: calc(100% - 40px);
            margin: 20px;
        }

        .content-card {
            background: #fff;
            border-radius: 18px;
            padding: 30px;
            margin-top: 25px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            border: 1px solid #e5e7eb;
        }

        .table thead th {
            background: #f8fafc;
            color: #334155;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .btn-sm {
            margin-right: 4px;
        }

        .badge {
            padding: 8px 12px;
            font-size: 12px;
        }

        /* Responsive */

        @media(max-width:991px){

            .sidebar{
                width:220px;
            }

            .main-content{
                margin-left:220px;
            }

        }

        @media(max-width:768px){

            .sidebar{
                display:none;
            }

            .main-content{
                margin-left:0;
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
                Dashboard
            </a>

            <a href="{{ route('schools.index') }}"
            class="{{ request()->routeIs('schools.*') ? 'active-menu' : '' }}">
                <i class="bi bi-buildings"></i>
                Schools
            </a>

            <a href="#">
                <i class="bi bi-people"></i>
                Users
            </a>

            <a href="#">
                <i class="bi bi-shield-lock"></i>
                Roles
            </a>

            <a href="#">
                <i class="bi bi-file-earmark-text"></i>
                Reports
            </a>

            <a href="#">
                <i class="bi bi-gear"></i>
                Settings
            </a>

        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button class="btn btn-danger logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </button>
        </form>

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

</body>

</html>
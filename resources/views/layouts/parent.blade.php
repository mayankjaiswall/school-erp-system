<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EduERP')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{background:#f8fafc;font-family:'Segoe UI',sans-serif;overflow-x:hidden}
        .sidebar{position:fixed;top:0;left:0;width:290px;height:100vh;background:linear-gradient(180deg,rgba(37,99,235,.95),rgba(15,23,42,1)),#0f172a;color:#fff;display:flex;flex-direction:column;z-index:1000;box-shadow:8px 0 30px rgba(0,0,0,.15)}
        .logo{padding:24px 24px 20px;font-size:28px;font-weight:700;border-bottom:1px solid rgba(255,255,255,.08);line-height:1}
        .logo span{color:#facc15}.logo small{display:block;color:#bfdbfe;font-size:12px;font-weight:600;letter-spacing:.08em;margin-top:8px;text-transform:uppercase}
        .sidebar-menu{flex:1;padding:18px 14px;overflow-y:auto}.sidebar-section{margin-bottom:16px}.sidebar-section-title{color:#93c5fd;font-size:11px;font-weight:700;letter-spacing:.08em;margin:18px 12px 8px;text-transform:uppercase}
        .sidebar-menu a{display:flex;align-items:center;gap:12px;color:#dbeafe;text-decoration:none;padding:11px 14px;border-radius:10px;margin-bottom:4px;transition:.25s ease;font-weight:500;min-height:42px}
        .sidebar-menu a i{align-items:center;background:rgba(219,234,254,.12);border-radius:8px;color:#bfdbfe;display:inline-flex;flex:0 0 30px;font-size:16px;height:30px;justify-content:center;width:30px}
        .sidebar-menu a:hover{background:rgba(37,99,235,.38);color:#fff;transform:translateX(4px)}
        .sidebar-menu a.active-menu{background:#f59e0b;color:#111827;font-weight:700;box-shadow:0 10px 24px rgba(245,158,11,.25)}
        .sidebar-menu a.active-menu i{background:rgba(17,24,39,.12);color:#111827}.logout-wrapper{padding:20px;border-top:1px solid rgba(255,255,255,.08)}.logout-btn{width:100%;border-radius:12px;padding:12px;font-weight:600}
        .main-content{margin-left:290px;min-height:100vh}.topbar{background:#fff;padding:20px 30px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #e2e8f0;box-shadow:0 4px 15px rgba(0,0,0,.04)}
        .topbar h4{margin:0;font-size:28px;font-weight:700;color:#0f172a}.topbar-right{display:flex;align-items:center;gap:18px}.topbar-right i{font-size:20px;color:#475569}.topbar-right img{width:44px;height:44px;border-radius:50%;border:3px solid #dbeafe}
        .page-content{padding:30px}.content-card{background:#fff;border-radius:18px;padding:25px;margin-top:25px;box-shadow:0 10px 30px rgba(15,23,42,.08);border:1px solid #e5e7eb}
        .stats-card{color:#fff;padding:25px;border-radius:18px;position:relative;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,.08)}.stats-card h2{font-size:34px;margin:10px 0;font-weight:700}.stats-card::after{content:'';position:absolute;width:100px;height:100px;background:rgba(255,255,255,.15);border-radius:50%;top:-20px;right:-20px}
        .card-blue{background:linear-gradient(135deg,#2563eb,#1d4ed8)}.card-green{background:linear-gradient(135deg,#16a34a,#15803d)}.card-orange{background:linear-gradient(135deg,#ea580c,#c2410c)}.card-purple{background:linear-gradient(135deg,#9333ea,#7e22ce)}
        .table thead th{background:#f8fafc;color:#334155;font-weight:600;border-bottom:2px solid #e2e8f0}.table tbody tr:hover{background:#f8fafc}.badge{padding:8px 12px;font-size:12px}
        @media(max-width:991px){.sidebar{width:245px}.main-content{margin-left:245px}}@media(max-width:768px){.sidebar{display:none}.main-content{margin-left:0}.topbar{padding:16px 20px}.page-content{padding:20px}.topbar h4{font-size:22px}}
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">Edu<span>ERP</span><small>Parent Panel</small></div>
        <div class="sidebar-menu">
            <div class="sidebar-section">
                <a href="{{ route('parent.dashboard') }}" class="{{ request()->routeIs('parent.dashboard') ? 'active-menu' : '' }}"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-section-title">My Child</div>
                <a href="{{ route('parent.children') }}" class="{{ request()->routeIs('parent.children') ? 'active-menu' : '' }}"><i class="bi bi-people"></i><span>My Children</span></a>
                <a href="{{ route('parent.attendance') }}" class="{{ request()->routeIs('parent.attendance') ? 'active-menu' : '' }}"><i class="bi bi-calendar2-check"></i><span>Attendance</span></a>
                <a href="{{ route('parent.results') }}" class="{{ request()->routeIs('parent.results') ? 'active-menu' : '' }}"><i class="bi bi-bar-chart-line"></i><span>Results</span></a>
                <a href="{{ route('parent.report-cards') }}" class="{{ request()->routeIs('parent.report-cards*') ? 'active-menu' : '' }}"><i class="bi bi-award"></i><span>Report Cards</span></a>
                <a href="{{ route('parent.remarks') }}" class="{{ request()->routeIs('parent.remarks') ? 'active-menu' : '' }}"><i class="bi bi-chat-left-text"></i><span>Teacher Remarks</span></a>
            </div>
            <div class="sidebar-section">
                <a href="{{ route('parent.profile') }}" class="{{ request()->routeIs('parent.profile') ? 'active-menu' : '' }}"><i class="bi bi-person-circle"></i><span>Profile</span></a>
            </div>
        </div>
        <div class="logout-wrapper">
            <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="btn btn-danger logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</button></form>
        </div>
    </div>
    <div class="main-content">
        <div class="topbar">
            <h4>@yield('page-title')</h4>
            <div class="topbar-right">
                <i class="bi bi-bell fs-5"></i>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Parent') }}&background=2563eb&color=fff">
                <strong>{{ auth()->user()->name ?? 'Parent' }}</strong>
            </div>
        </div>
        <div class="page-content">@yield('content')</div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>

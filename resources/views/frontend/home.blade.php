<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduERP — Smart School Management System</title>
    <meta name="description" content="A modern, all-in-one school ERP system to manage students, teachers, attendance, results, and more.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:    #4f46e5;
            --primary-dk: #3730a3;
            --accent:     #f59e0b;
            --accent-lt:  #fbbf24;
            --success:    #10b981;
            --dark:       #0f172a;
            --dark2:      #1e293b;
            --grey:       #64748b;
            --light:      #f8fafc;
            --white:      #ffffff;
            --grad1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --grad2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --grad3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --grad4: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --grad5: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --shadow-sm: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
            --shadow:    0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);
            --shadow-lg: 0 20px 60px -10px rgba(79,70,229,.25);
            --radius: 16px;
            --radius-sm: 10px;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background: var(--white);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ─── Scrollbar ─────────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 3px; }

        /* ─── Utility ───────────────────────────── */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        .section-tag {
            display: inline-block;
            background: rgba(79,70,229,.1);
            color: var(--primary);
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 50px;
            margin-bottom: 16px;
        }
        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(1.75rem, 3vw, 2.5rem);
            font-weight: 800;
            color: var(--dark);
            line-height: 1.2;
            margin-bottom: 16px;
        }
        .section-subtitle {
            font-size: 1.05rem;
            color: var(--grey);
            max-width: 560px;
            margin: 0 auto 56px;
        }
        .text-center { text-align: center; }
        .gradient-text {
            background: var(--grad1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            border-radius: 50px;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all .3s ease;
            white-space: nowrap;
        }
        .btn-primary {
            background: var(--grad1);
            color: var(--white);
            box-shadow: 0 8px 25px rgba(79,70,229,.4);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 35px rgba(79,70,229,.5); }
        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        .btn-outline:hover { background: var(--primary); color: var(--white); transform: translateY(-2px); }
        .btn-white {
            background: var(--white);
            color: var(--primary);
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
        }
        .btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,.2); }
        .btn-accent {
            background: linear-gradient(135deg, var(--accent) 0%, #ff6b35 100%);
            color: var(--white);
            box-shadow: 0 8px 25px rgba(245,158,11,.4);
        }
        .btn-accent:hover { transform: translateY(-2px); box-shadow: 0 12px 35px rgba(245,158,11,.5); }

        /* ─── Navbar ────────────────────────────── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            padding: 18px 0;
            transition: all .4s ease;
        }
        .navbar.scrolled {
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0,0,0,.1);
            padding: 12px 0;
        }
        .navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .logo-icon {
            width: 42px; height: 42px;
            background: var(--grad1);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            font-weight: 900;
            flex-shrink: 0;
        }
        .logo-text { font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 1.3rem; }
        .logo-text span { color: var(--primary); }
        .logo-text small { display: block; font-size: .62rem; font-weight: 500; color: var(--grey); letter-spacing: .08em; text-transform: uppercase; }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 36px;
            list-style: none;
        }
        .nav-links a {
            text-decoration: none;
            color: var(--dark2);
            font-size: .9rem;
            font-weight: 500;
            position: relative;
            transition: color .3s;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px; left: 0;
            width: 0; height: 2px;
            background: var(--grad1);
            border-radius: 2px;
            transition: width .3s ease;
        }
        .nav-links a:hover { color: var(--primary); }
        .nav-links a:hover::after { width: 100%; }
        .navbar-white .nav-links a { color: rgba(255,255,255,.85); }
        .navbar-white .nav-links a:hover { color: #fff; }
        .navbar-white .logo-text { color: #fff; }
        .navbar-white .logo-text small { color: rgba(255,255,255,.6); }

        .nav-cta { display: flex; align-items: center; gap: 12px; }
        .btn-nav-login {
            padding: 9px 22px;
            font-size: .85rem;
            background: transparent;
            color: var(--white);
            border: 2px solid rgba(255,255,255,.5);
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all .3s;
        }
        .btn-nav-login:hover { background: rgba(255,255,255,.15); }
        .navbar.scrolled .btn-nav-login { color: var(--dark); border-color: var(--dark2); }
        .navbar.scrolled .btn-nav-login:hover { background: var(--dark); color: #fff; }
        .btn-nav-get-started {
            padding: 9px 22px;
            font-size: .85rem;
            background: var(--white);
            color: var(--primary);
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all .3s;
            box-shadow: 0 4px 15px rgba(0,0,0,.1);
        }
        .btn-nav-get-started:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,0,0,.15); }
        .navbar.scrolled .btn-nav-get-started { background: var(--grad1); color: var(--white); }

        .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; }
        .hamburger span { width: 24px; height: 2px; background: var(--dark); border-radius: 2px; transition: all .3s; }

        /* ─── Hero ──────────────────────────────── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
            background: var(--dark);
        }
        .hero-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #312e81 70%, #4c1d95 100%);
        }
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .5;
            animation: floatOrb 8s ease-in-out infinite;
        }
        .hero-orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, #6366f1, transparent); top: -100px; right: -100px; animation-delay: 0s; }
        .hero-orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, #a855f7, transparent); bottom: -80px; left: -80px; animation-delay: 3s; }
        .hero-orb-3 { width: 300px; height: 300px; background: radial-gradient(circle, #f59e0b, transparent); top: 50%; left: 50%; transform: translate(-50%,-50%); animation-delay: 6s; opacity: .25; }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.05); }
        }

        .hero .container {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 60px;
            padding-top: 100px;
            padding-bottom: 60px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.2);
            color: #fff;
            font-size: .78rem;
            font-weight: 600;
            padding: 7px 16px;
            border-radius: 50px;
            margin-bottom: 24px;
            letter-spacing: .06em;
        }
        .hero-badge .dot { width: 8px; height: 8px; background: var(--success); border-radius: 50%; animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,.5); } 50% { box-shadow: 0 0 0 8px rgba(16,185,129,0); } }

        .hero-title {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2.2rem, 4.5vw, 3.6rem);
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 20px;
        }
        .hero-title .highlight {
            background: linear-gradient(90deg, #fbbf24, #f472b6, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            color: rgba(255,255,255,.72);
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 36px;
            max-width: 480px;
        }

        .hero-cta { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 48px; }

        .hero-stats {
            display: flex;
            gap: 32px;
            flex-wrap: wrap;
        }
        .hero-stat { text-align: left; }
        .hero-stat-num {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }
        .hero-stat-num span { color: var(--accent-lt); }
        .hero-stat-label { font-size: .78rem; color: rgba(255,255,255,.55); font-weight: 500; margin-top: 4px; text-transform: uppercase; letter-spacing: .08em; }

        /* Hero Visual */
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .hero-card-main {
            background: rgba(255,255,255,.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 24px;
            padding: 32px;
            width: 100%;
            max-width: 420px;
            animation: floatCard 4s ease-in-out infinite;
        }
        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .dashboard-title { color: #fff; font-weight: 700; font-size: 1rem; }
        .dash-badge { background: rgba(16,185,129,.2); color: #34d399; font-size: .72rem; font-weight: 700; padding: 4px 12px; border-radius: 50px; border: 1px solid rgba(52,211,153,.3); }

        .dash-metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }
        .dash-metric {
            background: rgba(255,255,255,.07);
            border-radius: 14px;
            padding: 16px;
            border: 1px solid rgba(255,255,255,.08);
        }
        .dash-metric-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem;
            margin-bottom: 10px;
        }
        .dm-blue { background: rgba(99,102,241,.3); }
        .dm-green { background: rgba(16,185,129,.3); }
        .dm-yellow { background: rgba(245,158,11,.3); }
        .dm-pink { background: rgba(244,63,94,.3); }
        .dash-metric-value { color: #fff; font-weight: 700; font-size: 1.3rem; font-family: 'Poppins',sans-serif; }
        .dash-metric-label { color: rgba(255,255,255,.5); font-size: .72rem; margin-top: 2px; }

        .dash-progress-label { color: rgba(255,255,255,.7); font-size: .78rem; margin-bottom: 8px; display: flex; justify-content: space-between; }
        .dash-progress-bar { background: rgba(255,255,255,.1); border-radius: 50px; height: 8px; overflow: hidden; margin-bottom: 12px; }
        .dash-progress-fill { height: 100%; border-radius: 50px; transition: width 1.5s ease; }
        .fill-indigo { background: linear-gradient(90deg, #6366f1, #a855f7); }
        .fill-green { background: linear-gradient(90deg, #10b981, #34d399); }
        .fill-amber { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

        .floating-badge {
            position: absolute;
            background: var(--white);
            border-radius: 14px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,.2);
            animation: floatBadge 3s ease-in-out infinite;
        }
        @keyframes floatBadge {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(2deg); }
        }
        .fb-1 { top: 20px; left: -30px; animation-delay: .5s; }
        .fb-2 { bottom: 40px; right: -30px; animation-delay: 1.5s; }
        .floating-badge-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .9rem; }
        .fbi-green { background: rgba(16,185,129,.15); color: #10b981; }
        .fbi-purple { background: rgba(99,102,241,.15); color: #6366f1; }
        .floating-badge-text strong { display: block; font-size: .82rem; font-weight: 700; color: var(--dark); }
        .floating-badge-text small { font-size: .7rem; color: var(--grey); }

        /* ─── Wave Separator ────────────────────── */
        .wave-sep { display: block; width: 100%; overflow: hidden; line-height: 0; }
        .wave-sep svg { display: block; }

        /* ─── Stats Bar ─────────────────────────── */
        .stats-bar {
            background: var(--grad1);
            padding: 48px 0;
            position: relative;
            overflow: hidden;
        }
        .stats-bar::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            position: relative;
            z-index: 1;
        }
        .stat-item {
            text-align: center;
            padding: 0 24px;
            position: relative;
        }
        .stat-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0; top: 50%;
            transform: translateY(-50%);
            height: 60%;
            width: 1px;
            background: rgba(255,255,255,.25);
        }
        .stat-number {
            font-family: 'Poppins', sans-serif;
            font-size: 2.8rem;
            font-weight: 900;
            color: #fff;
            line-height: 1;
            margin-bottom: 8px;
        }
        .stat-number sup { font-size: 1.4rem; vertical-align: super; }
        .stat-number span { color: var(--accent-lt); }
        .stat-desc { color: rgba(255,255,255,.75); font-size: .9rem; font-weight: 500; }
        .stat-icon { font-size: 1.6rem; color: rgba(255,255,255,.4); margin-bottom: 10px; }

        /* ─── Features ──────────────────────────── */
        .features { padding: 100px 0; background: #fff; }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }
        .feature-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius);
            padding: 32px 28px;
            transition: all .3s ease;
            position: relative;
            overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--grad1);
            transform: scaleX(0);
            transition: transform .3s ease;
        }
        .feature-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-lg); border-color: transparent; }
        .feature-card:hover::before { transform: scaleX(1); }
        .feature-icon {
            width: 60px; height: 60px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 20px;
        }
        .fi-1 { background: rgba(99,102,241,.12); color: #6366f1; }
        .fi-2 { background: rgba(16,185,129,.12); color: #10b981; }
        .fi-3 { background: rgba(245,158,11,.12); color: #f59e0b; }
        .fi-4 { background: rgba(244,63,94,.12); color: #f43f5e; }
        .fi-5 { background: rgba(168,85,247,.12); color: #a855f7; }
        .fi-6 { background: rgba(14,165,233,.12); color: #0ea5e9; }

        .feature-title { font-family: 'Poppins',sans-serif; font-size: 1.1rem; font-weight: 700; margin-bottom: 10px; color: var(--dark); }
        .feature-desc { font-size: .9rem; color: var(--grey); line-height: 1.65; }
        .feature-link { display: inline-flex; align-items: center; gap: 6px; font-size: .85rem; font-weight: 600; color: var(--primary); margin-top: 16px; text-decoration: none; transition: gap .2s; }
        .feature-link:hover { gap: 10px; }

        /* ─── How It Works ──────────────────────── */
        .how-it-works { padding: 100px 0; background: var(--light); }
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            position: relative;
        }
        .steps-grid::before {
            content: '';
            position: absolute;
            top: 50px; left: calc(16.66% + 20px); right: calc(16.66% + 20px);
            height: 2px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
            z-index: 0;
        }
        .step-card {
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .step-number {
            width: 80px; height: 80px;
            background: var(--grad1);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            font-family: 'Poppins', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: #fff;
            box-shadow: 0 8px 25px rgba(79,70,229,.35);
            position: relative;
        }
        .step-number::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px dashed rgba(79,70,229,.3);
            animation: spinBorder 8s linear infinite;
        }
        @keyframes spinBorder { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .step-title { font-family: 'Poppins',sans-serif; font-size: 1.1rem; font-weight: 700; margin-bottom: 12px; color: var(--dark); }
        .step-desc { font-size: .9rem; color: var(--grey); line-height: 1.7; }

        /* ─── Why Choose Us ─────────────────────── */
        .why-us { padding: 100px 0; background: #fff; overflow: hidden; }
        .why-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
        .why-visual {
            position: relative;
        }
        .why-image-wrap {
            background: var(--grad1);
            border-radius: 24px;
            padding: 8px;
            box-shadow: var(--shadow-lg);
        }
        .why-image-inner {
            background: rgba(255,255,255,.15);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            padding: 32px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .why-card {
            background: rgba(255,255,255,.2);
            border-radius: 14px;
            padding: 20px;
            border: 1px solid rgba(255,255,255,.25);
        }
        .why-card-icon { font-size: 1.6rem; margin-bottom: 10px; }
        .why-card-title { color: #fff; font-weight: 700; font-size: .9rem; margin-bottom: 4px; }
        .why-card-val { color: rgba(255,255,255,.7); font-size: .8rem; }
        .why-badge-float {
            position: absolute;
            background: var(--white);
            border-radius: 14px;
            padding: 14px 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,.15);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .why-badge-float.wbf-1 { bottom: -20px; right: -20px; }
        .why-badge-float.wbf-2 { top: -20px; left: -20px; }
        .wbf-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
        .wbf-green { background: rgba(16,185,129,.15); color: #10b981; }
        .wbf-indigo { background: rgba(99,102,241,.15); color: #6366f1; }
        .wbf-text strong { display: block; font-weight: 700; font-size: .85rem; color: var(--dark); }
        .wbf-text small { color: var(--grey); font-size: .75rem; }

        .why-content .section-subtitle { margin: 0 0 32px; }
        .why-list { list-style: none; }
        .why-list li {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 20px;
            padding: 16px;
            border-radius: var(--radius-sm);
            transition: background .2s;
        }
        .why-list li:hover { background: var(--light); }
        .why-list-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .9rem; flex-shrink: 0; margin-top: 2px; }
        .wli-1 { background: rgba(99,102,241,.12); color: #6366f1; }
        .wli-2 { background: rgba(16,185,129,.12); color: #10b981; }
        .wli-3 { background: rgba(245,158,11,.12); color: #f59e0b; }
        .wli-4 { background: rgba(244,63,94,.12); color: #f43f5e; }
        .why-list-text strong { display: block; font-weight: 700; font-size: .95rem; color: var(--dark); margin-bottom: 4px; }
        .why-list-text span { font-size: .87rem; color: var(--grey); line-height: 1.5; }

        /* ─── Testimonials ──────────────────────── */
        .testimonials { padding: 100px 0; background: var(--light); }
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }
        .testimonial-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 32px;
            box-shadow: var(--shadow-sm);
            transition: all .3s ease;
            position: relative;
        }
        .testimonial-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); }
        .testimonial-card::before { content: '\201C'; font-size: 6rem; color: rgba(79,70,229,.1); font-family: Georgia, serif; position: absolute; top: -10px; left: 20px; line-height: 1; }
        .testimonial-stars { color: var(--accent); font-size: .85rem; margin-bottom: 16px; }
        .testimonial-text { font-size: .92rem; color: #334155; line-height: 1.75; margin-bottom: 24px; position: relative; z-index: 1; }
        .testimonial-author { display: flex; align-items: center; gap: 14px; }
        .testimonial-avatar {
            width: 48px; height: 48px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            font-family: 'Poppins', sans-serif;
        }
        .av-1 { background: var(--grad1); }
        .av-2 { background: var(--grad4); }
        .av-3 { background: var(--grad5); }
        .testimonial-name { font-weight: 700; font-size: .9rem; color: var(--dark); }
        .testimonial-role { font-size: .78rem; color: var(--grey); }

        /* ─── Modules / Programs ────────────────── */
        .modules { padding: 100px 0; background: #fff; }
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .module-card {
            border-radius: 16px;
            padding: 28px 22px;
            text-align: center;
            cursor: pointer;
            transition: all .3s ease;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }
        .module-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--grad1);
            opacity: 0;
            transition: opacity .3s;
        }
        .module-card:hover::after { opacity: 1; }
        .module-card:hover .mc-icon,
        .module-card:hover .mc-title,
        .module-card:hover .mc-desc { color: #fff; }
        .module-card:hover .mc-icon-wrap { background: rgba(255,255,255,.2); border-color: rgba(255,255,255,.3); }
        .module-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: transparent; }
        .mc-icon-wrap {
            width: 64px; height: 64px;
            border-radius: 18px;
            background: rgba(79,70,229,.1);
            border: 1px solid rgba(79,70,229,.15);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 18px;
            transition: all .3s;
            position: relative; z-index: 1;
        }
        .mc-icon { font-size: 1.6rem; color: var(--primary); transition: color .3s; position: relative; z-index: 1; }
        .mc-title { font-family: 'Poppins',sans-serif; font-weight: 700; font-size: .95rem; margin-bottom: 8px; color: var(--dark); transition: color .3s; position: relative; z-index: 1; }
        .mc-desc { font-size: .8rem; color: var(--grey); line-height: 1.55; transition: color .3s; position: relative; z-index: 1; }

        /* Pricing */
        .pricing {
            padding: 100px 0;
            background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
            position: relative;
            overflow: hidden;
        }
        .pricing::before {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(79,70,229,.12), transparent 65%);
            top: -160px;
            right: -120px;
        }
        .pricing::after {
            content: '';
            position: absolute;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245,158,11,.12), transparent 65%);
            bottom: -160px;
            left: -120px;
        }
        .pricing .container {
            position: relative;
            z-index: 1;
        }
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            align-items: stretch;
        }
        .pricing-card {
            background: rgba(255,255,255,.92);
            border: 1px solid #e2e8f0;
            border-radius: 22px;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            min-height: 100%;
            overflow: hidden;
            padding: 30px;
            position: relative;
            transition: all .3s ease;
        }
        .pricing-card:hover {
            border-color: rgba(79,70,229,.25);
            box-shadow: var(--shadow-lg);
            transform: translateY(-8px);
        }
        .pricing-card.featured {
            border-color: rgba(79,70,229,.45);
            box-shadow: 0 24px 70px -18px rgba(79,70,229,.35);
        }
        .pricing-card.featured::before {
            content: 'Popular';
            position: absolute;
            top: 18px;
            right: 18px;
            background: var(--grad1);
            border-radius: 50px;
            color: #fff;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .08em;
            padding: 6px 12px;
            text-transform: uppercase;
        }
        .pricing-icon {
            width: 54px;
            height: 54px;
            align-items: center;
            background: rgba(79,70,229,.1);
            border: 1px solid rgba(79,70,229,.15);
            border-radius: 16px;
            color: var(--primary);
            display: flex;
            font-size: 1.25rem;
            justify-content: center;
            margin-bottom: 22px;
        }
        .pricing-name {
            color: var(--dark);
            font-family: 'Poppins', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .pricing-desc {
            color: var(--grey);
            font-size: .9rem;
            line-height: 1.7;
            min-height: 54px;
        }
        .pricing-price {
            align-items: flex-end;
            display: flex;
            gap: 6px;
            margin: 26px 0 8px;
        }
        .pricing-currency {
            color: var(--primary);
            font-size: 1.05rem;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .pricing-amount {
            color: var(--dark);
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2.1rem, 4vw, 3rem);
            font-weight: 900;
            line-height: 1;
        }
        .pricing-duration {
            color: var(--grey);
            font-size: .88rem;
            margin-bottom: 26px;
        }
        .pricing-features {
            border-top: 1px solid #e2e8f0;
            display: grid;
            gap: 12px;
            list-style: none;
            margin: 0 0 28px;
            padding-top: 22px;
        }
        .pricing-features li {
            align-items: center;
            color: #334155;
            display: flex;
            font-size: .88rem;
            gap: 10px;
        }
        .pricing-features i {
            color: var(--success);
            font-size: .9rem;
        }
        .pricing-card .btn {
            justify-content: center;
            margin-top: auto;
            width: 100%;
        }
        .pricing-empty {
            background: #fff;
            border: 1px dashed #cbd5e1;
            border-radius: 22px;
            color: var(--grey);
            padding: 46px 28px;
            text-align: center;
        }
        .pricing-empty i {
            color: #cbd5e1;
            display: block;
            font-size: 2.8rem;
            margin-bottom: 14px;
        }

        /* ─── CTA Banner ────────────────────────── */
        .cta-banner {
            padding: 100px 0;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
            position: relative;
            overflow: hidden;
        }
        .cta-banner::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,.3), transparent 60%);
            top: -200px; right: -100px;
        }
        .cta-banner::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245,158,11,.2), transparent 60%);
            bottom: -100px; left: -50px;
        }
        .cta-banner .container { position: relative; z-index: 1; text-align: center; }
        .cta-banner .section-tag { background: rgba(255,255,255,.1); color: rgba(255,255,255,.8); }
        .cta-banner .section-title { color: #fff; }
        .cta-banner .section-subtitle { color: rgba(255,255,255,.65); margin-bottom: 40px; }
        .cta-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
        .cta-chips {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-top: 40px;
        }
        .cta-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            padding: 8px 18px;
            border-radius: 50px;
            color: rgba(255,255,255,.8);
            font-size: .82rem;
            font-weight: 500;
        }
        .cta-chip i { color: var(--accent-lt); }

        /* ─── Footer ────────────────────────────── */
        .footer { background: #0f172a; padding: 80px 0 0; }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 60px;
            padding-bottom: 60px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .footer-brand {}
        .footer-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 18px; text-decoration: none; }
        .footer-logo-icon { width: 40px; height: 40px; background: var(--grad1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 900; font-size: 1rem; }
        .footer-logo-text { font-family: 'Poppins',sans-serif; font-weight: 800; font-size: 1.2rem; color: #fff; }
        .footer-logo-text span { color: #818cf8; }
        .footer-desc { color: rgba(255,255,255,.5); font-size: .88rem; line-height: 1.7; margin-bottom: 24px; }
        .footer-socials { display: flex; gap: 10px; }
        .social-btn {
            width: 38px; height: 38px;
            background: rgba(255,255,255,.07);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.55);
            font-size: .85rem;
            text-decoration: none;
            transition: all .3s;
        }
        .social-btn:hover { background: var(--primary); color: #fff; transform: translateY(-3px); }

        .footer-col h5 { font-family: 'Poppins',sans-serif; font-weight: 700; color: #fff; margin-bottom: 20px; font-size: .95rem; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 12px; }
        .footer-links a { text-decoration: none; color: rgba(255,255,255,.5); font-size: .87rem; transition: color .2s; display: flex; align-items: center; gap: 6px; }
        .footer-links a:hover { color: #818cf8; }
        .footer-links a::before { content: '→'; font-size: .7rem; opacity: 0; transition: opacity .2s, transform .2s; transform: translateX(-4px); }
        .footer-links a:hover::before { opacity: 1; transform: translateX(0); }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 0;
        }
        .footer-copy { color: rgba(255,255,255,.35); font-size: .82rem; }
        .footer-copy a { color: #818cf8; text-decoration: none; }
        .footer-badges { display: flex; gap: 12px; }
        .footer-badge { font-size: .72rem; color: rgba(255,255,255,.35); background: rgba(255,255,255,.05); border-radius: 6px; padding: 5px 10px; }

        /* ─── Back to top ───────────────────────── */
        .back-to-top {
            position: fixed;
            bottom: 32px; right: 32px;
            width: 48px; height: 48px;
            background: var(--grad1);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: .9rem;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(79,70,229,.4);
            opacity: 0;
            visibility: hidden;
            transition: all .3s;
            z-index: 999;
        }
        .back-to-top.visible { opacity: 1; visibility: visible; }
        .back-to-top:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(79,70,229,.5); }

        /* ─── Animations ────────────────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity .7s ease, transform .7s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: .1s; }
        .reveal-delay-2 { transition-delay: .2s; }
        .reveal-delay-3 { transition-delay: .3s; }
        .reveal-delay-4 { transition-delay: .4s; }
        .reveal-delay-5 { transition-delay: .5s; }

        /* ─── Responsive ────────────────────────── */
        @media (max-width: 1024px) {
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .testimonials-grid { grid-template-columns: repeat(2, 1fr); }
            .modules-grid { grid-template-columns: repeat(2, 1fr); }
            .pricing-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .why-grid { gap: 40px; }
            .steps-grid::before { display: none; }
        }
        @media (max-width: 768px) {
            .hero .container { grid-template-columns: 1fr; text-align: center; padding-top: 120px; }
            .hero-desc { margin-left: auto; margin-right: auto; }
            .hero-cta { justify-content: center; }
            .hero-stats { justify-content: center; }
            .hero-visual { display: none; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 32px 0; }
            .stat-item:nth-child(2)::after { display: none; }
            .features-grid { grid-template-columns: 1fr; }
            .steps-grid { grid-template-columns: 1fr; }
            .why-grid { grid-template-columns: 1fr; }
            .why-visual { display: none; }
            .testimonials-grid { grid-template-columns: 1fr; }
            .modules-grid { grid-template-columns: repeat(2, 1fr); }
            .pricing-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; gap: 32px; }
            .footer-bottom { flex-direction: column; gap: 16px; text-align: center; }
            .nav-links { display: none; }
            .hamburger { display: flex; }
            .nav-cta .btn-nav-login { display: none; }
        }
    </style>
</head>
<body>

<!-- ═══════════════════════════════════════════════
     NAVBAR
════════════════════════════════════════════════ -->
<nav class="navbar navbar-white" id="navbar">
    <div class="container">
        <a href="/" class="logo">
            <div class="logo-icon">E</div>
            <div class="logo-text">
                Edu<span>ERP</span>
                <small>School Management System</small>
            </div>
        </a>

        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#modules">Modules</a></li>
            <li><a href="#pricing">Pricing</a></li>
            <li><a href="#how-it-works">How It Works</a></li>
            <li><a href="#testimonials">Reviews</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>

        <div class="nav-cta">
            <a href="{{ route('login') }}" class="btn-nav-login">Log In</a>
            <a href="#" class="btn-nav-get-started">Get Started</a>
        </div>
        <div class="hamburger" id="hamburger">
            <span></span><span></span><span></span>
        </div>
    </div>
</nav>

<!-- ═══════════════════════════════════════════════
     HERO
════════════════════════════════════════════════ -->
<section class="hero" id="home">
    <div class="hero-bg"></div>
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>

    <div class="container">
        <!-- Content -->
        <div class="hero-content">
            <div class="hero-badge">
                <div class="dot"></div>
                Trusted by 500+ Schools Worldwide
            </div>

            <h1 class="hero-title">
                A Smart Way to<br>
                Manage Your<br>
                <span class="highlight">School's Future</span>
            </h1>

            <p class="hero-desc">
                EduERP is an all-in-one school management platform that streamlines
                admissions, academics, attendance, finance, and communication —
                so you can focus on what matters most: <strong style="color:#fbbf24">education.</strong>
            </p>

            <div class="hero-cta">
                <a href="#" class="btn btn-accent">
                    <i class="fas fa-rocket"></i> Start Free Trial
                </a>
                <a href="#" class="btn btn-white">
                    <i class="fas fa-play-circle"></i> Watch Demo
                </a>
            </div>

            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-num">500<span>+</span></div>
                    <div class="hero-stat-label">Schools</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num">1.2<span>M+</span></div>
                    <div class="hero-stat-label">Students</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num">99<span>%</span></div>
                    <div class="hero-stat-label">Uptime SLA</div>
                </div>
            </div>
        </div>

        <!-- Visual / Dashboard Mockup -->
        <div class="hero-visual">
            <div class="floating-badge fb-1">
                <div class="floating-badge-icon fbi-green"><i class="fas fa-check"></i></div>
                <div class="floating-badge-text">
                    <strong>Attendance Synced</strong>
                    <small>Just now · All Classes</small>
                </div>
            </div>

            <div class="hero-card-main">
                <div class="dashboard-header">
                    <span class="dashboard-title">School Dashboard</span>
                    <span class="dash-badge">● Live</span>
                </div>

                <div class="dash-metrics">
                    <div class="dash-metric">
                        <div class="dash-metric-icon dm-blue"><i class="fas fa-user-graduate" style="color:#818cf8"></i></div>
                        <div class="dash-metric-value">1,248</div>
                        <div class="dash-metric-label">Students</div>
                    </div>
                    <div class="dash-metric">
                        <div class="dash-metric-icon dm-green"><i class="fas fa-chalkboard-teacher" style="color:#34d399"></i></div>
                        <div class="dash-metric-value">86</div>
                        <div class="dash-metric-label">Teachers</div>
                    </div>
                    <div class="dash-metric">
                        <div class="dash-metric-icon dm-yellow"><i class="fas fa-calendar-check" style="color:#fbbf24"></i></div>
                        <div class="dash-metric-value">94.2%</div>
                        <div class="dash-metric-label">Attendance</div>
                    </div>
                    <div class="dash-metric">
                        <div class="dash-metric-icon dm-pink"><i class="fas fa-trophy" style="color:#fb7185"></i></div>
                        <div class="dash-metric-value">98.5%</div>
                        <div class="dash-metric-label">Pass Rate</div>
                    </div>
                </div>

                <div>
                    <div class="dash-progress-label"><span>Math Avg.</span><span style="color:#fff">87%</span></div>
                    <div class="dash-progress-bar"><div class="dash-progress-fill fill-indigo" style="width:87%"></div></div>

                    <div class="dash-progress-label"><span>Science Avg.</span><span style="color:#fff">73%</span></div>
                    <div class="dash-progress-bar"><div class="dash-progress-fill fill-green" style="width:73%"></div></div>

                    <div class="dash-progress-label"><span>English Avg.</span><span style="color:#fff">91%</span></div>
                    <div class="dash-progress-bar"><div class="dash-progress-fill fill-amber" style="width:91%"></div></div>
                </div>
            </div>

            <div class="floating-badge fb-2">
                <div class="floating-badge-icon fbi-purple"><i class="fas fa-bell"></i></div>
                <div class="floating-badge-text">
                    <strong>Fee Collected</strong>
                    <small>₹2.4L today · 12 students</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Wave -->
<span class="wave-sep">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 60" fill="#e8e4ff">
        <path d="M0,30 C360,70 1080,-10 1440,30 L1440,60 L0,60 Z"/>
    </svg>
</span>

<!-- ═══════════════════════════════════════════════
     STATS BAR
════════════════════════════════════════════════ -->
<section class="stats-bar" id="stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item reveal">
                <div class="stat-icon"><i class="fas fa-school"></i></div>
                <div class="stat-number" data-target="500">0<span>+</span></div>
                <div class="stat-desc">Schools Onboarded</div>
            </div>
            <div class="stat-item reveal reveal-delay-1">
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-number" data-target="1200000">0<span>+</span></div>
                <div class="stat-desc">Active Students</div>
            </div>
            <div class="stat-item reveal reveal-delay-2">
                <div class="stat-icon"><i class="fas fa-award"></i></div>
                <div class="stat-number" data-target="99">0<span>%</span></div>
                <div class="stat-desc">Satisfaction Rate</div>
            </div>
            <div class="stat-item reveal reveal-delay-3">
                <div class="stat-icon"><i class="fas fa-headset"></i></div>
                <div class="stat-number">24<span>/7</span></div>
                <div class="stat-desc">Expert Support</div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     FEATURES
════════════════════════════════════════════════ -->
<section class="features" id="features">
    <div class="container">
        <div class="text-center reveal">
            <span class="section-tag">Core Features</span>
            <h2 class="section-title">Everything You Need to<br>Run a <span class="gradient-text">Modern School</span></h2>
            <p class="section-subtitle">Powerful tools built for administrators, teachers, students, and parents — all in one unified platform.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card reveal reveal-delay-1">
                <div class="feature-icon fi-1"><i class="fas fa-users-cog"></i></div>
                <h3 class="feature-title">Student Management</h3>
                <p class="feature-desc">Complete student profiles, enrollment, ID cards, transfer certificates, and academic history in one place.</p>
                <a href="#" class="feature-link">Learn more <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="feature-card reveal reveal-delay-2">
                <div class="feature-icon fi-2"><i class="fas fa-calendar-alt"></i></div>
                <h3 class="feature-title">Smart Attendance</h3>
                <p class="feature-desc">Biometric, RFID, or manual attendance with real-time parent SMS alerts and auto-generated reports.</p>
                <a href="#" class="feature-link">Learn more <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="feature-card reveal reveal-delay-3">
                <div class="feature-icon fi-3"><i class="fas fa-rupee-sign"></i></div>
                <h3 class="feature-title">Fee Management</h3>
                <p class="feature-desc">Flexible fee structures, online payments, receipts, due reminders, and financial dashboards.</p>
                <a href="#" class="feature-link">Learn more <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="feature-card reveal reveal-delay-1">
                <div class="feature-icon fi-4"><i class="fas fa-clipboard-list"></i></div>
                <h3 class="feature-title">Exam & Results</h3>
                <p class="feature-desc">Schedule exams, enter marks, auto-calculate grades, generate report cards, and publish online.</p>
                <a href="#" class="feature-link">Learn more <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="feature-card reveal reveal-delay-2">
                <div class="feature-icon fi-5"><i class="fas fa-book-open"></i></div>
                <h3 class="feature-title">Library System</h3>
                <p class="feature-desc">Book catalog, issue/return tracking, fine management, and digital resource integration.</p>
                <a href="#" class="feature-link">Learn more <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="feature-card reveal reveal-delay-3">
                <div class="feature-icon fi-6"><i class="fas fa-comments"></i></div>
                <h3 class="feature-title">Parent Communication</h3>
                <p class="feature-desc">Instant SMS/email notifications, parent-teacher meetings, complaint portal, and progress sharing.</p>
                <a href="#" class="feature-link">Learn more <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     HOW IT WORKS
════════════════════════════════════════════════ -->
<section class="how-it-works" id="how-it-works">
    <div class="container">
        <div class="text-center reveal">
            <span class="section-tag">Getting Started</span>
            <h2 class="section-title">Up and Running in<br><span class="gradient-text">3 Simple Steps</span></h2>
            <p class="section-subtitle">No complex setup. No IT team needed. Your school can be fully operational in under 24 hours.</p>
        </div>

        <div class="steps-grid">
            <div class="step-card reveal reveal-delay-1">
                <div class="step-number">1</div>
                <h3 class="step-title">Create Your School Account</h3>
                <p class="step-desc">Sign up in minutes, enter your school details, set up roles for admin, teachers, and students with full data import support.</p>
            </div>
            <div class="step-card reveal reveal-delay-2">
                <div class="step-number">2</div>
                <h3 class="step-title">Configure Your Modules</h3>
                <p class="step-desc">Customize attendance, fee structures, exam patterns, timetables, and notifications to perfectly match your school's workflow.</p>
            </div>
            <div class="step-card reveal reveal-delay-3">
                <div class="step-number">3</div>
                <h3 class="step-title">Go Live & Grow</h3>
                <p class="step-desc">Invite staff and students, start managing daily operations seamlessly, and watch productivity and transparency soar.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     WHY CHOOSE US
════════════════════════════════════════════════ -->
<section class="why-us">
    <div class="container">
        <div class="why-grid">
            <div class="why-visual reveal">
                <div class="why-image-wrap">
                    <div class="why-image-inner">
                        <div class="why-card">
                            <div class="why-card-icon">📊</div>
                            <div class="why-card-title">Real-Time Analytics</div>
                            <div class="why-card-val">Live performance data</div>
                        </div>
                        <div class="why-card">
                            <div class="why-card-icon">🔐</div>
                            <div class="why-card-title">Bank-Level Security</div>
                            <div class="why-card-val">256-bit SSL encryption</div>
                        </div>
                        <div class="why-card">
                            <div class="why-card-icon">📱</div>
                            <div class="why-card-title">Mobile First</div>
                            <div class="why-card-val">iOS, Android & Web</div>
                        </div>
                        <div class="why-card">
                            <div class="why-card-icon">☁️</div>
                            <div class="why-card-title">Cloud Powered</div>
                            <div class="why-card-val">Always available, 99% uptime</div>
                        </div>
                    </div>
                </div>
                <div class="why-badge-float wbf-1">
                    <div class="wbf-icon wbf-green"><i class="fas fa-shield-alt"></i></div>
                    <div class="wbf-text">
                        <strong>GDPR Compliant</strong>
                        <small>Your data is always safe</small>
                    </div>
                </div>
                <div class="why-badge-float wbf-2">
                    <div class="wbf-icon wbf-indigo"><i class="fas fa-star"></i></div>
                    <div class="wbf-text">
                        <strong>4.9 / 5 Rating</strong>
                        <small>By 2,000+ admins</small>
                    </div>
                </div>
            </div>

            <div class="why-content reveal reveal-delay-2">
                <span class="section-tag">Why EduERP</span>
                <h2 class="section-title">Built for Schools.<br>Loved by <span class="gradient-text">Educators.</span></h2>
                <p class="section-subtitle">We understand the unique challenges of running a school. EduERP was designed from the ground up with feedback from 500+ school administrators.</p>

                <ul class="why-list">
                    <li>
                        <div class="why-list-icon wli-1"><i class="fas fa-bolt"></i></div>
                        <div class="why-list-text">
                            <strong>Lightning Fast Setup</strong>
                            <span>Import existing student data via Excel, configure your modules, and go live the same day.</span>
                        </div>
                    </li>
                    <li>
                        <div class="why-list-icon wli-2"><i class="fas fa-globe"></i></div>
                        <div class="why-list-text">
                            <strong>Multi-Language Support</strong>
                            <span>Available in 12 languages including Hindi, Tamil, Telugu, Bengali, and more.</span>
                        </div>
                    </li>
                    <li>
                        <div class="why-list-icon wli-3"><i class="fas fa-headset"></i></div>
                        <div class="why-list-text">
                            <strong>Dedicated Onboarding Team</strong>
                            <span>A dedicated success manager walks your team through setup and training at no extra cost.</span>
                        </div>
                    </li>
                    <li>
                        <div class="why-list-icon wli-4"><i class="fas fa-code-branch"></i></div>
                        <div class="why-list-text">
                            <strong>Custom Module Development</strong>
                            <span>Need something specific? We build custom features tailored to your school's requirements.</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     MODULES
════════════════════════════════════════════════ -->
<section class="modules" id="modules">
    <div class="container">
        <div class="text-center reveal">
            <span class="section-tag">Modules</span>
            <h2 class="section-title">All School Operations<br><span class="gradient-text">In One Platform</span></h2>
            <p class="section-subtitle">EduERP covers every aspect of school administration — from gate to classroom to parent.</p>
        </div>

        <div class="modules-grid">
            <div class="module-card reveal reveal-delay-1">
                <div class="mc-icon-wrap"><i class="fas fa-user-graduate mc-icon"></i></div>
                <div class="mc-title">Student Portal</div>
                <div class="mc-desc">Profile, marks, attendance, timetable, assignments</div>
            </div>
            <div class="module-card reveal reveal-delay-2">
                <div class="mc-icon-wrap"><i class="fas fa-chalkboard-teacher mc-icon"></i></div>
                <div class="mc-title">Teacher Portal</div>
                <div class="mc-desc">Classes, attendance, marks entry, lesson plans</div>
            </div>
            <div class="module-card reveal reveal-delay-3">
                <div class="mc-icon-wrap"><i class="fas fa-users mc-icon"></i></div>
                <div class="mc-title">HR & Payroll</div>
                <div class="mc-desc">Staff management, salary, leave, PF/ESI</div>
            </div>
            <div class="module-card reveal reveal-delay-4">
                <div class="mc-icon-wrap"><i class="fas fa-bus mc-icon"></i></div>
                <div class="mc-title">Transport</div>
                <div class="mc-desc">Routes, GPS tracking, driver info, fee collection</div>
            </div>
            <div class="module-card reveal reveal-delay-1">
                <div class="mc-icon-wrap"><i class="fas fa-utensils mc-icon"></i></div>
                <div class="mc-title">Canteen / Hostel</div>
                <div class="mc-desc">Menu, billing, room allocation, mess tracking</div>
            </div>
            <div class="module-card reveal reveal-delay-2">
                <div class="mc-icon-wrap"><i class="fas fa-chart-bar mc-icon"></i></div>
                <div class="mc-title">Reports & Analytics</div>
                <div class="mc-desc">100+ reports, custom dashboards, export to PDF/Excel</div>
            </div>
            <div class="module-card reveal reveal-delay-3">
                <div class="mc-icon-wrap"><i class="fas fa-mobile-alt mc-icon"></i></div>
                <div class="mc-title">Parent App</div>
                <div class="mc-desc">Live updates, fees, attendance, result access</div>
            </div>
            <div class="module-card reveal reveal-delay-4">
                <div class="mc-icon-wrap"><i class="fas fa-video mc-icon"></i></div>
                <div class="mc-title">Online Classes</div>
                <div class="mc-desc">Live classes, recordings, assignments, quizzes</div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     PRICING
════════════════════════════════════════════════ -->
<section class="pricing" id="pricing">
    <div class="container">
        <div class="text-center reveal">
            <span class="section-tag">Subscription Plans</span>
            <h2 class="section-title">Simple Plans for<br><span class="gradient-text">Growing Schools</span></h2>
            <p class="section-subtitle">Choose a plan that matches your school size and operating rhythm. Every active plan is managed directly from the Super Admin panel.</p>
        </div>

        @if($subscriptionPlans->isNotEmpty())
            <div class="pricing-grid">
                @foreach($subscriptionPlans as $plan)
                    <div class="pricing-card reveal reveal-delay-{{ min(($loop->iteration % 5) + 1, 5) }} {{ $plan->is_popular ? 'featured' : '' }}">
                        <div class="pricing-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="pricing-name">{{ $plan->plan_name }}</div>
                        <p class="pricing-desc">
                            {{ $plan->description ?: 'A flexible EduERP subscription plan designed for day-to-day school operations.' }}
                        </p>
                        <div class="pricing-price">
                            <span class="pricing-currency">Rs.</span>
                            <span class="pricing-amount">{{ number_format((float) $plan->price, 0) }}</span>
                        </div>
                        <div class="pricing-duration">
                            For {{ $plan->duration }} {{ \Illuminate\Support\Str::lower($plan->duration_type) }}
                        </div>
                        <ul class="pricing-features">
                            <li><i class="fas fa-check-circle"></i> Complete school ERP access</li>
                            <li><i class="fas fa-check-circle"></i> Student, teacher and parent portals</li>
                            <li><i class="fas fa-check-circle"></i> Attendance, reports and communication tools</li>
                            <li><i class="fas fa-check-circle"></i> Guided onboarding and support</li>
                        </ul>
                        <a href="#contact" class="btn {{ $plan->is_popular ? 'btn-primary' : 'btn-outline' }}">
                            <i class="fas fa-arrow-right"></i>
                            Get Started
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="pricing-empty reveal">
                <i class="fas fa-tags"></i>
                <h3>No subscription plans available yet</h3>
                <p>Active plans created in the Super Admin panel will appear here automatically.</p>
            </div>
        @endif
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials" id="testimonials">
    <div class="container">
        <div class="text-center reveal">
            <span class="section-tag">Testimonials</span>
            <h2 class="section-title">What School Leaders<br><span class="gradient-text">Are Saying</span></h2>
            <p class="section-subtitle">Don't take our word for it — hear from the administrators and educators using EduERP every day.</p>
        </div>

        <div class="testimonials-grid">
            <div class="testimonial-card reveal reveal-delay-1">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"EduERP transformed how we manage our 1,200-student school. Attendance, fees, results — everything is instant. Our parents love the real-time updates. Best investment we've made."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar av-1">RS</div>
                    <div>
                        <div class="testimonial-name">Rajesh Sharma</div>
                        <div class="testimonial-role">Principal, Delhi Public School, Noida</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card reveal reveal-delay-2">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"The fee management module alone saved us 20+ hours per month. Online collection, auto-receipts, and outstanding reports have made our finance team incredibly efficient."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar av-2">PM</div>
                    <div>
                        <div class="testimonial-name">Priya Mehta</div>
                        <div class="testimonial-role">Administrator, Bright Future Academy, Pune</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card reveal reveal-delay-3">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"We switched from a legacy system and the difference is night and day. The UI is clean, onboarding was smooth, and the support team is incredibly responsive."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar av-3">AK</div>
                    <div>
                        <div class="testimonial-name">Anil Kumar</div>
                        <div class="testimonial-role">Director, Sunrise International School, Bangalore</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     CTA BANNER
════════════════════════════════════════════════ -->
<section class="cta-banner" id="contact">
    <div class="container">
        <span class="section-tag">Get Started Today</span>
        <h2 class="section-title">Ready to Transform<br>Your School?</h2>
        <p class="section-subtitle">Join 500+ schools already running smarter with EduERP. Start your free 30-day trial — no credit card required.</p>
        <div class="cta-buttons">
            <a href="#" class="btn btn-accent"><i class="fas fa-rocket"></i> Start Free Trial</a>
            <a href="#" class="btn btn-white"><i class="fas fa-calendar-alt"></i> Schedule a Demo</a>
        </div>
        <div class="cta-chips">
            <span class="cta-chip"><i class="fas fa-check-circle"></i> 30-day free trial</span>
            <span class="cta-chip"><i class="fas fa-check-circle"></i> No credit card needed</span>
            <span class="cta-chip"><i class="fas fa-check-circle"></i> Free data migration</span>
            <span class="cta-chip"><i class="fas fa-check-circle"></i> Dedicated onboarding</span>
            <span class="cta-chip"><i class="fas fa-check-circle"></i> Cancel anytime</span>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════ -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="/" class="footer-logo">
                    <div class="footer-logo-icon">E</div>
                    <div class="footer-logo-text">Edu<span>ERP</span></div>
                </a>
                <p class="footer-desc">The most trusted school management platform, built for the modern educator. Simplify, automate, and elevate your school operations.</p>
                <div class="footer-socials">
                    <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h5>Product</h5>
                <ul class="footer-links">
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Modules</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#">Changelog</a></li>
                    <li><a href="#">Roadmap</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Resources</h5>
                <ul class="footer-links">
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">Video Tutorials</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Case Studies</a></li>
                    <li><a href="#">Help Center</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Company</h5>
                <ul class="footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="footer-copy">&copy; {{ date('Y') }} EduERP. All rights reserved. Made with ❤️ for educators.</p>
            <div class="footer-badges">
                <span class="footer-badge">🔐 SSL Secured</span>
                <span class="footer-badge">☁️ Cloud Hosted</span>
                <span class="footer-badge">🇮🇳 Made in India</span>
            </div>
        </div>
    </div>
</footer>

<!-- Back to top -->
<a href="#home" class="back-to-top" id="backToTop"><i class="fas fa-chevron-up"></i></a>

<script>
    // ── Navbar scroll ──────────────────────────
    const navbar = document.getElementById('navbar');
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 60) {
            navbar.classList.add('scrolled');
            navbar.classList.remove('navbar-white');
            backToTop.classList.add('visible');
        } else {
            navbar.classList.remove('scrolled');
            navbar.classList.add('navbar-white');
            backToTop.classList.remove('visible');
        }
    });

    // ── Scroll reveal ──────────────────────────
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });
    reveals.forEach(el => observer.observe(el));

    // ── Counter animation ──────────────────────
    function animateCounter(el, target, duration = 2000) {
        let start = 0;
        const step = target / (duration / 16);
        const timer = setInterval(() => {
            start += step;
            if (start >= target) { start = target; clearInterval(timer); }
            const suffix = el.querySelector('span').textContent;
            const display = target >= 1000000
                ? (start / 1000000).toFixed(1) + 'M'
                : Math.floor(start).toLocaleString();
            el.innerHTML = display + '<span>' + suffix + '</span>';
        }, 16);
    }

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.animated) {
                entry.target.dataset.animated = 'true';
                const target = parseInt(entry.target.dataset.target);
                if (target) animateCounter(entry.target, target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-number[data-target]').forEach(el => counterObserver.observe(el));

    // ── Smooth scroll for anchor links ─────────
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', e => {
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ── Mobile hamburger (basic toggle) ────────
    document.getElementById('hamburger').addEventListener('click', () => {
        const links = document.querySelector('.nav-links');
        if (links.style.display === 'flex') {
            links.style.display = 'none';
        } else {
            links.style.display = 'flex';
            links.style.flexDirection = 'column';
            links.style.position = 'absolute';
            links.style.top = '70px';
            links.style.left = '0';
            links.style.right = '0';
            links.style.background = '#fff';
            links.style.padding = '20px 24px';
            links.style.boxShadow = '0 10px 30px rgba(0,0,0,.1)';
            links.style.zIndex = '999';
        }
    });
</script>
</body>
</html>

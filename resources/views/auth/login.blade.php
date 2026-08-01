<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In — EduERP</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@800&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@800&display=swap" rel="stylesheet"></noscript>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:    #1d4ed8;
            --primary-dk: #1e3a8a;
            --accent:     #f59e0b;
            --accent-lt:  #fbbf24;
            --dark:       #0f172a;
            --grey:       #64748b;
            --grad1: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
        }

        html, body { height: 100%; }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            min-height: 100dvh;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
        }

        /* ─── Brand panel ───────────────────────── */
        .brand-panel {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0a1628 0%, #0f2748 35%, #123b6b 70%, #1d4ed8 100%);
            padding: 72px 64px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff;
        }
        .brand-glow {
            position: absolute;
            width: 480px; height: 480px;
            border-radius: 50%;
            filter: blur(70px);
            opacity: .3;
            background: radial-gradient(circle, #3b82f6, transparent);
            top: -120px; right: -120px;
        }
        .brand-mark {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 56px;
        }
        .brand-mark-icon {
            width: 38px; height: 38px;
            background: var(--grad1);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: 1rem;
        }
        .brand-mark-text { font-weight: 700; font-size: 1.15rem; }
        .brand-mark-text span { color: var(--accent-lt); }

        .brand-panel h1 {
            position: relative;
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: clamp(1.9rem, 3vw, 2.6rem);
            line-height: 1.25;
            max-width: 460px;
            margin-bottom: 20px;
        }
        .brand-panel p {
            position: relative;
            color: rgba(255,255,255,.65);
            line-height: 1.75;
            max-width: 420px;
            margin-bottom: 40px;
        }

        .brand-points {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .brand-point {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: .92rem;
            color: rgba(255,255,255,.85);
        }
        .brand-point-mark {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--accent-lt);
            flex-shrink: 0;
        }

        /* ─── Form panel ────────────────────────── */
        .form-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 64px;
        }
        .form-inner { width: 100%; max-width: 380px; margin: 0 auto; }

        .mobile-mark {
            display: none;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
            text-decoration: none;
            color: var(--dark);
        }
        .mobile-mark .brand-mark-icon { font-size: .9rem; width: 34px; height: 34px; }
        .mobile-mark span { font-weight: 700; font-size: 1.05rem; color: var(--dark); }
        .mobile-mark strong { color: var(--primary); }

        .form-tag {
            display: inline-block;
            background: rgba(29,78,216,.08);
            color: var(--primary);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 50px;
            margin-bottom: 18px;
        }
        .form-title {
            font-size: 1.7rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .form-subtitle {
            color: var(--grey);
            font-size: .93rem;
            margin-bottom: 32px;
        }

        .error-box {
            display: none;
            align-items: flex-start;
            gap: 10px;
            border-left: 3px solid #dc2626;
            background: #fef2f2;
            color: #991b1b;
            font-size: .87rem;
            padding: 12px 14px;
            border-radius: 6px;
            margin-bottom: 24px;
        }
        .error-box.is-visible { display: flex; }

        .field { margin-bottom: 24px; }
        .field label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }
        .field input[type="text"],
        .field input[type="password"] {
            width: 100%;
            border: none;
            border-bottom: 1.5px solid #e2e8f0;
            padding: 10px 2px;
            font-size: .96rem;
            font-family: inherit;
            color: var(--dark);
            background: transparent;
            outline: none;
            transition: border-color .2s ease;
        }
        .field input:focus { border-bottom-color: var(--primary); }
        .field input::placeholder { color: #94a3b8; }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            font-size: .85rem;
        }
        .remember-me { display: flex; align-items: center; gap: 8px; color: #475569; cursor: pointer; }
        .remember-me input { width: 15px; height: 15px; accent-color: var(--primary); cursor: pointer; }
        .options a { color: var(--primary); font-weight: 600; text-decoration: none; }
        .options a:hover { text-decoration: underline; }

        .login-btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 50px;
            background: var(--grad1);
            color: #fff;
            font-weight: 600;
            font-size: .95rem;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(29,78,216,.3);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .login-btn:hover { transform: translateY(-1px); box-shadow: 0 12px 30px rgba(29,78,216,.4); }
        .login-btn:disabled { cursor: not-allowed; opacity: .7; transform: none; }

        .demo-note {
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            font-size: .8rem;
            color: var(--grey);
            line-height: 1.6;
        }
        .demo-note strong { color: #334155; }

        @media (max-width: 900px) {
            body { display: block; }
            .brand-panel { display: none; }
            .form-panel { min-height: 100dvh; padding: 40px 28px; }
            .mobile-mark { display: inline-flex; }
        }
    </style>
</head>

<body>

<div class="brand-panel">
    <div class="brand-glow"></div>

    <div class="brand-mark">
        <div class="brand-mark-icon">E</div>
        <div class="brand-mark-text">Edu<span>ERP</span></div>
    </div>

    <h1>Everything your school runs on, in one place.</h1>
    <p>Sign in to manage admissions, attendance, fees, exams and communication from a single, unified dashboard.</p>

    <div class="brand-points">
        <div class="brand-point"><span class="brand-point-mark"></span> Trusted by 500+ schools worldwide</div>
        <div class="brand-point"><span class="brand-point-mark"></span> Bank-level security &amp; 99% uptime</div>
        <div class="brand-point"><span class="brand-point-mark"></span> Dedicated onboarding support</div>
    </div>
</div>

<div class="form-panel">
    <div class="form-inner">
        <a href="/" class="mobile-mark">
            <div class="brand-mark-icon">E</div>
            <span>Edu<strong>ERP</strong></span>
        </a>

        <span class="form-tag">Welcome Back</span>
        <div class="form-title">Sign in to your account</div>
        <div class="form-subtitle">Enter your details to access your dashboard.</div>

        <div class="error-box{{ $errors->any() || session('error') ? ' is-visible' : '' }}" id="login-error" role="alert">
            <span>
                @if($errors->any())
                    {{ $errors->first() }}
                @elseif(session('error'))
                    {{ session('error') }}
                @endif
            </span>
        </div>

        <form method="POST" action="{{ route('login.post') }}" id="login-form" data-ajax-login>
            @csrf

            <div class="field">
                <label>Email Address or Mobile Number</label>
                <input
                    type="text"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="you@school.com"
                    required>
            </div>

            <div class="field">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required>
            </div>

            <div class="options">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                <a href="#">Forgot password?</a>
            </div>

            <button class="login-btn" type="submit" id="login-submit">
                <span data-login-label>Sign In</span>
            </button>
        </form>

        <div class="demo-note">
            <strong>Super Admin demo access</strong><br>
            admin@eduerp.com · password
        </div>
    </div>
</div>

<script>
    (function () {
        const form = document.querySelector('[data-ajax-login]');
        const errorBox = document.getElementById('login-error');
        const errorText = errorBox ? errorBox.querySelector('span') : null;
        const submitButton = document.getElementById('login-submit');
        const submitLabel = submitButton ? submitButton.querySelector('[data-login-label]') : null;

        if (!form || !errorBox || !submitButton || !window.fetch) {
            return;
        }

        function showError(message) {
            if (errorText) errorText.textContent = message || 'Unable to login. Please try again.';
            errorBox.classList.add('is-visible');
        }

        function clearError() {
            if (errorText) errorText.textContent = '';
            errorBox.classList.remove('is-visible');
        }

        function setLoading(isLoading) {
            submitButton.disabled = isLoading;
            if (submitLabel) {
                submitLabel.textContent = isLoading ? 'Signing In...' : 'Sign In';
            }
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            clearError();
            setLoading(true);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json().catch(function () {
                    return {};
                });

                if (!response.ok) {
                    const firstError = data.errors
                        ? Object.values(data.errors).flat()[0]
                        : data.message;

                    showError(firstError);
                    setLoading(false);
                    return;
                }

                window.location.assign(data.redirect || '/dashboard');
            } catch (error) {
                showError('Network error. Please check your connection and try again.');
                setLoading(false);
            }
        });
    })();
</script>

</body>
</html>

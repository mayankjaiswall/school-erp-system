<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Login — EduERP</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body{font-family:Inter,Arial,Helvetica,sans-serif;background:#f3f6fb;display:flex;align-items:center;justify-content:center;height:100vh;margin:0}
    .card{background:#fff;width:420px;border-radius:12px;padding:28px;box-shadow:0 10px 30px rgba(16,24,40,.08)}
    h1{font-size:1.25rem;margin-bottom:8px}
    p.desc{color:#64748b;font-size:.95rem;margin-bottom:20px}
    form{display:flex;flex-direction:column;gap:12px}
    label{font-size:.78rem;color:#334155}
    input[type=text],input[type=password]{padding:12px 14px;border:1px solid #e6edf3;border-radius:8px;font-size:.95rem}
    .controls{display:flex;align-items:center;justify-content:space-between;margin-top:6px}
    .btn{background:#4f46e5;color:#fff;padding:10px 14px;border-radius:8px;border:none;font-weight:600;cursor:pointer}
    .link{color:#4f46e5;text-decoration:none;font-weight:600}
    .errors{background:#fee2e2;color:#991b1b;padding:8px;border-radius:8px;margin-bottom:8px}
  </style>
</head>
<body>
  <div class="card">
    <h1>Super Admin Login</h1>
    <p class="desc">Sign in to access the super admin dashboard for managing the SaaS platform.</p>

    @if($errors->any())
      <div class="errors">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="/login">
      @csrf
      <div>
        <label for="email">Email</label>
        <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus />
      </div>
      <div>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required />
      </div>
      <div class="controls">
        <label><input type="checkbox" name="remember"> Remember me</label>
        <a href="#" class="link">Forgot?</a>
      </div>
      <button class="btn" type="submit">Sign In</button>
    </form>
  </div>
</body>
</html>
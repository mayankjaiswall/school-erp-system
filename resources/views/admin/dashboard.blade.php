<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Super Admin Dashboard — EduERP</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: Inter, Arial, Helvetica, sans-serif;
      background: #f8fafc;
      margin: 0
    }

    .topbar {
      background: #fff;
      padding: 18px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 6px 20px rgba(2, 6, 23, .06)
    }

    .brand {
      font-weight: 700;
      color: #111827
    }

    .container {
      max-width: 1200px;
      margin: 28px auto;
      padding: 0 24px
    }

    .card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 6px 18px rgba(2, 6, 23, .04)
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-top: 18px
    }

    .stat {
      padding: 18px;
      border-radius: 10px;
      background: linear-gradient(90deg, #eef2ff, #f8fafc)
    }

    a.logout {
      color: #ef4444;
      text-decoration: none;
      font-weight: 600
    }
  </style>
</head>

<body>
  <div class="topbar">
    <div class="brand">EduERP — Super Admin</div>
    <div>
      <form method="POST" action="/logout" style="display:inline">@csrf<button style="background:none;border:none;color:#4f46e5;font-weight:700;cursor:pointer">Logout</button></form>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <h2>Welcome, Super Admin</h2>
      <p style="color:#475569">Overview of your SaaS instance</p>

      <div class="grid">
        <div class="stat">
          <h3>Schools</h3>
          <p style="font-weight:800;font-size:1.6rem">500+</p>
        </div>
        <div class="stat">
          <h3>Active Students</h3>
          <p style="font-weight:800;font-size:1.6rem">1.2M+</p>
        </div>
        <div class="stat">
          <h3>Pending Requests</h3>
          <p style="font-weight:800;font-size:1.6rem">12</p>
        </div>
      </div>

    </div>
  </div>
</body>

</html>
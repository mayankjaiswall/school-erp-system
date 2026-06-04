<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduERP Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Inter',sans-serif;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:linear-gradient(
                135deg,
                #0f172a 0%,
                #312e81 50%,
                #4f46e5 100%
            );
            padding:20px;
        }

        .login-wrapper{
            width:100%;
            max-width:1100px;
            background:#fff;
            border-radius:24px;
            overflow:hidden;
            display:grid;
            grid-template-columns:1fr 1fr;
            box-shadow:0 25px 50px rgba(0,0,0,.25);
        }

        .left-side{
            padding:60px;
            background:linear-gradient(
                135deg,
                #1e1b4b,
                #4338ca
            );
            color:#fff;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .logo{
            font-size:32px;
            font-weight:700;
            margin-bottom:20px;
        }

        .logo span{
            color:#fbbf24;
        }

        .left-side h2{
            font-size:42px;
            line-height:1.2;
            margin-bottom:20px;
        }

        .left-side p{
            color:#dbeafe;
            line-height:1.8;
        }

        .features{
            margin-top:30px;
        }

        .features div{
            margin-bottom:12px;
        }

        .right-side{
            padding:60px;
        }

        .login-title{
            font-size:32px;
            font-weight:700;
            margin-bottom:10px;
            color:#111827;
        }

        .login-subtitle{
            color:#64748b;
            margin-bottom:30px;
        }

        .form-group{
            margin-bottom:18px;
        }

        label{
            display:block;
            margin-bottom:8px;
            font-size:14px;
            color:#334155;
        }

        .input-box{
            position:relative;
        }

        .input-box i{
            position:absolute;
            left:15px;
            top:15px;
            color:#94a3b8;
        }

        .input-box input{
            width:100%;
            padding:14px 14px 14px 45px;
            border:1px solid #e2e8f0;
            border-radius:12px;
            font-size:15px;
            outline:none;
        }

        .input-box input:focus{
            border-color:#4f46e5;
        }

        .options{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        }

        .options a{
            text-decoration:none;
            color:#4f46e5;
            font-weight:600;
        }

        .login-btn{
            width:100%;
            padding:14px;
            border:none;
            border-radius:12px;
            background:linear-gradient(
                135deg,
                #4f46e5,
                #4338ca
            );
            color:#fff;
            font-weight:600;
            cursor:pointer;
            font-size:15px;
        }

        .login-btn:hover{
            opacity:.95;
        }

        .error-box{
            background:#fee2e2;
            color:#991b1b;
            padding:12px;
            border-radius:10px;
            margin-bottom:20px;
        }

        .demo-box{
            margin-top:25px;
            background:#f8fafc;
            border:1px dashed #cbd5e1;
            padding:15px;
            border-radius:12px;
            font-size:14px;
        }

        @media(max-width:900px){

            .login-wrapper{
                grid-template-columns:1fr;
            }

            .left-side{
                display:none;
            }

            .right-side{
                padding:35px;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <div class="left-side">

        <div class="logo">
            Edu<span>ERP</span>
        </div>

        <h2>
            Smart School
            Management Platform
        </h2>

        <p>
            Manage schools, students, teachers,
            attendance, fees, examinations and
            administration from a single SaaS platform.
        </p>

        <div class="features">
            <div>✅ School Management</div>
            <div>✅ Student Tracking</div>
            <div>✅ Attendance System</div>
            <div>✅ Fee Management</div>
            <div>✅ Reports & Analytics</div>
        </div>

    </div>

    <div class="right-side">

        <div class="login-title">
            Welcome Back 👋
        </div>

        <div class="login-subtitle">
            Login to your EduERP account
        </div>

        @if($errors->any())
            <div class="error-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group">
                <label>Email Address</label>

                <div class="input-box">
                    <i class="bi bi-envelope"></i>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                        required>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>

                <div class="input-box">
                    <i class="bi bi-lock"></i>
                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        required>
                </div>
            </div>

            <div class="options">
                <label>
                    <input type="checkbox" name="remember">
                    Remember Me
                </label>

                <a href="#">
                    Forgot Password?
                </a>
            </div>

            <button class="login-btn" type="submit">
                Sign In
            </button>
        </form>

        <div class="demo-box">
            <strong>Super Admin Demo</strong><br>
            Email: admin@eduerp.com<br>
            Password: password
        </div>

    </div>

</div>

</body>
</html>
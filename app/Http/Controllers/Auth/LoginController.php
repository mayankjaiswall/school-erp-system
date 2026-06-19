<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginField = filter_var($validated['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = [
            $loginField => $validated['email'],
            'password' => $validated['password'],
            'status' => 1,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role && $user->role->slug === 'super_admin') {
                return redirect('/admin/dashboard');
            }
            if ($user->role && $user->role->slug === 'principal') {
                return redirect('/principal/dashboard');
            }
            if ($user->role && $user->role->slug === 'teacher') {
                return redirect('/teacher/dashboard');
            }
            if ($user->role && $user->role->slug === 'parent') {
                return redirect('/parent/dashboard');
            }
            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

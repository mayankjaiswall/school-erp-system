<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (! Auth::check()) {
            return redirect('/login');
        }

        // Optionally you could add a gate or role-check here for super-admin.
        return view('admin.dashboard');
    }
}

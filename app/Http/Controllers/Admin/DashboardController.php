<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\School;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (! Auth::check()) {
            return redirect('/login');
        }
        $totalSchools = School::count();
        $activeSchools = School::where('status', 1)->count();
        $recentSchools = School::latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();
        // Optionally you could add a gate or role-check here for super-admin.
        return view('admin.dashboard', compact('totalSchools', 'activeSchools', 'recentSchools', 'recentUsers'));
    }
}

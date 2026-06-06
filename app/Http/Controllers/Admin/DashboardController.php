<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\School;
use App\Models\User;

class DashboardController extends Controller
{
public function index(Request $request)
{
    if (!Auth::check()) {
        return redirect('/login');
    }

    $totalSchools = School::count();
    $activeSchools = School::where('status', 1)->count();
    $inactiveSchools = School::where('status', 0)->count();

    $totalUsers = User::count();
    $activeUsers = User::where('status', 1)->count();
    $inactiveUsers = User::where('status', 0)->count();

    $totalRoles = Role::count();

    $recentSchools = School::latest()->take(5)->get();
    $recentUsers = User::latest()->take(5)->get();

    return view('admin.dashboard', compact('totalSchools','activeSchools','inactiveSchools','totalUsers','activeUsers','inactiveUsers','totalRoles','recentSchools','recentUsers'
    ));
}
}

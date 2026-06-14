<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\School;

class UserController extends Controller
{
    public function index()
    {
        $search = trim((string) request('search'));

        $users = User::with(['role', 'school'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('role', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('slug', 'like', "%{$search}%");
                        })
                        ->orWhereHas('school', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->get();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function create()
    {
        $roles = Role::all();
        $schools = School::all(); 
        return view('admin.users.create', compact('roles', 'schools')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|digits:10',
            'role_id' => 'required|exists:roles,id',
            'school_id' => 'required|exists:schools,id',
            'status' => 'required|boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'school_id' => $request->school_id,
            'status' => $request->status,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User created successfully.',
                'redirect' => route('users.index'),
                'user' => $user,
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $schools = School::all(); 
        return view('admin.users.edit', compact('user', 'roles', 'schools')); 
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|digits:10',
            'role_id' => 'required|exists:roles,id',
            'school_id' => 'required|exists:schools,id',
            'status' => 'required|boolean',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->role_id = $request->role_id;
        $user->school_id = $request->school_id;
        $user->status = $request->status;
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User updated successfully.',
                'redirect' => route('users.index'),
                'user' => $user,
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}

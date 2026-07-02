<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function profile()
    {
        return view('account.profile', [
            'user' => auth()->user()->load(['role', 'school']),
            'layout' => $this->layoutForUser(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'digits:10'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;

        if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('profile-photos', 'public');
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function password()
    {
        return view('account.password', [
            'user' => auth()->user()->load(['role', 'school']),
            'layout' => $this->layoutForUser(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return back()->with('success', 'Password reset successfully.');
    }

    private function layoutForUser(): string
    {
        return match (auth()->user()?->role?->slug) {
            'principal' => 'layouts.principal',
            'teacher' => 'layouts.teacher',
            'parent' => 'layouts.parent',
            default => 'layouts.admin',
        };
    }
}

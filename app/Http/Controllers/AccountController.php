<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function profile()
    {
        $user = auth()->user()->load(['role', 'school']);
        $hasProfilePhoto = $user->photo && Storage::disk('public')->exists($user->photo);

        return view('account.profile', [
            'user' => $user,
            'layout' => $this->layoutForUser(),
            'hasProfilePhoto' => $hasProfilePhoto,
            'profilePhotoName' => $hasProfilePhoto ? $this->profilePhotoDisplayName($user) : null,
        ]);
    }

    public function profilePhoto(Request $request)
    {
        $user = $request->user();

        if (! $user->photo || ! Storage::disk('public')->exists($user->photo)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($user->photo), [
            'Cache-Control' => 'private, max-age=300',
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
            $photo = $request->file('photo');
            $originalName = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $photo->getClientOriginalExtension();
            $safeName = Str::slug($originalName) ?: Str::slug($user->name ?: 'profile-photo');
            $fileName = "{$safeName}.{$extension}";
            $counter = 1;

            while (Storage::disk('public')->exists("profile-photos/{$fileName}")) {
                $fileName = "{$safeName}-{$counter}.{$extension}";
                $counter++;
            }

            $user->photo = $photo->storeAs('profile-photos', $fileName, 'public');
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

    private function profilePhotoDisplayName($user): string
    {
        $extension = pathinfo($user->photo, PATHINFO_EXTENSION) ?: 'jpg';
        $name = Str::headline($user->name ?: 'Profile');

        return "{$name} Profile Photo.{$extension}";
    }
}

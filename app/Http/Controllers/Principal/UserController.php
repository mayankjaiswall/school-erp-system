<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private array $allowedRoleSlugs = [
        'admin',
        'hod',
        'teacher',
        'parent',
        'student',
    ];

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $users = User::with('role')
            ->where('school_id', $this->schoolId())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('role', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('slug', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->get();

        return view('principal.users.index', compact('users', 'search'));
    }

    public function create()
    {
        $roles = $this->allowedRoles()->get();

        return view('principal.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules($request));

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'school_id' => $this->schoolId(),
                'role_id' => $validated['role_id'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'],
            ]);

            $this->syncTeacherProfile($user);

            return $user;
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User created successfully.',
                'redirect' => route('principal.users.index'),
                'user' => $user,
            ]);
        }

        return redirect()
            ->route('principal.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        $user = User::with(['role', 'school'])
            ->where('school_id', $this->schoolId())
            ->findOrFail($id);

        return view('principal.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = $this->manageableUser($id);
        $roles = $this->allowedRoles()->get();

        return view('principal.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = $this->manageableUser($id);
        $validated = $request->validate($this->rules($request, $user->id));

        $user->fill([
            'role_id' => $validated['role_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ]);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        DB::transaction(function () use ($user) {
            $user->save();
            $this->syncTeacherProfile($user);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User updated successfully.',
                'redirect' => route('principal.users.index'),
                'user' => $user,
            ]);
        }

        return redirect()
            ->route('principal.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $user = $this->manageableUser($id);

        DB::transaction(function () use ($user) {
            $user->teacher?->delete();
            $user->delete();
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User deleted successfully.',
            ]);
        }

        return redirect()
            ->route('principal.users.index')
            ->with('success', 'User deleted successfully.');
    }

    private function rules(Request $request, ?int $userId = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone' => 'nullable|digits:10',
            'password' => [$userId ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where(function ($query) {
                    $query->whereIn('slug', $this->allowedRoleSlugs);
                }),
            ],
            'status' => 'required|boolean',
        ];
    }

    private function allowedRoles()
    {
        return Role::whereIn('slug', $this->allowedRoleSlugs)
            ->orderBy('name');
    }

    private function syncTeacherProfile(User $user): void
    {
        $user->loadMissing(['role', 'teacher']);

        if ($user->role?->slug !== 'teacher') {
            $user->teacher?->delete();
            return;
        }

        $teacher = $user->teacher ?: new Teacher([
            'user_id' => $user->id,
            'school_id' => $user->school_id,
            'employee_code' => $this->nextEmployeeCode($user->school_id),
        ]);

        $teacher->fill([
            'user_id' => $user->id,
            'school_id' => $user->school_id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'status' => $user->status,
        ]);

        if (! $teacher->employee_code) {
            $teacher->employee_code = $this->nextEmployeeCode($user->school_id);
        }

        $teacher->save();
    }

    private function nextEmployeeCode(int $schoolId): string
    {
        $nextId = ((int) Teacher::where('school_id', $schoolId)->max('id')) + 1;

        return 'T-' . str_pad((string) $nextId, 3, '0', STR_PAD_LEFT);
    }

    private function manageableUser($id): User
    {
        $user = User::with('role')
            ->where('school_id', $this->schoolId())
            ->findOrFail($id);

        abort_unless(
            $user->role && in_array($user->role->slug, $this->allowedRoleSlugs, true),
            403,
            'Principal cannot manage this user role.'
        );

        return $user;
    }

    private function schoolId(): int
    {
        $schoolId = auth()->user()->school_id;

        abort_unless($schoolId, 403, 'Principal is not attached to a school.');

        return (int) $schoolId;
    }
}

<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\School;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    //Teacher index
    public function index(Request $request)
    {  
        $search = trim((string) $request->query('search'));

        $teachers = Teacher::with('user')
            ->where('school_id', auth()->user()->school_id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('principal.teachers.index', compact('teachers', 'search'));
    }

    //Teacher create
    public function create()
    {
        $school = School::where('id', auth()->user()->school_id)->first();
        return view('principal.teachers.create', compact('school'));
    }

    //Teacher store
    public function store(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:teachers,email',
                'unique:users,email',
            ],
            'phone' => 'nullable|digits:10',
            'employee_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teachers', 'employee_code')->where('school_id', $schoolId),
            ],
            'qualification' => 'nullable|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'joining_date' => 'nullable|date',
            'gender' => 'nullable|string',
            'status' => 'required|in:1,0',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($validated, $schoolId) {
            $user = User::create([
                'school_id' => $schoolId,
                'role_id' => $this->teacherRoleId(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'],
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'employee_code' => $validated['employee_code'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'qualification' => $validated['qualification'] ?? null,
                'experience' => $validated['experience'] ?? null,
                'joining_date' => $validated['joining_date'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'status' => $validated['status'],
            ]);
        });

        return response()->json(['success' => true,'message' => 'Teacher created successfully.']); 
    }


    //Teacher edit
    public function edit($id)
    {
        $teacher = Teacher::with('user')
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($id);

        return view('principal.teachers.edit', compact('teacher'));
    }

    //Teacher update
    public function update(Request $request, $id)
    {
        $schoolId = auth()->user()->school_id;
        $teacher = Teacher::with('user')
            ->where('school_id', $schoolId)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('teachers', 'email')->ignore($teacher->id),
                Rule::unique('users', 'email')->ignore($teacher->user_id),
            ],
            'phone' => 'nullable|digits:10',
            'employee_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teachers', 'employee_code')
                    ->where('school_id', $schoolId)
                    ->ignore($teacher->id),
            ],
            'qualification' => 'nullable|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'joining_date' => 'nullable|date',
            'gender' => 'nullable|string',
            'status' => 'required|in:1,0',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($teacher, $validated, $schoolId) {
            $user = $teacher->user;

            if (! $user) {
                $user = User::create([
                    'school_id' => $schoolId,
                    'role_id' => $this->teacherRoleId(),
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'password' => Hash::make($validated['password'] ?? Str::random(16)),
                    'status' => $validated['status'],
                ]);

                $teacher->user_id = $user->id;
            }

            $user->fill([
                'school_id' => $schoolId,
                'role_id' => $this->teacherRoleId(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'status' => $validated['status'],
            ]);

            if (! empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            $teacher->fill([
                'employee_code' => $validated['employee_code'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'qualification' => $validated['qualification'] ?? null,
                'experience' => $validated['experience'] ?? null,
                'joining_date' => $validated['joining_date'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'status' => $validated['status'],
            ]);

            $teacher->save();
        });

        return response()->json(['success' => true,'message' => 'Teacher updated successfully.']);
    }

    //Teacher show
    public function show($id)
    {
        $teacher = Teacher::with(['school', 'user'])
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($id);

        return view('principal.teachers.show', compact('teacher'));
    }

    //Teacher destroy
    public function destroy($id)
    {
        $teacher = Teacher::with('user')
            ->where('school_id', auth()->user()->school_id)
            ->findOrFail($id);

        DB::transaction(function () use ($teacher) {
            $user = $teacher->user;
            $teacher->delete();

            if ($user && $user->role?->slug === 'teacher') {
                $user->delete();
            }
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }

    private function teacherRoleId(): int
    {
        return Role::where('slug', 'teacher')->firstOrFail()->id;
    }
}

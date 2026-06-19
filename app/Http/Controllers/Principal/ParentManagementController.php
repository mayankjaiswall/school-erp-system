<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ParentManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $parents = ParentModel::with(['user', 'students.class'])
            ->whereHas('user', fn ($query) => $query->where('school_id', auth()->user()->school_id))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('father_name', 'like', "%{$search}%")
                        ->orWhere('mother_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('students', fn ($studentQuery) => $studentQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('admission_no', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->get();

        return view('principal.parents.index', compact('parents', 'search'));
    }

    public function create()
    {
        $parent = new ParentModel();
        $linkedStudents = collect();
        $students = $this->schoolStudents()->get();

        return view('principal.parents.create', compact('parent', 'students', 'linkedStudents'));
    }

    public function store(Request $request)
    {
        $schoolId = (int) auth()->user()->school_id;
        $validated = $request->validate($this->rules($schoolId));

        DB::transaction(function () use ($validated, $schoolId) {
            $role = Role::firstOrCreate(
                ['slug' => 'parent'],
                ['name' => 'Parent']
            );

            $user = User::create([
                'school_id' => $schoolId,
                'role_id' => $role->id,
                'name' => $this->displayName($validated),
                'email' => $validated['email'] ?: $this->generatedEmail($validated['phone']),
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'],
            ]);

            $parent = ParentModel::create([
                'user_id' => $user->id,
                'father_name' => $validated['father_name'] ?? null,
                'mother_name' => $validated['mother_name'] ?? null,
                'phone' => $validated['phone'],
                'alternate_phone' => $validated['alternate_phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'occupation' => $validated['occupation'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'],
            ]);

            $parent->students()->sync($this->studentSyncPayload($validated));
        });

        return response()->json([
            'success' => true,
            'message' => 'Parent created and linked successfully.',
            'redirect' => route('principal.parents.index'),
        ]);
    }

    public function edit($id)
    {
        $parent = $this->schoolParent($id);
        $students = $this->schoolStudents()->get();
        $linkedStudents = $parent->students->keyBy('id');

        return view('principal.parents.edit', compact('parent', 'students', 'linkedStudents'));
    }

    public function update(Request $request, $id)
    {
        $parent = $this->schoolParent($id);
        $schoolId = (int) auth()->user()->school_id;
        $validated = $request->validate($this->rules($schoolId, $parent));

        DB::transaction(function () use ($parent, $validated) {
            $userData = [
                'name' => $this->displayName($validated),
                'email' => $validated['email'] ?: $this->generatedEmail($validated['phone']),
                'phone' => $validated['phone'],
                'status' => $validated['status'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $parent->user->update($userData);
            $parent->update([
                'father_name' => $validated['father_name'] ?? null,
                'mother_name' => $validated['mother_name'] ?? null,
                'phone' => $validated['phone'],
                'alternate_phone' => $validated['alternate_phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'occupation' => $validated['occupation'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'],
            ]);

            $parent->students()->sync($this->studentSyncPayload($validated));
        });

        return response()->json([
            'success' => true,
            'message' => 'Parent updated successfully.',
            'redirect' => route('principal.parents.index'),
        ]);
    }

    public function show($id)
    {
        $parent = $this->schoolParent($id);

        return view('principal.parents.show', compact('parent'));
    }

    public function destroy(Request $request, $id)
    {
        $parent = $this->schoolParent($id);
        $parent->user()->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Parent deleted successfully.',
            ]);
        }

        return redirect()->route('principal.parents.index')->with('success', 'Parent deleted successfully.');
    }

    private function rules(int $schoolId, ?ParentModel $parent = null): array
    {
        $userId = $parent?->user_id;

        return [
            'father_name' => ['nullable', 'string', 'max:255', 'required_without:mother_name'],
            'mother_name' => ['nullable', 'string', 'max:255', 'required_without:father_name'],
            'phone' => [
                'required',
                'digits:10',
                Rule::unique('users', 'phone')->ignore($userId),
            ],
            'alternate_phone' => ['nullable', 'digits:10'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'occupation' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['1', '0', 1, 0])],
            'password' => [$parent ? 'nullable' : 'required', 'string', 'min:6', 'confirmed'],
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => [
                'integer',
                Rule::exists('students', 'id')->where('school_id', $schoolId)->where('status', 1),
            ],
            'relationships' => ['required', 'array'],
            'relationships.*' => ['required', Rule::in(['Father', 'Mother', 'Guardian'])],
        ];
    }

    private function schoolParent($id): ParentModel
    {
        return ParentModel::with(['user', 'students.class'])
            ->whereHas('user', fn ($query) => $query->where('school_id', auth()->user()->school_id))
            ->findOrFail($id);
    }

    private function schoolStudents()
    {
        return Student::with('class')
            ->where('school_id', auth()->user()->school_id)
            ->where('status', 1)
            ->orderBy('name');
    }

    private function studentSyncPayload(array $validated): array
    {
        return collect($validated['student_ids'])
            ->mapWithKeys(fn ($studentId) => [
                (int) $studentId => [
                    'relationship' => $validated['relationships'][$studentId] ?? 'Guardian',
                ],
            ])
            ->all();
    }

    private function displayName(array $data): string
    {
        return $data['father_name'] ?: ($data['mother_name'] ?: 'Parent');
    }

    private function generatedEmail(string $phone): string
    {
        return 'parent_' . $phone . '@parents.local';
    }
}

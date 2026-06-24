<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super_admin',
            ],
            [
                'name' => 'Principal',
                'slug' => 'principal',
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
            ],
            [
                'name' => 'HOD',
                'slug' => 'hod',
            ],
            [
                'name' => 'Teacher',
                'slug' => 'teacher',
            ],
            [
                'name' => 'Parent',
                'slug' => 'parent',
            ],
            [
                'name' => 'Student',
                'slug' => 'student',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                ['name' => $role['name']]
            );
        }
    }
}

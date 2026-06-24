<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(
            ['slug' => 'super_admin'],
            ['name' => 'Super Admin']
        );

        User::updateOrCreate(
            ['email' => 'admin@eduerp.com'],
            [
                'school_id' => null,
                'role_id'   => $role->id,
                'name'      => 'Super Admin',
                'phone'     => null,
                'password'  => 'password',
                'status'    => true,
            ]
        );
    }
}

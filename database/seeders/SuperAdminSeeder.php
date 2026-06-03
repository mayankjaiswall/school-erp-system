<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'school_id' => null,
            'role_id'   => 1,
            'name'      => 'Super Admin',
            'email'     => 'admin@eduerp.com',
            'phone'     => null,
            'password'  => 'password',
            'status'    => true,
        ]);
    }
}
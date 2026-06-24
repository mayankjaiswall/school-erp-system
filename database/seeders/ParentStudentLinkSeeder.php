<?php

namespace Database\Seeders;

use App\Models\ParentModel;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParentStudentLinkSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::updateOrCreate(
            ['code' => 'DEMO'],
            [
                'name' => 'Demo School',
                'email' => 'demo@eduerp.com',
                'phone' => '9999999999',
                'address' => 'Demo Campus',
                'status' => true,
            ]
        );

        $parentRole = Role::firstOrCreate(
            ['slug' => 'parent'],
            ['name' => 'Parent']
        );

        $classEight = SchoolClass::updateOrCreate(
            ['school_id' => $school->id, 'class_code' => '8-A'],
            [
                'name' => 'Class 8',
                'section' => 'A',
                'capacity' => 40,
                'description' => 'Demo class 8-A',
                'status' => true,
            ]
        );

        $classFive = SchoolClass::updateOrCreate(
            ['school_id' => $school->id, 'class_code' => '5-B'],
            [
                'name' => 'Class 5',
                'section' => 'B',
                'capacity' => 40,
                'description' => 'Demo class 5-B',
                'status' => true,
            ]
        );

        $parentUser = User::updateOrCreate(
            ['email' => 'rajesh.sharma.parent@eduerp.local'],
            [
                'school_id' => $school->id,
                'role_id' => $parentRole->id,
                'name' => 'Rajesh Sharma',
                'phone' => '9876543210',
                'password' => 'password',
                'status' => true,
            ]
        );

        $parent = ParentModel::updateOrCreate(
            ['user_id' => $parentUser->id],
            [
                'father_name' => 'Rajesh Sharma',
                'mother_name' => null,
                'phone' => '9876543210',
                'email' => 'rajesh.sharma.parent@eduerp.local',
                'occupation' => 'Business',
                'address' => 'Jaipur',
                'status' => true,
            ]
        );

        $rahul = Student::updateOrCreate(
            ['school_id' => $school->id, 'admission_no' => 'ADM-RAHUL-8A'],
            [
                'class_id' => $classEight->id,
                'roll_no' => '8A-01',
                'name' => 'Rahul Sharma',
                'email' => null,
                'phone' => null,
                'gender' => 'Male',
                'dob' => '2012-04-15',
                'address' => 'Jaipur',
                'photo' => null,
                'status' => true,
            ]
        );

        $riya = Student::updateOrCreate(
            ['school_id' => $school->id, 'admission_no' => 'ADM-RIYA-5B'],
            [
                'class_id' => $classFive->id,
                'roll_no' => '5B-01',
                'name' => 'Riya Sharma',
                'email' => null,
                'phone' => null,
                'gender' => 'Female',
                'dob' => '2015-08-20',
                'address' => 'Jaipur',
                'photo' => null,
                'status' => true,
            ]
        );

        $parent->students()->syncWithoutDetaching([
            $rahul->id => ['relationship' => 'Father'],
            $riya->id => ['relationship' => 'Father'],
        ]);
    }
}

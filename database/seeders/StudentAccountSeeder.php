<?php

namespace Database\Seeders;

use App\Models\UserAccount;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// FEATURE: Student account seeder for populating demo student data with authentication
class StudentAccountSeeder extends Seeder
{
    /**
     * FEATURE: Database seeding with predefined student accounts and credentials
     */
    public function run(): void
    {
        $students = [
            [
                'nrp' => '5026231001',
                'name' => 'Akhtar Widodo',
                'email' => 'akhtar.widodo@student.its.ac.id',
                'program' => 'Teknik Informatika',
            ],
            [
                'nrp' => '5026231002',
                'name' => 'Fattan Widodo',
                'email' => 'fattan.widodo@student.its.ac.id',
                'program' => 'Teknik Informatika',
            ],
            [
                'nrp' => '5026231003',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.its.ac.id',
                'program' => 'Teknik Mesin',
            ],
            [
                'nrp' => '5026231004',
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@student.its.ac.id',
                'program' => 'Teknik Elektro',
            ],
            [
                'nrp' => '5026231005',
                'name' => 'Rini Wijaya',
                'email' => 'rini.wijaya@student.its.ac.id',
                'program' => 'Teknik Informatika',
            ],
            [
                'nrp' => '5026231006',
                'name' => 'Ahmad Gunawan',
                'email' => 'ahmad.gunawan@student.its.ac.id',
                'program' => 'Teknik Sipil',
            ],
            [
                'nrp' => '5026231007',
                'name' => 'Linda Kusuma',
                'email' => 'linda.kusuma@student.its.ac.id',
                'program' => 'Teknik Industri',
            ],
            [
                'nrp' => '5026231008',
                'name' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@student.its.ac.id',
                'program' => 'Teknik Kimia',
            ],
            [
                'nrp' => '5026231009',
                'name' => 'Maya Septiani',
                'email' => 'maya.septiani@student.its.ac.id',
                'program' => 'Teknik Arsitektur',
            ],
            [
                'nrp' => '5026231010',
                'name' => 'Haryo Pratama',
                'email' => 'haryo.pratama@student.its.ac.id',
                'program' => 'Teknik Informatika',
            ],
        ];

        foreach ($students as $studentData) {
            // Create user account
            $userAccount = UserAccount::create([
                'email' => $studentData['email'],
                'password_hash' => Hash::make('password123'), // Default password for seeding
                'role' => 'Student',
                'is_active' => true,
            ]);

            // Create student profile
            Student::create([
                'user_id' => $userAccount->id,
                'nrp' => $studentData['nrp'],
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'program' => $studentData['program'],
            ]);
        }
    }
}

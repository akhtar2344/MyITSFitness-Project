<?php

namespace Database\Seeders;

use App\Models\UserAccount;
use App\Models\Lecturer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LecturerAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lecturers = [
            [
                'employee_id' => 'LEC001',
                'name' => 'Dr. Ir. Bambang Sutrisno',
                'email' => 'bambang@its.ac.id',
                'department' => 'Teknik Informatika',
            ],
            [
                'employee_id' => 'LEC002',
                'name' => 'Prof. Dr. Ahmad Wijaya',
                'email' => 'ahmad@its.ac.id',
                'department' => 'Teknik Mesin',
            ],
            [
                'employee_id' => 'LEC003',
                'name' => 'Dr. Siti Aisyah',
                'email' => 'siti@its.ac.id',
                'department' => 'Teknik Elektro',
            ],
            [
                'employee_id' => 'LEC004',
                'name' => 'Ir. Rejo Sudibyo',
                'email' => 'rejo@its.ac.id',
                'department' => 'Teknik Informatika',
            ],
            [
                'employee_id' => 'LEC005',
                'name' => 'Dr. Taufik Rahman',
                'email' => 'taufik@its.ac.id',
                'department' => 'Teknik Sipil',
            ],
            [
                'employee_id' => 'LEC006',
                'name' => 'Prof. Ir. Hendra Kusuma',
                'email' => 'hendra@its.ac.id',
                'department' => 'Teknik Industri',
            ],
            [
                'employee_id' => 'LEC007',
                'name' => 'Dr. Endang Suryani',
                'email' => 'endang@its.ac.id',
                'department' => 'Teknik Kimia',
            ],
            [
                'employee_id' => 'LEC008',
                'name' => 'Ir. Pranoto Setyadi',
                'email' => 'pranoto@its.ac.id',
                'department' => 'Teknik Arsitektur',
            ],
            [
                'employee_id' => 'LEC009',
                'name' => 'Dr. Rina Kusuma',
                'email' => 'rina@its.ac.id',
                'department' => 'Teknik Informatika',
            ],
            [
                'employee_id' => 'LEC010',
                'name' => 'Prof. Dr. Sutardjo Adji',
                'email' => 'sutardjo@its.ac.id',
                'department' => 'Teknik Elektro',
            ],
        ];

        foreach ($lecturers as $lecturerData) {
            // Create user account
            $userAccount = UserAccount::create([
                'email' => $lecturerData['email'],
                'password_hash' => Hash::make('password123'), // Default password for seeding
                'role' => 'Lecturer',
                'is_active' => true,
            ]);

            // Create lecturer profile
            Lecturer::create([
                'user_id' => $userAccount->id,
                'employee_id' => $lecturerData['employee_id'],
                'name' => $lecturerData['name'],
                'email' => $lecturerData['email'],
                'department' => $lecturerData['department'],
            ]);
        }
    }
}

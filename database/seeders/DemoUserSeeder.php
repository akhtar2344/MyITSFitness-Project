<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('auth.user_account')->insert([
            [
                'id'          => \Str::uuid()->toString(),
                'email'       => 'student1@example.com',
                'password_hash' => Hash::make('password123'),
                'role'        => 'Student',
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'          => \Str::uuid()->toString(),
                'email'       => 'lecturer1@example.com',
                'password_hash' => Hash::make('password123'),
                'role'        => 'Lecturer',
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}

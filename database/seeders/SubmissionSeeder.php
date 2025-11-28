<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Submission;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder ini tidak membuat submission/activity apapun.
        // Semua mahasiswa dimulai dari blank (tanpa submission).
        // Submission akan dibuat secara manual melalui halaman submit.
        $this->command->info('SubmissionSeeder: Skipping. All students start with zero submissions.');
    }
}

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
        // Buat minimal 2 submission per student yang ada di tabel `student`.
        $students = Student::all();

        if ($students->isEmpty()) {
            $this->command->info('No students found. Skipping submission seeding.');
            return;
        }

        // Sesuaikan dengan ENUM di database: 'Pending','Accepted','Rejected','NeedRevision'
        $statuses = ['Pending', 'Accepted', 'Rejected', 'NeedRevision'];

        foreach ($students as $student) {
            $existing = $student->submissions()->count();
            $needed = max(0, 2 - $existing);

            for ($i = 0; $i < $needed; $i++) {
                $activity = Activity::create([
                    'name' => "Running (Seeded) - {$student->nrp} #" . ($existing + $i + 1),
                    'date' => Carbon::now()->subDays(rand(0, 30))->toDateString(),
                    'location' => ['Campus Track', 'City Park', 'Gym'][array_rand(['Campus Track','City Park','Gym'])],
                    'duration_minutes' => [60, 90, 120][array_rand([60,90,120])],
                ]);

                $status = $statuses[array_rand($statuses)];

                $submission = Submission::create([
                    'student_id' => $student->id,
                    'activity_id' => $activity->id,
                    'status' => $status,
                    'notes' => "Seeded submission for {$student->name} ({$student->nrp})",
                    'duration_minutes' => $activity->duration_minutes,
                ]);

                $this->command->info("Seeded submission {$submission->id} for {$student->email} (status: {$status})");
            }
        }
    }
}

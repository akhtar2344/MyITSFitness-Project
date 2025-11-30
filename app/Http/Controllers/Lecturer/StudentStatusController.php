<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Submission;

class StudentStatusController extends Controller
{
    /**
     * Display student status and submission history.
     */
    public function show($nrp)
    {
        // Cari student berdasarkan NRP
        $student = Student::where('nrp', $nrp)->firstOrFail();

        // Ambil submission history student dengan status history
        $submissions = Submission::where('student_id', $student->id)
            ->with(['activity', 'statusHistories'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('lecturer.status-account', compact('student', 'submissions'));
    }
}

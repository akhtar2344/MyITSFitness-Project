<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StatusPageController extends Controller
{
    /**
     * Display submission status page with all submissions
     */
    public function viewStatusPage(Request $request)
    {
        // Get logged-in user
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login');
        }

        // Find student
        $student = Student::where('user_id', $userId)->first();
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found');
        }

        // Get all submissions with activity, ordered by newest first
        $allSubmissions = $student->submissions()
            ->with('activity')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.status-page', [
            'student' => $student,
            'submissions' => $allSubmissions,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentDashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index(Request $request)
    {
        // Determine logged-in user via session (set by AuthController)
        $userId = session('user_id');

        if (! $userId) {
            return redirect()->route('login')->with('info', 'Please login to view dashboard');
        }

        $student = Student::where('user_id', $userId)->first();

        if (! $student) {
            return redirect()->route('login')->with('error', 'Student profile not found for current user');
        }

        // FEATURE: Aggregate submission statistics for dashboard display
        $totalSubmissions = $student->submissions()->count();
        $pendingCount = $student->submissions()->where('status', 'Pending')->count();
        // FEATURE: Accepted count calculation 
        $acceptedCount = $student->submissions()->where('status', 'Accepted')->count();
        // Note: DB enum uses 'NeedRevision'
        $needRevisionCount = $student->submissions()->where('status', 'NeedRevision')->count();
        
        // FEATURE: Generate SKEM points
        // Example: $skemPoints = $acceptedCount; (currently hard-coded as "SKEM 4" in views)

        // Recent submissions (latest 5) dengan eager load
        $recentSubmissions = $student->submissions()
            ->with(['activity', 'fileAttachments'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('student.dashboard.dashboard', [
            'student' => $student,
            'totalSubmissions' => $totalSubmissions,
            'pendingCount' => $pendingCount,
            'acceptedCount' => $acceptedCount,
            'needRevisionCount' => $needRevisionCount,
            'recentSubmissions' => $recentSubmissions,
        ]);
    }
}

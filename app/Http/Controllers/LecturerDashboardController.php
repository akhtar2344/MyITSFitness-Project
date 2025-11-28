<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecturer;
use App\Models\Submission;

class LecturerDashboardController extends Controller
{
    /**
     * Display the lecturer dashboard with real data from database.
     */
    public function index(Request $request)
    {
        // Get logged-in user via session (set by AuthController)
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('info', 'Please login to view dashboard');
        }

        $lecturer = Lecturer::where('user_id', $userId)->first();

        if (!$lecturer) {
            return redirect()->route('login')->with('error', 'Lecturer profile not found for current user');
        }

        // Get ALL submissions (not just by this lecturer, since submissions are for all lecturers to verify)
        $totalSubmissions = Submission::count();
        $pendingCount = Submission::where('status', 'Pending')->count();
        $acceptedCount = Submission::where('status', 'Accepted')->count();
        $needRevisionCount = Submission::where('status', 'NeedRevision')->count();
        $rejectedCount = Submission::where('status', 'Rejected')->count();

        // Recent submissions (latest 8) dengan eager load relations
        $recentSubmissions = Submission::with(['student', 'activity', 'fileAttachments'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Debug: Log jumlah submissions yang diambil
        \Log::info('Recent submissions count: ' . $recentSubmissions->count());
        foreach ($recentSubmissions as $sub) {
            \Log::info('Submission: ' . $sub->id . ' | Student: ' . ($sub->student ? $sub->student->name : 'NULL') . ' | Activity: ' . ($sub->activity ? $sub->activity->name : 'NULL'));
        }

        return view('lecturer.dashboard.dashboard', [
            'lecturer' => $lecturer,
            'totalSubmissions' => $totalSubmissions,
            'pendingCount' => $pendingCount,
            'acceptedCount' => $acceptedCount,
            'needRevisionCount' => $needRevisionCount,
            'rejectedCount' => $rejectedCount,
            'recentSubmissions' => $recentSubmissions,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Lecturer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submission;

class SubmissionReviewController extends Controller
{
    /**
     * Display submissions for lecturer review.
     */
    public function openSubmissionPage(Request $request)
    {
        // Get all submissions with their relations, ordered by latest
        $submissions = Submission::with(['student', 'activity', 'fileAttachments'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Filter by status if requested
        $filterStatus = $request->query('status');
        if ($filterStatus && $filterStatus !== 'All') {
            // Map UI status to database status (handle "Need Revision" -> "NeedRevision")
            $statusMap = [
                'Pending' => 'Pending',
                'Accepted' => 'Accepted',
                'Rejected' => 'Rejected',
                'Need Revision' => 'NeedRevision',  // Map spaced version to no-space version
            ];
            
            $dbStatus = $statusMap[$filterStatus] ?? $filterStatus;
            $submissions = $submissions->filter(function ($sub) use ($dbStatus) {
                return $sub->status === $dbStatus;
            })->values();
        }

        return view('lecturer.reviews.sublec', [
            'submissions' => $submissions,
            'filterStatus' => $filterStatus ?? 'All',
        ]);
    }
}

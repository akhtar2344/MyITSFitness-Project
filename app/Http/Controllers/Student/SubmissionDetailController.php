<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionDetailController extends Controller
{
    /**
     * Show the detail page for a specific submission.
     * GET /student/submissions/{id}/view
     */
    public function show(Submission $submission)
    {
        // Get logged-in user
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login');
        }

        // Check if submission belongs to logged-in student
        $student = $submission->student;
        if (!$student || $student->user_id !== $userId) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Unauthorized access to this submission');
        }

        // Load relationships
        $submission->load(['activity', 'fileAttachments', 'revisionRequests', 'comments']);

        // Get proof and certificate images
        $proofFile = $submission->fileAttachments()
            ->whereIn('file_type', ['JPG', 'JPEG', 'PNG'])
            ->first();
        
        $certificateFile = $submission->fileAttachments()
            ->where('file_type', 'PDF')
            ->first();

        // Load user relationships for comments (for display purposes)
        $comments = $submission->comments->map(function($comment) {
            if ($comment->student_id) {
                $student_obj = $comment->student;
                $comment->commentUser = (object)['name' => $student_obj?->name ?? 'Unknown'];
            } elseif ($comment->lecturer_id) {
                $lecturer_obj = $comment->lecturer;
                $comment->commentUser = (object)['name' => $lecturer_obj?->name ?? 'Unknown'];
            }
            return $comment;
        });

        // Map status to view
        $statusMapping = [
            'Pending' => 'student.submissions.show-pending',
            'Accepted' => 'student.submissions.show-accepted',
            'NeedRevision' => 'student.submissions.show-need-revision',
            'Rejected' => 'student.submissions.show-rejected',
        ];

        $viewName = $statusMapping[$submission->status] ?? 'student.submissions.show-pending';

        return view($viewName, [
            'submission' => $submission,
            'activity' => $submission->activity,
            'student' => $student,
            'proofImage' => $proofFile ? $proofFile->url : asset('images/placeholder.png'),
            'certificateFile' => $certificateFile,
            'revisionRequests' => $submission->revisionRequests,
            'comments' => $comments,
        ]);
    }
}

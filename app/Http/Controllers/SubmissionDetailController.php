<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Comment;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class SubmissionDetailController extends Controller
{
    /**
     * Display the submission detail page (for lecturer)
     */
    public function show($submissionId)
    {
        $submission = Submission::with(['student', 'activity', 'fileAttachments', 'comments'])
            ->findOrFail($submissionId);
        
        // Get comments (if comments table exists and has lecturer_id)
        $comments = Comment::where('submission_id', $submissionId)->get();
        
        // Process file attachments to ensure proper URL format
        if ($submission->fileAttachments) {
            foreach ($submission->fileAttachments as $file) {
                $file->display_url = $this->getFileDisplayUrl($file->url);
            }
        }
        
        return view('lecturer.show', compact('submission', 'comments'));
    }

    /**
     * Convert file URL to proper display format
     * Handles multiple path formats: /storage/..., storage/..., submissions/...
     */
    private function getFileDisplayUrl($url)
    {
        if (!$url) {
            return null;
        }

        // Already has /storage/ prefix
        if (strpos($url, '/storage/') === 0) {
            return $url;
        }

        // Has storage/ prefix without leading slash
        if (strpos($url, 'storage/') === 0) {
            return '/' . $url;
        }

        // Just the path without storage prefix
        if (strpos($url, 'submissions/') === 0 || strpos($url, 'proofs/') === 0) {
            return '/storage/' . $url;
        }

        // Fallback: assume it's a public disk path and generate URL
        try {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($url);
        } catch (\Exception $e) {
            return $url;
        }
    }

    /**
     * Verify user is a lecturer
     */
    private function isLecturer()
    {
        $userId = session('user_id');
        if (!$userId) {
            return false;
        }
        
        // Check if user is a lecturer in the Lecturer table
        return Lecturer::where('user_id', $userId)->exists();
    }

    /**
     * Accept a submission
     */
    public function accept(Submission $submission)
    {
        // Authorization check - only lecturers can accept submissions
        if (!$this->isLecturer()) {
            return redirect()->route('lecturer.submissions.show', $submission->id)
                ->with('error', 'Unauthorized: Only lecturers can accept submissions');
        }
        
        $submission->update(['status' => 'Accepted']);
        
        return redirect()->route('lecturer.submissions.show', $submission->id)->with('success', 'Submission accepted');
    }

    /**
     * Reject a submission
     */
    public function reject(Submission $submission)
    {
        // Authorization check - only lecturers can reject submissions
        if (!$this->isLecturer()) {
            return redirect()->route('lecturer.submissions.show', $submission->id)
                ->with('error', 'Unauthorized: Only lecturers can reject submissions');
        }
        
        $submission->update(['status' => 'Rejected']);
        
        return redirect()->route('lecturer.submissions.show', $submission->id)->with('success', 'Submission rejected');
    }

    /**
     * Request revision for a submission
     */
    public function requestRevision(Submission $submission)
    {
        // Authorization check - only lecturers can request revisions
        if (!$this->isLecturer()) {
            return redirect()->route('lecturer.submissions.show', $submission->id)
                ->with('error', 'Unauthorized: Only lecturers can request revisions');
        }
        
        $submission->update(['status' => 'NeedRevision']);
        
        return redirect()->route('lecturer.submissions.show', $submission->id)->with('success', 'Revision requested');
    }
}


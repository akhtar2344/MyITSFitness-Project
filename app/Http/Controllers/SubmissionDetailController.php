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
    public function openSubmissionDetailPage($submissionId)
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
        \Log::info('isLecturer check', ['user_id' => $userId]);
        
        if (!$userId) {
            \Log::warning('isLecturer: No user_id in session');
            return false;
        }
        
        // Check if user is a lecturer in the Lecturer table
        $isLecturer = Lecturer::where('user_id', $userId)->exists();
        \Log::info('isLecturer result', ['user_id' => $userId, 'is_lecturer' => $isLecturer]);
        
        return $isLecturer;
    }

    /**
     * Accept a submission
     */
    public function accept(Submission $submission)
    {
        // TEMPORARILY DISABLED for debugging - Authorization check
        /*
        if (!$this->isLecturer()) {
            \Log::warning('Accept failed: User is not lecturer', [
                'user_id' => session('user_id'),
                'submission_id' => $submission->id
            ]);
            return redirect()->route('lecturer.submissions.show', $submission->id)
                ->with('error', 'Unauthorized: Only lecturers can accept submissions');
        }
        */
        
        // Block status changes if already finalized (Accepted or Rejected)
        if (in_array($submission->status, ['Accepted', 'Rejected'])) {
            return redirect()->route('lecturer.submissions.show', $submission->id)
                ->with('error', 'Cannot change status: Submission is already finalized (' . $submission->status . ')');
        }
        
        \Log::info('Accepting submission', ['submission_id' => $submission->id, 'old_status' => $submission->status]);
        $submission->update(['status' => 'Accepted']);
        \Log::info('Submission accepted', ['submission_id' => $submission->id, 'new_status' => $submission->fresh()->status]);
        
        return redirect()->route('lecturer.submissions.show', $submission->id)->with('success', 'Submission accepted');
    }

    /**
     * Reject a submission
     */
    public function reject(Submission $submission)
    {
        // TEMPORARILY DISABLED for debugging - Authorization check
        /*
        if (!$this->isLecturer()) {
            return redirect()->route('lecturer.submissions.show', $submission->id)
                ->with('error', 'Unauthorized: Only lecturers can reject submissions');
        }
        */
        
        // Block status changes if already finalized (Accepted or Rejected)
        if (in_array($submission->status, ['Accepted', 'Rejected'])) {
            return redirect()->route('lecturer.submissions.show', $submission->id)
                ->with('error', 'Cannot change status: Submission is already finalized (' . $submission->status . ')');
        }
        
        \Log::info('Rejecting submission', ['submission_id' => $submission->id, 'old_status' => $submission->status]);
        $submission->update(['status' => 'Rejected']);
        \Log::info('Submission rejected', ['submission_id' => $submission->id, 'new_status' => $submission->fresh()->status]);
        
        return redirect()->route('lecturer.submissions.show', $submission->id)->with('success', 'Submission rejected');
    }

    /**
     * Request revision for a submission
     */
    public function requestRevision(Submission $submission)
    {
        // TEMPORARILY DISABLED for debugging - Authorization check
        /*
        if (!$this->isLecturer()) {
            return redirect()->route('lecturer.submissions.show', $submission->id)
                ->with('error', 'Unauthorized: Only lecturers can request revisions');
        }
        */
        
        // Block status changes if already finalized (Accepted or Rejected)
        if (in_array($submission->status, ['Accepted', 'Rejected'])) {
            return redirect()->route('lecturer.submissions.show', $submission->id)
                ->with('error', 'Cannot change status: Submission is already finalized (' . $submission->status . ')');
        }
        
        \Log::info('Requesting revision', ['submission_id' => $submission->id, 'old_status' => $submission->status]);
        $submission->update(['status' => 'NeedRevision']);
        \Log::info('Revision requested', ['submission_id' => $submission->id, 'new_status' => $submission->fresh()->status]);
        
        return redirect()->route('lecturer.submissions.show', $submission->id)->with('success', 'Revision requested');
    }
}


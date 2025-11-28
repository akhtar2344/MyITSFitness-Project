<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Activity;
use App\Models\Student;
use App\Models\FileAttachment;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubmissionManagementController extends Controller
{
    /**
     * Show submission form (GET /student/submissions/edit?activity=Basketball)
     */
    public function edit(Request $request)
    {
        $activityName = $request->query('activity', '');
        
        // Validasi activity name
        if (!$activityName) {
            return redirect()->route('student.submit')
                ->with('error', 'Please select an activity first');
        }

        return view('student.submissions.edit');
    }

    /**
     * Store new submission (POST /student/submissions)
     */
    public function store(Request $request)
    {
        // Get current logged-in student
        $studentId = session('user_id');
        
        if (!$studentId) {
            return redirect()->route('login')
                ->with('error', 'Please login first');
        }

        // Get student record
        $student = Student::where('user_id', $studentId)->firstOrFail();

        // Validate request
        $validated = $request->validate([
            'activity_name' => 'required|string|max:100',
            'date' => 'required|date_format:d/m/Y',
            'duration' => 'required|integer|min:1',
            'place' => 'required|string|max:100',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:10240', // 10MB
            'certificate_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ], [
            'activity_name.required' => 'Activity name is required',
            'date.required' => 'Date of occurrence is required',
            'date.date_format' => 'Date must be in dd/mm/yyyy format',
            'duration.required' => 'Duration is required',
            'duration.min' => 'Duration must be at least 1 minute',
            'place.required' => 'Place of issue is required',
            'proof_image.required' => 'Activity proof image is required',
            'proof_image.image' => 'Proof must be an image',
            'proof_image.mimes' => 'Proof must be JPEG or PNG',
            'proof_image.max' => 'Proof must not exceed 10MB',
        ]);

        try {
            // Find or create activity
            $activity = Activity::where('name', $validated['activity_name'])->first();
            
            if (!$activity) {
                // Parse date from dd/mm/yyyy format
                $dateFormatted = \DateTime::createFromFormat('d/m/Y', $validated['date']);
                
                $activity = Activity::create([
                    'name' => $validated['activity_name'],
                    'date' => $dateFormatted->format('Y-m-d'),
                    'location' => $validated['place'],
                    'duration_minutes' => $validated['duration'],
                ]);
            }

            // Create submission
            $submission = Submission::create([
                'student_id' => $student->id,
                'activity_id' => $activity->id,
                'status' => 'Pending',
                'notes' => null,
                'duration_minutes' => $validated['duration'],
            ]);

            // Store proof image
            if ($request->hasFile('proof_image')) {
                $proofFile = $request->file('proof_image');
                $proofPath = $proofFile->store('submissions/proofs', 'public');
                
                FileAttachment::create([
                    'submission_id' => $submission->id,
                    'file_name' => $proofFile->getClientOriginalName(),
                    'file_type' => strtoupper($proofFile->getClientOriginalExtension()),
                    'url' => '/storage/' . $proofPath,
                    'size_mb' => $proofFile->getSize() / (1024 * 1024),
                ]);
            }

            // Store certificate image if provided
            if ($request->hasFile('certificate_image')) {
                $certFile = $request->file('certificate_image');
                $certPath = $certFile->store('submissions/certificates', 'public');
                
                FileAttachment::create([
                    'submission_id' => $submission->id,
                    'file_name' => $certFile->getClientOriginalName(),
                    'file_type' => strtoupper($certFile->getClientOriginalExtension()),
                    'url' => '/storage/' . $certPath,
                    'size_mb' => $certFile->getSize() / (1024 * 1024),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Activity submitted successfully!',
                'submission_id' => $submission->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating submission: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel a submission - DELETE from database
     * POST /student/submissions/{id}/cancel
     */
    public function cancel(Submission $submission)
    {
        // Get current logged-in student
        $studentId = session('user_id');
        
        if (!$studentId) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Please login first'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login first');
        }

        // Get student record
        $student = Student::where('user_id', $studentId)->firstOrFail();

        // Check if submission belongs to logged-in student
        if ($submission->student_id !== $student->id) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return redirect()->route('student.dashboard')
                ->with('error', 'Unauthorized: Cannot cancel this submission');
        }

        try {
            $activityId = $submission->activity_id;
            
            // Delete file attachments from storage
            $submission->fileAttachments()->each(function ($file) {
                if ($file->url) {
                    // Remove /storage/ prefix and delete from disk
                    $path = str_replace('/storage/', '', $file->url);
                    Storage::disk('public')->delete($path);
                }
                $file->delete();
            });

            // Delete submission
            $submission->delete();

            // Delete activity if no other submissions reference it
            $activity = Activity::find($activityId);
            if ($activity && $activity->submissions()->count() === 0) {
                $activity->delete();
            }

            // If AJAX request, return JSON
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Submission canceled successfully'
                ]);
            }

            return redirect()->route('student.dashboard')
                ->with('success', 'Submission canceled successfully');

        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error canceling submission: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('student.dashboard')
                ->with('error', 'Error canceling submission: ' . $e->getMessage());
        }
    }

    /**
     * Store a private comment on a submission
     * POST /student/submissions/{id}/comment
     */
    public function storeComment(Submission $submission, Request $request)
    {
        // Get current logged-in student
        $studentId = session('user_id');
        
        if (!$studentId) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        // Get student record
        $student = Student::where('user_id', $studentId)->firstOrFail();

        // Check if submission belongs to logged-in student
        if ($submission->student_id !== $student->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Validate comment
        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        try {
            // Create comment
            $comment = $submission->comments()->create([
                'student_id' => $student->id,
                'body' => $validated['content'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'comment' => [
                    'id' => $comment->id,
                    'name' => $student->name,
                    'content' => $comment->body,
                    'created_at' => $comment->created_at->format('M j, Y \a\t g:i A'),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding comment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a private comment
     * DELETE /comments/{id}
     */
    public function deleteComment(Comment $comment)
    {
        // Get current logged-in student
        $studentId = session('user_id');
        
        if (!$studentId) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        // Get student record
        $student = Student::where('user_id', $studentId)->firstOrFail();

        // Check if comment belongs to logged-in student
        if ($comment->student_id !== $student->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting comment: ' . $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Activity;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\FileAttachment;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubmissionManagementController extends Controller
{
    /**
     * Show submission form (GET /student/submissions/edit?activity=Basketball)
     */
    public function openSubmissionPage(Request $request)
    {
        return $this->submissionView($request);
    }

    /**
     * FEATURE: Render submission form view for activity details entry
     */
    public function submissionView(Request $request)
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
        // Validate submission data
        $validationResult = $this->validateSubmission($request);
        if (!$validationResult['success']) {
            return $validationResult['response'];
        }

        $validated = $validationResult['data'];

        // Get current logged-in student
        $studentId = session('user_id');
        
        if (!$studentId) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first',
            ], 401);
        }

        $student = Student::where('user_id', $studentId)->firstOrFail();

        // Prepare and submit activity
        $activityResult = $this->submitActivity($validated);
        if (!$activityResult['success']) {
            return $activityResult['response'];
        }

        $activity = $activityResult['activity'];

        // Store submission to database
        return $this->storeSubmission($request, $student, $activity, $validated);
    }

    /**
     * FEATURE: Validate submission form data and file uploads
     */
    private function validateSubmission(Request $request)
    {
        try {
            $validated = $request->validate([
                'activity_name' => 'required|string|max:100',
                'date' => 'required|date_format:d/m/Y',
                'duration' => 'required|integer|min:1',
                'place' => 'required|string|max:100',
                // FEATURE: File upload validation with size limits and MIME type restrictions
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

            return [
                'success' => true,
                'data' => $validated
            ];
        } catch (\Illuminate\Validation\ValidationException $e) {
            return [
                'success' => false,
                'response' => response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors()
                ], 422)
            ];
        }
    }

    /**
     * FEATURE: Find or create activity and prepare submission data
     */
    private function submitActivity($validated)
    {
        try {
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

            return [
                'success' => true,
                'activity' => $activity
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'response' => response()->json([
                    'success' => false,
                    'message' => 'Error preparing activity: ' . $e->getMessage(),
                ], 500)
            ];
        }
    }

    /**
     * FEATURE: Persist submission and file attachments to database
     */
    private function storeSubmission(Request $request, Student $student, Activity $activity, $validated)
    {
        try {
            // Create submission
            $submission = Submission::create([
                'student_id' => $student->id,
                'activity_id' => $activity->id,
                'status' => 'Pending',
                'notes' => null,
                'duration_minutes' => $validated['duration'],
            ]);

            // FEATURE: Secure file storage to public disk with organized directory structure
            if ($request->hasFile('proof_image')) {
                $proofFile = $request->file('proof_image');
                $proofPath = $proofFile->store('submissions/proofs', 'public');
                
                // FEATURE: File attachment metadata storage with size calculation
                FileAttachment::create([
                    'submission_id' => $submission->id,
                    'file_name' => $proofFile->getClientOriginalName(),
                    'file_type' => strtoupper($proofFile->getClientOriginalExtension()),
                    'url' => '/storage/' . $proofPath,
                    'size_mb' => $proofFile->getSize() / (1024 * 1024),
                ]);
            }

            // FEATURE: Optional certificate upload with separate directory structure
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
    public function SendCommentRequset(Submission $submission, Request $request)
    {
        // Get current logged-in user
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        // Try to get student record
        $student = Student::where('user_id', $userId)->first();
        $lecturer = null;
        $userName = null;

        if ($student) {
            // Student commenting - check if submission belongs to them
            if ($submission->student_id !== $student->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            
            // Block student from commenting if submission is rejected or accepted
            if ($submission->status === 'Rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your submission has been rejected. Please resubmit your activity; you can no longer send messages for this submission.'
                ], 403);
            }
            
            if ($submission->status === 'Accepted') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your submission has been accepted, you no longer can send message here'
                ], 403);
            }
            
            $userName = $student->name;
        } else {
            // Try to get lecturer record
            $lecturer = \App\Models\Lecturer::where('user_id', $userId)->first();
            if (!$lecturer) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }
            $userName = $lecturer->name;
        }

        // Validate comment
        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        try {
            // Create comment
            $comment = $submission->comments()->create([
                'student_id' => $student?->id,
                'lecturer_id' => $lecturer?->id,
                'body' => $validated['content'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'comment' => [
                    'id' => $comment->id,
                    'name' => $userName,
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
        // Get current logged-in user
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        // Try to get student record
        $student = Student::where('user_id', $userId)->first();
        $lecturer = null;

        if ($student) {
            // Student deleting - check if comment belongs to them
            if ($comment->student_id !== $student->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        } else {
            // Try to get lecturer record
            $lecturer = Lecturer::where('user_id', $userId)->first();
            if (!$lecturer) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }
            // Lecturer deleting - check if comment belongs to them
            if ($comment->lecturer_id !== $lecturer->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
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

    /**
     * Show resubmit form for a submission with NeedRevision status
     */
    public function beginRevision(Submission $submission)
    {
        // Check that submission belongs to current student
        $studentId = session('user_id');
        if (!$studentId) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $student = Student::where('user_id', $studentId)->firstOrFail();
        
        if ($submission->student_id !== $student->id) {
            return redirect()->route('student.status')->with('error', 'Unauthorized access');
        }

        if ($submission->status !== 'NeedRevision') {
            return redirect()->route('student.status')->with('error', 'Only submissions with "Need Revision" status can be resubmitted');
        }

        // Get activity and file data
        $activity = $submission->activity;
        $fileAttachments = $submission->fileAttachments;
        
        // Get proof image (first file in submission)
        $proofFile = null;
        $proofUrl = null;
        if ($fileAttachments && count($fileAttachments) > 0) {
            $proofFile = $fileAttachments[0]; // First file is proof
            $proofUrl = $proofFile->url;
        }
        
        // Generate full URL for proof image
        // URL can be: '/storage/...' or 'submissions/...' or full path
        $proofImage = '';
        if ($proofUrl) {
            // If URL starts with /storage/ or storage/, it's already a valid public URL
            if (strpos($proofUrl, '/storage/') === 0 || strpos($proofUrl, 'storage/') === 0) {
                $proofImage = $proofUrl;
            } elseif (strpos($proofUrl, 'submissions/') === 0) {
                // If it's just the path, prepend /storage/
                $proofImage = '/storage/' . $proofUrl;
            } else {
                // For other cases, try to generate via Storage::url()
                $url = ltrim($proofUrl, '/');
                if (strpos($url, 'public/') === 0) {
                    $url = substr($url, 7);
                }
                $proofImage = Storage::disk('public')->url($url);
            }
        }
        
        // Get certificate/membership (second file if exists)
        $certificateFile = null;
        if ($fileAttachments && count($fileAttachments) > 1) {
            $certificateFile = $fileAttachments[1]; // Second file is certificate
        }

        return view('student.submissions.resubmit', compact(
            'submission',
            'activity',
            'proofImage',
            'proofFile',
            'certificateFile'
        ));
    }

    /**
     * Store resubmission
     */
    public function submitRevision(Request $request, Submission $submission)
    {
        // Check authentication and authorization
        $studentId = session('user_id');
        if (!$studentId) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        $student = Student::where('user_id', $studentId)->firstOrFail();
        
        if ($submission->student_id !== $student->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($submission->status !== 'NeedRevision') {
            return response()->json(['success' => false, 'message' => 'Cannot resubmit this submission'], 400);
        }

        // Validate request
        try {
            $validated = $request->validate([
                'proof_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
                'certificate_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            // Handle proof image (optional - only update if new one provided)
            if ($request->hasFile('proof_image')) {
                // Delete old proof - look for first JPG/PNG/JPEG file (proof should be first)
                $oldProof = $submission->fileAttachments
                    ->whereIn('file_type', ['JPG', 'PNG', 'JPEG', 'jpg', 'png', 'jpeg'])
                    ->first();
                if ($oldProof) {
                    Storage::disk('public')->delete($oldProof->url);
                    $oldProof->delete();
                }

                // Upload new proof
                $proofFile = $request->file('proof_image');
                $extension = strtoupper($proofFile->getClientOriginalExtension());
                $proofPath = 'submissions/' . Str::uuid() . '-' . time() . '.' . $extension;
                Storage::disk('public')->put($proofPath, file_get_contents($proofFile));

                $fileSizeBytes = $proofFile->getSize();
                $fileSizeMB = round($fileSizeBytes / (1024 * 1024), 2);

                FileAttachment::create([
                    'submission_id' => $submission->id,
                    'file_type' => $extension,
                    'file_name' => $proofFile->getClientOriginalName(),
                    'url' => $proofPath,
                    'size_mb' => $fileSizeMB,
                ]);
            }
            // If no new proof uploaded, old proof is automatically preserved

            // Handle certificate image (optional - only update if new one provided)
            if ($request->hasFile('certificate_image')) {
                // Delete old certificate - look for second image or manually marked certificate
                // Since we don't have explicit 'certificate' type, we'll just delete any old cert
                // by checking if there's more than one file after proof
                $files = $submission->fileAttachments
                    ->whereIn('file_type', ['JPG', 'PNG', 'JPEG', 'jpg', 'png', 'jpeg'])
                    ->get();
                
                // If there are 2+ files, the last one should be certificate
                if ($files->count() > 1) {
                    $oldCert = $files->last();
                    Storage::disk('public')->delete($oldCert->url);
                    $oldCert->delete();
                }

                // Upload new certificate
                $certFile = $request->file('certificate_image');
                $extension = strtoupper($certFile->getClientOriginalExtension());
                $certPath = 'submissions/' . Str::uuid() . '-' . time() . '.' . $extension;
                Storage::disk('public')->put($certPath, file_get_contents($certFile));

                $fileSizeBytes = $certFile->getSize();
                $fileSizeMB = round($fileSizeBytes / (1024 * 1024), 2);
                FileAttachment::create([
                    'submission_id' => $submission->id,
                    'file_type' => $extension,
                    'file_name' => $certFile->getClientOriginalName(),
                    'url' => $certPath,
                    'size_mb' => $fileSizeMB,
                ]);
            }
            // If no new cert uploaded, old cert is automatically preserved

            // Update submission status back to Pending
            $submission->update([
                'status' => 'Pending',
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Resubmission successful! Your submission has been updated.',
                'redirect' => route('student.status'),
            ]);

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            \Log::error('Resubmit error', [
                'submission_id' => $submission->id,
                'error' => $errorMsg,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $errorMsg,
            ], 500);
        }
    }
}

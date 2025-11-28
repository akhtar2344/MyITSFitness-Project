<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Activity;
use App\Models\Student;
use App\Models\FileAttachment;
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
}

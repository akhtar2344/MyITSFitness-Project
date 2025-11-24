<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $submissions = Submission::with('student', 'activity', 'comments', 'fileAttachments')->paginate(15);
        return view('submission.index', compact('submissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('submission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|uuid|exists:student,id',
            'activity_id' => 'required|uuid|exists:activity,id',
            'status' => 'required|in:Pending,Accepted,Rejected,NeedRevision',
            'notes' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
        ]);

        $submission = Submission::create($validated);

        return redirect()->route('submissions.show', $submission->id)
                        ->with('success', 'Submission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $submission = Submission::with('student', 'activity', 'comments', 'fileAttachments', 'statusHistories')->findOrFail($id);
        return view('submission.show', compact('submission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $submission = Submission::findOrFail($id);
        return view('submission.edit', compact('submission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $submission = Submission::findOrFail($id);

        $validated = $request->validate([
            'status' => 'sometimes|required|in:Pending,Accepted,Rejected,NeedRevision',
            'notes' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
        ]);

        $submission->update($validated);

        return redirect()->route('submissions.show', $submission->id)
                        ->with('success', 'Submission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $submission = Submission::findOrFail($id);
        $submission->delete();

        return redirect()->route('submissions.index')
                        ->with('success', 'Submission deleted successfully.');
    }
}

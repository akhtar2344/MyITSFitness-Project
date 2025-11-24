<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RevisionRequest;

class RevisionRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $revisionRequests = RevisionRequest::with('submission', 'lecturer')->paginate(15);
        return view('revision-request.index', compact('revisionRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('revision-request.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'submission_id' => 'required|uuid|exists:submission,id',
            'lecturer_id' => 'required|uuid|exists:lecturer,id',
            'message' => 'required|string',
        ]);

        $revisionRequest = RevisionRequest::create($validated);

        return redirect()->route('revision-requests.show', $revisionRequest->id)
                        ->with('success', 'Revision request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $revisionRequest = RevisionRequest::with('submission', 'lecturer')->findOrFail($id);
        return view('revision-request.show', compact('revisionRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $revisionRequest = RevisionRequest::findOrFail($id);
        return view('revision-request.edit', compact('revisionRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $revisionRequest = RevisionRequest::findOrFail($id);

        $validated = $request->validate([
            'message' => 'sometimes|required|string',
            'resolved_at' => 'nullable|date',
        ]);

        $revisionRequest->update($validated);

        return redirect()->route('revision-requests.show', $revisionRequest->id)
                        ->with('success', 'Revision request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $revisionRequest = RevisionRequest::findOrFail($id);
        $revisionRequest->delete();

        return redirect()->route('revision-requests.index')
                        ->with('success', 'Revision request deleted successfully.');
    }
}

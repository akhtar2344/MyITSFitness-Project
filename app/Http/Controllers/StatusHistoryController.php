<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusHistory;

class StatusHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statusHistories = StatusHistory::with('submission')->paginate(15);
        return view('status-history.index', compact('statusHistories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('status-history.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'submission_id' => 'required|uuid|exists:submission,id',
            'from_status' => 'required|in:Pending,Accepted,Rejected,NeedRevision',
            'to_status' => 'required|in:Pending,Accepted,Rejected,NeedRevision',
            'note' => 'nullable|string',
        ]);

        $statusHistory = StatusHistory::create($validated);

        return redirect()->route('status-histories.show', $statusHistory->id)
                        ->with('success', 'Status history created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $statusHistory = StatusHistory::with('submission')->findOrFail($id);
        return view('status-history.show', compact('statusHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $statusHistory = StatusHistory::findOrFail($id);
        return view('status-history.edit', compact('statusHistory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $statusHistory = StatusHistory::findOrFail($id);

        $validated = $request->validate([
            'note' => 'nullable|string',
        ]);

        $statusHistory->update($validated);

        return redirect()->route('status-histories.show', $statusHistory->id)
                        ->with('success', 'Status history updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $statusHistory = StatusHistory::findOrFail($id);
        $statusHistory->delete();

        return redirect()->route('status-histories.index')
                        ->with('success', 'Status history deleted successfully.');
    }
}

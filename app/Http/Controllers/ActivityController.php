<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::with('submissions')->paginate(15);
        return view('activity.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activity.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'date' => 'required|date',
            'location' => 'required|string|max:100',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $activity = Activity::create($validated);

        return redirect()->route('activities.show', $activity->id)
                        ->with('success', 'Activity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $activity = Activity::with('submissions')->findOrFail($id);
        return view('activity.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $activity = Activity::findOrFail($id);
        return view('activity.edit', compact('activity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $activity = Activity::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'date' => 'sometimes|required|date',
            'location' => 'sometimes|required|string|max:100',
            'duration_minutes' => 'sometimes|required|integer|min:1',
        ]);

        $activity->update($validated);

        return redirect()->route('activities.show', $activity->id)
                        ->with('success', 'Activity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return redirect()->route('activities.index')
                        ->with('success', 'Activity deleted successfully.');
    }
}

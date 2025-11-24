<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::with('userAccount', 'submission')->paginate(15);
        return view('notification.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notification.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_account_id' => 'required|uuid|exists:user_account,id',
            'submission_id' => 'nullable|uuid|exists:submission,id',
            'type' => 'required|in:InApp,Email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $notification = Notification::create($validated);

        return redirect()->route('notifications.show', $notification->id)
                        ->with('success', 'Notification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notification = Notification::with('userAccount', 'submission')->findOrFail($id);
        return view('notification.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $notification = Notification::findOrFail($id);
        return view('notification.edit', compact('notification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $notification = Notification::findOrFail($id);

        $validated = $request->validate([
            'type' => 'sometimes|required|in:InApp,Email',
            'subject' => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
            'read_at' => 'nullable|date',
        ]);

        $notification->update($validated);

        return redirect()->route('notifications.show', $notification->id)
                        ->with('success', 'Notification updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->route('notifications.index')
                        ->with('success', 'Notification deleted successfully.');
    }
}

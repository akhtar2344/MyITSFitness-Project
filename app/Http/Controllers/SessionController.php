<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = Session::all();
        return view('sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sessions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:user_account,id',
            'token' => 'required|string|unique:session',
            'expires_at' => 'required|date',
        ]);

        Session::create($validated);
        return redirect()->route('sessions.index')->with('success', 'Session created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Session $session)
    {
        return view('sessions.show', compact('session'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Session $session)
    {
        return view('sessions.edit', compact('session'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Session $session)
    {
        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:user_account,id',
            'token' => 'required|string|unique:session,token,' . $session->id,
            'expires_at' => 'required|date',
            'revoked_at' => 'nullable|date',
        ]);

        $session->update($validated);
        return redirect()->route('sessions.index')->with('success', 'Session updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Session $session)
    {
        $session->delete();
        return redirect()->route('sessions.index')->with('success', 'Session deleted successfully.');
    }
}

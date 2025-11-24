<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use Illuminate\Http\Request;

class UserAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = UserAccount::all();
        return view('user-accounts.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user-accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:user_account',
            'password_hash' => 'required|string|min:8',
            'role' => 'required|in:Student,Lecturer',
            'is_active' => 'boolean',
        ]);

        UserAccount::create($validated);
        return redirect()->route('user-accounts.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAccount $userAccount)
    {
        return view('user-accounts.show', compact('userAccount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserAccount $userAccount)
    {
        return view('user-accounts.edit', compact('userAccount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserAccount $userAccount)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:user_account,email,' . $userAccount->id,
            'password_hash' => 'nullable|string|min:8',
            'role' => 'required|in:Student,Lecturer',
            'is_active' => 'boolean',
        ]);

        $userAccount->update($validated);
        return redirect()->route('user-accounts.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAccount $userAccount)
    {
        $userAccount->delete();
        return redirect()->route('user-accounts.index')->with('success', 'User deleted successfully.');
    }
}

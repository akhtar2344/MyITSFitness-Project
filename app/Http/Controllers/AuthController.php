<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Process login
     */
    public function processLogin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:1',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email',
            'password.required' => 'Password is required',
        ]);

        // Find user by email
        $user = UserAccount::where('email', $validated['email'])->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'Email not found in system'])
                ->withInput($request->only('email'));
        }

        // Verify password
        if (!Hash::check($validated['password'], $user->password_hash)) {
            return back()
                ->withErrors(['password' => 'Password is incorrect'])
                ->withInput($request->only('email'));
        }

        // Check if user is active
        if (!$user->is_active) {
            return back()
                ->withErrors(['email' => 'Your account has been deactivated. Please contact administrator.'])
                ->withInput($request->only('email'));
        }

        // Store session/login info
        session(['user_id' => $user->id, 'email' => $user->email, 'role' => $user->role]);

        // Redirect based on role
        if ($user->role === 'Student') {
            return redirect()->route('student.dashboard')
                ->with('success', 'Welcome back, Student!');
        } elseif ($user->role === 'Lecturer') {
            return redirect()->route('lecturer.dashboard')
                ->with('success', 'Welcome back, Lecturer!');
        }

        return back()
            ->withErrors(['email' => 'Invalid user role. Please contact support.'])
            ->withInput($request->only('email'));
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->forget(['user_id', 'email', 'role']);
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

    /**
     * Login with MyITS (placeholder)
     */
    public function loginWithMyITS()
    {
        // TODO: Implement MyITS OAuth/SAML integration
        return redirect()->route('login')->with('info', 'MyITS login coming soon');
    }
}

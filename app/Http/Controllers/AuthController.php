<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Hash;

// FEATURE: Authentication controller handling login/logout and session management
class AuthController extends Controller
{
    /**
     * FEATURE: Display login form view
     */
    public function openLoginPage()
    {
        return view('auth.login');
    }

    /**
     * FEATURE: Process user authentication with validation and session creation
     */
    public function loginReady(Request $request)
    {
        // FEATURE: Input validation with custom error messages
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:1',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email',
            'password.required' => 'Password is required',
        ]);

        // FEATURE: Database user lookup by email
        $user = UserAccount::where('email', $validated['email'])->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'Email not found in system'])
                ->withInput($request->only('email'));
        }

        // FEATURE: Secure password verification using Hash facade
        if (!Hash::check($validated['password'], $user->password_hash)) {
            return back()
                ->withErrors(['password' => 'Password is incorrect'])
                ->withInput($request->only('email'));
        }

        // FEATURE: Active user status verification
        if (!$user->is_active) {
            return back()
                ->withErrors(['email' => 'Your account has been deactivated. Please contact administrator.'])
                ->withInput($request->only('email'));
        }

        // FEATURE: Session creation with user credentials and role
        session(['user_id' => $user->id, 'email' => $user->email, 'role' => $user->role]);

        // FEATURE: Role-based routing after successful authentication
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
     * FEATURE: User logout with session cleanup
     */
    public function logout()
    {
        session()->forget(['user_id', 'email', 'role']);
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

    /**
     * FEATURE: MyITS OAuth integration placeholder for future SSO implementation
     */
    public function loginWithMyITS()
    {
        // TODO: Implement MyITS OAuth/SAML integration
        return redirect()->route('login')->with('info', 'MyITS login coming soon');
    }
}

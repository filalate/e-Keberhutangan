<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate the user
        $request->authenticate();

        $user = Auth::user();

        // Check if the user is an admin_negeri and their account is not verified
        if ($user->role === 'admin_negeri' && !$user->verified) {
            // Log the user out immediately if they are unverified
            Auth::logout();

            // Redirect back to login with a custom error message
            return redirect()->route('login')->withErrors([
                'email' => 'Akaun anda belum disahkan oleh Superadmin.',
            ]);
        }

        // Regenerate the session after successful authentication
        $request->session()->regenerate();

        // Redirect to the intended page or dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'negeri' => ['required', 'string'],
            'role' => ['required', 'string', 'in:pegawai,admin_negeri'], // Superadmin tidak boleh dipilih
        ]);

        // Create the user with 'verified' set to true (no Superadmin verification)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'negeri' => $request->negeri,
            'role' => $request->role,
            'verified' => true, // Set directly to true, no need for verification step
        ]);

        // Trigger the Registered event and log in the user
        event(new Registered($user));
        Auth::login($user);

        // Send the verification email (optional, if you want to ensure email verification)
        $user->sendEmailVerificationNotification();

        // Store success message and redirect to login
        // session()->flash('success', 'Pendaftaran berjaya. Sila log masuk.');

        // Redirect to login page
        // return redirect()->route('login');

        return redirect()->route('verification.notice')
                 ->with('status', 'verification-link-sent');
    }
}

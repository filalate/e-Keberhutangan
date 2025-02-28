<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminVerificationController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin_negeri')->where('verified', false)->get();
        return view('superadmin.verify_admins', compact('admins'));
    }

    public function verify($id)
    {
        $user = User::findOrFail($id);

        // Check if user is 'admin_negeri' and unverified
        if ($user->role == 'admin_negeri' && !$user->verified) {
            $user->verified = true;
            $user->save();

            return redirect()->back()->with('success', 'Admin Negeri telah disahkan.');
        }

        return redirect()->back()->withErrors('Pengguna ini tidak boleh disahkan.');
    }
}

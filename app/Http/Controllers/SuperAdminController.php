<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function paparNegeri($negeri)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized'); // Halang selain Superadmin
        }

        // Ambil data berdasarkan negeri yang dipilih
        $data = User::where('negeri', $negeri)->get();

        return view('dashboard', compact('data', 'negeri'));
    }
}

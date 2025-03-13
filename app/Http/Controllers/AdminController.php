<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        if (auth()->user()->isSuperAdmin()) {
            $data = User::all(); // Superadmin boleh akses semua negeri
        } else {
            $data = User::where('negeri', auth()->user()->negeri)->get(); // Admin Negeri hanya nampak data negeri sendiri
        }
        
        return view('dashboard', compact('data'));
    }
}

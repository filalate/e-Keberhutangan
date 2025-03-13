<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenyataGaji; // Ensure you import the models
use App\Models\PinjamanPerumahan;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Determine the state (negeri) to filter data
        $negeri = $request->negeri ?? auth()->user()->negeri;

        // Fetch data based on user role and selected state
        if (auth()->user()->role === 'superadmin' && $request->has('negeri')) {
            // Superadmin can select a specific state
            $data_penyata = PenyataGaji::where('negeri', $negeri)->count();
            $data_pinjaman = PinjamanPerumahan::where('negeri', $negeri)->count();
        } else {
            // Non-superadmin users can only view data for their own state
            $data_penyata = PenyataGaji::where('negeri', auth()->user()->negeri)->count();
            $data_pinjaman = PinjamanPerumahan::where('negeri', auth()->user()->negeri)->count();
        }

        // Pass data to the view
        return view('dashboard', compact('data_penyata', 'data_pinjaman', 'negeri'));
    }
}
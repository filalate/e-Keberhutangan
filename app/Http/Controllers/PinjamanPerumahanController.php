<?php

namespace App\Http\Controllers;

use App\Models\PinjamanPerumahan;
use App\Models\PenyataGaji;
use App\Models\User; // Import model User
use Illuminate\Http\Request;

class PinjamanPerumahanController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->isSuperAdmin() && $request->has('negeri')) {
            // Superadmin boleh pilih negeri
            $pinjaman_perumahan = PinjamanPerumahan::whereHas('user', function ($query) use ($request) {
                $query->where('negeri', $request->negeri);
            })->paginate(10);
        } else {
            // Admin Negeri hanya boleh lihat data negeri sendiri
            $pinjaman_perumahan = PinjamanPerumahan::whereHas('user', function ($query) {
                $query->where('negeri', auth()->user()->negeri);
            })->paginate(10);
        }

        return view('pinjaman_perumahan.index', compact('pinjaman_perumahan'));
    }

    public function create(Request $request)
    {
        // Admin hanya boleh lihat pegawai dalam negeri mereka
        // if(auth()->user()->role == 'admin_negeri') {
        //     // Get users only from the same 'negeri' as the logged-in admin
        //     $namaPegawaiList = User::where('negeri', auth()->user()->negeri)->get();
        // } else {
        //     // For superadmin or other roles, get all users
        //     // You can add a condition here if needed (e.g., excluding deleted users)
        //     $namaPegawaiList = User::all();
        // }

        $namaPegawaiList = PenyataGaji::all();

        return view('pinjaman_perumahan.create', compact('namaPegawaiList'));
    }    
    

    public function store(Request $request)
{
    // Validate the input data
    $validated = $request->validate([
        'nama_pegawai' => 'required|exists:penyata_gaji,nama_pegawai',  // Ensure 'nama_pegawai' exists in the 'pegawai' table
        'no_ic' => 'required|string|max:20',  // Validate 'no_ic' as a string with a max length
        'jawatan' => 'required|string|max:255',  // Validate 'jawatan' as a string with a max length
        'gred' => 'required|string|max:50',  // Validate 'gred' as a string with a max length
        'tempat_bertugas' => 'required|string|max:255',  // Validate 'tempat_bertugas' as a string with a max length
        'jumlah_pendapatan' => 'required|numeric|min:0',  // Validate 'jumlah_pendapatan' as numeric and not negative
    ]);

    // Ambil data dari penyata_gaji berdasarkan nama_pegawai
    $penyataGaji = PenyataGaji::where('nama_pegawai', $request->nama_pegawai)->first();

    if ($penyataGaji) {
        // Create a new Pinjaman Perumahan instance
        $pinjaman = new PinjamanPerumahan();
        $pinjaman->nama_pegawai = $request->nama_pegawai;
        $pinjaman->no_ic = $request->no_ic;
        $pinjaman->jawatan = $request->jawatan;
        $pinjaman->gred = $request->gred;
        $pinjaman->tempat_bertugas = $request->tempat_bertugas;
        $pinjaman->jumlah_pendapatan = $request->jumlah_pendapatan;
        
        // Ambil jumlah potongan dari penyata_gaji
        $pinjaman->jumlah_potongan = $penyataGaji->jumlah_keseluruhan;

        // Kirakan Agregat Keterhutangan (Jumlah Potongan / Jumlah Pendapatan) * 100
        $pinjaman->agregat_keterhutangan = ($pinjaman->jumlah_potongan / $pinjaman->jumlah_pendapatan) * 100;

        // Ambil jumlah pinjaman perumahan dari penyata_gaji
        $pinjaman->jumlah_pinjaman_perumahan = $penyataGaji->pinjaman_perumahan;

        // Kirakan Agregat Bersih ((Jumlah Potongan - Pinjaman Perumahan) / Jumlah Pendapatan) * 100
        $pinjaman->agregat_bersih = (($pinjaman->jumlah_potongan - $pinjaman->jumlah_pinjaman_perumahan) / $pinjaman->jumlah_pendapatan) * 100;

        // Assign the user_id to the currently logged-in user
        $pinjaman->user_id = auth()->id();

        // Save the Pinjaman Perumahan record
        $pinjaman->save();

        // Redirect back with a success message
        return redirect()->route('pinjaman-perumahan.index')->with('success', 'Borang Pinjaman Perumahan Berjaya Ditambah');
    } else {
        // If no penyata_gaji data is found, redirect back with an error message
        return redirect()->back()->with('error', 'Data Penyata Gaji tidak ditemukan.');
    }
}

    public function edit($id)
    {
        $pinjaman = PinjamanPerumahan::findOrFail($id);
        $namaPegawaiList = PenyataGaji::select('nama_pegawai')->distinct()->get();

        return view('pinjaman_perumahan.edit', compact('pinjaman', 'namaPegawaiList'));
    }

    public function show($id)
    {
        // Ambil data pinjaman berdasarkan ID
        $pinjaman = PinjamanPerumahan::findOrFail($id);

        // Hantar data ke view show.blade.php
        return view('pinjaman_perumahan.show', compact('pinjaman'));
    }

    public function update(Request $request, $id)
    {
        // Ambil rekod pinjaman berdasarkan ID
        $pinjaman = PinjamanPerumahan::findOrFail($id);
    
        // Validasi data input
        $validatedData = $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'no_ic' => 'required|string|max:20',
            'jawatan' => 'required|string|max:255',
            'gred' => 'required|string|max:50',
            'tempat_bertugas' => 'required|string|max:255',
            'jumlah_pendapatan' => 'required|numeric|min:0',
            'jumlah_potongan' => 'required|numeric|min:0',
            'jumlah_pinjaman_perumahan' => 'required|numeric|min:0',
        ]);
    
        // Pastikan nilai dalam format betul (2 titik perpuluhan)
        $validatedData['jumlah_pendapatan'] = number_format($validatedData['jumlah_pendapatan'], 2, '.', '');
        $validatedData['jumlah_potongan'] = number_format($validatedData['jumlah_potongan'], 2, '.', '');
        $validatedData['jumlah_pinjaman_perumahan'] = number_format($validatedData['jumlah_pinjaman_perumahan'], 2, '.', '');
    
        // Kira semula agregat keterhutangan dan agregat bersih
        $validatedData['agregat_keterhutangan'] = round(($validatedData['jumlah_potongan'] / $validatedData['jumlah_pendapatan']) * 100);
        $validatedData['agregat_bersih'] = round((($validatedData['jumlah_potongan'] - $validatedData['jumlah_pinjaman_perumahan']) / $validatedData['jumlah_pendapatan']) * 100);
    
        // Update rekod dalam database
        $pinjaman->update($validatedData);
    
        return redirect()->route('pinjaman-perumahan.index')->with('success', 'Borang Pinjaman Perumahan Berjaya Dikemaskini');
    }
    
    public function destroy($id)
    {
        $pinjaman = PinjamanPerumahan::find($id);
        
        if (!$pinjaman) {
            return redirect()->route('pinjaman-perumahan.index')->with('error', 'Data tidak wujud!');
        }
    
        $pinjaman->delete();
        return redirect()->route('pinjaman-perumahan.index')->with('success', 'Borang Pinjaman Perumahan Berjaya Dipadam');
    }
}

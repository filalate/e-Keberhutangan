<?php

namespace App\Http\Controllers;

use App\Models\PinjamanPerumahan;
use App\Models\PenyataGaji; // Untuk tarik data dari Penyataan Gaji
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
        if(auth()->user()->role == 'admin_negeri') {
            $namaPegawaiList = User::where('negeri', auth()->user()->negeri)->get();
        } else {
            // Untuk superadmin atau lain-lain, boleh lihat semua pegawai
            $namaPegawaiList = User::all();
        }

        return view('pinjaman_perumahan.create', compact('namaPegawaiList'));
    }
    

    public function store(Request $request)
    {
        // Ambil data dari penyata_gaji berdasarkan nama_pegawai
        $penyataGaji = PenyataGaji::where('nama_pegawai', $request->nama_pegawai)->first();

        if ($penyataGaji) {
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

            $pinjaman->user_id = auth()->id();  // Assign the user_id to the current logged-in user
            $pinjaman->save();

            return redirect()->route('pinjaman-perumahan.index')->with('success', 'Borang Pinjaman Perumahan Berjaya Ditambah');
        } else {
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
        return redirect()->route('pinjaman-perumahan.index')->with('success', 'Borang Pinjaman Perumahan Berjaya Dihapuskan');
    }
}

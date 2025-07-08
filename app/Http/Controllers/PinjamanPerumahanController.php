<?php

namespace App\Http\Controllers;

use App\Models\PinjamanPerumahan;
use App\Models\PenyataGaji;
use App\Models\User;
use Illuminate\Http\Request;

class PinjamanPerumahanController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', null);

        $pinjaman_perumahan = PinjamanPerumahan::query();

        // First check if user is authenticated
        if (!auth()->check()) {
            // Handle unauthenticated case - redirect to login or return error
            return redirect()->route('login');
        }

        // Now safely access user methods
        if (auth()->user()->isSuperAdmin() && $request->has('negeri') && $request->negeri !== '') {
            $pinjaman_perumahan->whereHas('user', function ($query) use ($request) {
                $query->where('negeri', $request->negeri);
            });
        } elseif (auth()->user()->isSuperAdmin()) {
            $pinjaman_perumahan->where('user_id', auth()->id());
        } else {
            $pinjaman_perumahan->whereHas('user', function ($query) {
                $query->where('negeri', auth()->user()->negeri);
            });
        }
    
        if ($month && $year) {
            $pinjaman_perumahan->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        } elseif ($year) {
            $pinjaman_perumahan->whereYear('created_at', $year);
        } elseif ($month) {
            $pinjaman_perumahan->whereMonth('created_at', $month);
        }
    
        $pinjaman_perumahan = $pinjaman_perumahan->paginate(10);
    
        return view('pinjaman_perumahan.index', compact('pinjaman_perumahan', 'year', 'month'));
    }

    public function create(Request $request)
    {
        $user_id = auth()->id();
        $namaPegawaiList = PenyataGaji::where('user_id', $user_id)->get();

        return view('pinjaman_perumahan.create', compact('namaPegawaiList'));
    }    
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pegawai' => 'required|exists:penyata_gaji,nama_pegawai',
            'no_ic' => 'required|string|max:20',
            'jawatan' => 'required|string|max:255',
            'tempat_bertugas' => 'required|string|max:255',
            'jumlah_pendapatan' => 'required|numeric|min:0',
        ]);

        $penyataGaji = PenyataGaji::where('nama_pegawai', $request->nama_pegawai)->first();

        if ($penyataGaji) {
            $pinjaman = new PinjamanPerumahan();
            $pinjaman->nama_pegawai = $request->nama_pegawai;
            $pinjaman->no_ic = $request->no_ic;
            $pinjaman->jawatan = $request->jawatan;
            $pinjaman->gred = $penyataGaji->gred; // Fetch gred from penyata gaji
            $pinjaman->tempat_bertugas = $request->tempat_bertugas;
            $pinjaman->jumlah_pendapatan = $request->jumlah_pendapatan;
            $pinjaman->jumlah_potongan = $penyataGaji->jumlah_keseluruhan;
            $pinjaman->agregat_keterhutangan = ($pinjaman->jumlah_potongan / $pinjaman->jumlah_pendapatan) * 100;
            $pinjaman->jumlah_pinjaman_perumahan = $penyataGaji->pinjaman_perumahan;
            $pinjaman->agregat_bersih = (($pinjaman->jumlah_potongan - $pinjaman->jumlah_pinjaman_perumahan) / $pinjaman->jumlah_pendapatan) * 100;
            $pinjaman->user_id = auth()->id();
            $pinjaman->penyata_gaji_id = $penyataGaji->id;

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
        $pinjaman = PinjamanPerumahan::findOrFail($id);
        return view('pinjaman_perumahan.show', compact('pinjaman'));
    }

    public function update(Request $request, $id)
    {
        $pinjaman = PinjamanPerumahan::findOrFail($id);
    
        $validatedData = $request->validate([
            'no_ic' => 'required|string|max:20',
            'jawatan' => 'required|string|max:255',
            'tempat_bertugas' => 'required|string|max:255',
            'jumlah_pendapatan' => 'required|numeric|min:0',
            'jumlah_potongan' => 'required|numeric|min:0',
            'jumlah_pinjaman_perumahan' => 'required|numeric|min:0',
        ]);
    
        $validatedData['jumlah_pendapatan'] = number_format($validatedData['jumlah_pendapatan'], 2, '.', '');
        $validatedData['jumlah_potongan'] = number_format($validatedData['jumlah_potongan'], 2, '.', '');
        // $validatedData['jumlah_pinjaman_perumahan'] = number_format($validatedData['jumlah_pinjaman_perumahan'], 2, '.', '');
    
        $validatedData['agregat_keterhutangan'] = round(($validatedData['jumlah_potongan'] / $validatedData['jumlah_pendapatan']) * 100);
        $validatedData['agregat_bersih'] = round((($validatedData['jumlah_potongan'] - $validatedData['jumlah_pinjaman_perumahan']) / $validatedData['jumlah_pendapatan']) * 100);
    
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

    public function searchApi(Request $request)
    {
        $id_pegawai = $request->query('id_pegawai');
        $penyataGaji = PenyataGaji::find($id_pegawai);

        if ($penyataGaji) {
            return response()->json([
                'nama_pegawai' => $penyataGaji->nama_pegawai,
                'gred' => $penyataGaji->gred,
                'jumlah_keseluruhan' => $penyataGaji->jumlah_keseluruhan,
                'pinjaman_perumahan' => $penyataGaji->pinjaman_perumahan,
            ]);
        } else {
            return response()->json(null);  
        }
    }
}
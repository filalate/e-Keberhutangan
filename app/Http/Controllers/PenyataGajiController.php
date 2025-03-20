<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\PenyataGaji;
use Illuminate\Support\Facades\Auth;

class PenyataGajiController extends Controller {
    // Constructor removed middleware
    public function __construct() {
        // No need to use middleware() here directly
    }

    public function index(Request $request)
    {

        if (auth()->user()->isSuperAdmin() && $request->has('negeri') && $request->negeri !== '') {
            // Superadmin memilih negeri tertentu
            $penyata_gaji = PenyataGaji::whereHas('user', function ($query) use ($request) {
                $query->where('negeri', $request->negeri);
            })->paginate(10);
        } elseif (auth()->user()->isSuperAdmin()) {
            // Superadmin boleh lihat semua data
            $penyata_gaji = PenyataGaji::paginate(10);
        } else {
            // Admin negeri hanya boleh lihat negeri sendiri
            $penyata_gaji = PenyataGaji::whereHas('user', function ($query) {
                $query->where('negeri', auth()->user()->negeri);
            })->paginate(10);
        }

        return view('penyata_gaji.index', compact('penyata_gaji'));
    }
    
    public function create() {
        return view('penyata_gaji.create');
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'pinjaman_peribadi_bsn' => 'nullable|numeric',
            'pinjaman_perumahan' => 'nullable|numeric',
            'bayaran_balik_itp' => 'nullable|numeric',
            'bayaran_balik_bsh' => 'nullable|numeric',
            'ptptn' => 'nullable|numeric',
            'kutipan_semula_emolumen' => 'nullable|numeric',
            'arahan_potongan_nafkah' => 'nullable|numeric',
            'komputer' => 'nullable|numeric',
            'pcb' => 'nullable|numeric',
            'lain_lain_potongan_pembentungan' => 'nullable|numeric',
            'koperasi' => 'nullable|numeric',
            'berkat' => 'nullable|numeric',
            'angkasa' => 'nullable|numeric',
            'potongan_lembaga_th' => 'nullable|numeric',
            'amanah_saham_nasional' => 'nullable|numeric',
            'zakat_yayasan_wakaf' => 'nullable|numeric',
            'insuran' => 'nullable|numeric',
            'kwsp' => 'nullable|numeric',
            'i_destinasi' => 'nullable|numeric',
            'angkasa_bukan_pinjaman' => 'nullable|numeric',
        ]);
    
        // Kira jumlah hutang
        $validatedData['jumlah_hutang'] = 
            ($validatedData['pinjaman_peribadi_bsn'] ?? 0) +
            ($validatedData['pinjaman_perumahan'] ?? 0) +
            ($validatedData['bayaran_balik_itp'] ?? 0) +
            ($validatedData['bayaran_balik_bsh'] ?? 0) +
            ($validatedData['ptptn'] ?? 0) +
            ($validatedData['kutipan_semula_emolumen'] ?? 0) +
            ($validatedData['arahan_potongan_nafkah'] ?? 0) +
            ($validatedData['komputer'] ?? 0) +
            ($validatedData['pcb'] ?? 0) +
            ($validatedData['lain_lain_potongan_pembentungan'] ?? 0) +
            ($validatedData['koperasi'] ?? 0) +
            ($validatedData['berkat'] ?? 0) +
            ($validatedData['angkasa'] ?? 0);
    
        // Kira jumlah bukan hutang
        $validatedData['jumlah_bukan_hutang'] = 
            ($validatedData['potongan_lembaga_th'] ?? 0) +
            ($validatedData['amanah_saham_nasional'] ?? 0) +
            ($validatedData['zakat_yayasan_wakaf'] ?? 0) +
            ($validatedData['insuran'] ?? 0) +
            ($validatedData['kwsp'] ?? 0) +
            ($validatedData['i_destinasi'] ?? 0) +
            ($validatedData['angkasa_bukan_pinjaman'] ?? 0);
    
        // Kira jumlah keseluruhan
        $validatedData['jumlah_keseluruhan'] = 
        $validatedData['jumlah_hutang'] + $validatedData['jumlah_bukan_hutang'];
        $validatedData['user_id'] = auth()->id(); 

        // Simpan data ke dalam database
        PenyataGaji::create($validatedData);
    
        return redirect()->route('penyata-gaji.index')->with('success', 'Penyata Gaji berjaya ditambah');
    }

    public function edit($id)
    {
        $penyata = PenyataGaji::findOrFail($id);
        return view('penyata_gaji.edit', compact('penyata'));
    }

    public function show($id)
    {
        $penyataGaji = PenyataGaji::findOrFail($id);
        return view('penyata_gaji.show', compact('penyataGaji'));
    }

    public function update(Request $request, $id) {
        $penyata = PenyataGaji::findOrFail($id);
        $penyata->user_id = auth()->id(); // Pastikan admin negeri hanya update data sendiri
    
        $validatedData = $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'pinjaman_peribadi_bsn' => 'nullable|numeric',
            'pinjaman_perumahan' => 'nullable|numeric',
            'bayaran_balik_itp' => 'nullable|numeric',
            'bayaran_balik_bsh' => 'nullable|numeric',
            'ptptn' => 'nullable|numeric',
            'kutipan_semula_emolumen' => 'nullable|numeric',
            'arahan_potongan_nafkah' => 'nullable|numeric',
            'komputer' => 'nullable|numeric',
            'pcb' => 'nullable|numeric',
            'lain_lain_potongan_pembentungan' => 'nullable|numeric',
            'koperasi' => 'nullable|numeric',
            'berkat' => 'nullable|numeric',
            'angkasa' => 'nullable|numeric',
            'potongan_lembaga_th' => 'nullable|numeric',
            'amanah_saham_nasional' => 'nullable|numeric',
            'zakat_yayasan_wakaf' => 'nullable|numeric',
            'insuran' => 'nullable|numeric',
            'kwsp' => 'nullable|numeric',
            'i_destinasi' => 'nullable|numeric',
            'angkasa_bukan_pinjaman' => 'nullable|numeric',
        ]);
    
        // Kira jumlah hutang
        $jumlahHutang = $request->pinjaman_peribadi_bsn + 
        $request->pinjaman_perumahan + 
        $request->bayaran_balik_itp + 
        $request->bayaran_balik_bsh + 
        $request->ptptn + 
        $request->kutipan_semula_emolumen + 
        $request->arahan_potongan_nafkah + 
        $request->komputer + 
        $request->pcb + 
        $request->lain_lain_potongan_pembentungan + 
        $request->koperasi + 
        $request->berkat + 
        $request->angkasa;
    
        // Kira jumlah bukan hutang
        $jumlahBukanHutang = $request->potongan_lembaga_th + 
        $request->amanah_saham_nasional + 
        $request->zakat_yayasan_wakaf + 
        $request->insuran + 
        $request->kwsp + 
        $request->i_destinasi + 
        $request->angkasa_bukan_pinjaman;
    
        /// Kira jumlah keseluruhan
        $jumlahKeseluruhan = $jumlahHutang + $jumlahBukanHutang;
    
        // Kemaskini data dalam database
        $penyata->update(array_merge($validatedData, [
            'jumlah_hutang' => $jumlahHutang,
            'jumlah_bukan_hutang' => $jumlahBukanHutang,
            'jumlah_keseluruhan' => $jumlahKeseluruhan
        ]));
    
        return redirect()->route('penyata-gaji.index')->with('success', 'Penyata Gaji berjaya dikemaskini');
    }

    public function destroy($id)
    {
        $penyata = PenyataGaji::find($id);
        if (!$penyata) {
            return redirect()->route('penyata-gaji.index')->with('error', 'Data tidak wujud!');
        }
    
        // Delete the PenyataGaji
        $penyata->delete();
    
        // Redirect back with success message
        return redirect()->route('penyata-gaji.index')->with('success', 'Penyata Gaji berjaya dipadam');
    }

    public function search(Request $request)
    {
        // Get the pegawai's name (nama_pegawai) from the query string
        $id_pegawai = $request->query('id_pegawai');

        // Find the Penyata Gaji based on the nama_pegawai directly from the PenyataGaji table
        $penyataGaji = PenyataGaji::where('id', $id_pegawai)->first();

        // Check if Penyata Gaji exists and return the data
        if ($penyataGaji) {
            return response()->json([
                'nama_pegawai' => $penyataGaji->nama_pegawai,  // Fetch nama_pegawai directly from PenyataGaji table
                'jumlah_keseluruhan' => $penyataGaji->jumlah_keseluruhan,
                'pinjaman_perumahan' => $penyataGaji->pinjaman_perumahan,
            ]);
        } else {
            // If Penyata Gaji not found, return null
            return response()->json(null);  
        }
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenyataGaji;
use Illuminate\Support\Facades\Auth;

class PenyataGajiController extends Controller {
    // Constructor removed middleware
    public function __construct() {
        // No need to use middleware() here directly
    }

    public function index() {
        $penyata = PenyataGaji::paginate(10);
        return view('penyata_gaji.index', compact('penyata'));  // Correct view path
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

    public function destroy(PenyataGaji $penyata) {
        $penyata->delete();
        return redirect()->route('penyata-gaji.index')->with('success', 'Penyata Gaji berjaya dipadam');
    }
}

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
            'lain_lain_potongan' => 'nullable|numeric',
            'koperasi' => 'nullable|numeric',
            'berkat' => 'nullable|numeric',
            'angkasa_hutang' => 'nullable|numeric',
            'potongan_lembaga_th' => 'nullable|numeric',
            'amanah_saham_nasional' => 'nullable|numeric',
            'zakat_yapiem' => 'nullable|numeric',
            'insuran' => 'nullable|numeric',
            'kwsp' => 'nullable|numeric',
            'i_destinasi' => 'nullable|numeric',
            'angkasa_bukan_pinjaman' => 'nullable|numeric',
        ]);

        // Save data to database
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
    $penyata = PenyataGaji::findOrFail($id); // Ambil data berdasarkan ID
        $validatedData = $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'pinjaman_peribadi_bsn' => 'nullable|numeric',
            'pinjaman_perumahan' => 'nullable|numeric',
            'bayaran_itp' => 'nullable|numeric',
            'bayaran_bsh' => 'nullable|numeric',
            'ptptn' => 'nullable|numeric',
            'kutipan_semula_emolumen' => 'nullable|numeric',
            'arahan_potongan_nafkah' => 'nullable|numeric',
            'komputer' => 'nullable|numeric',
            'pcb' => 'nullable|numeric',
            'lain_lain_potongan' => 'nullable|numeric',
            'koperasi' => 'nullable|numeric',
            'berkat' => 'nullable|numeric',
            'angkasa_hutang' => 'nullable|numeric',
            'potongan_lembaga_th' => 'nullable|numeric',
            'amanah_saham_nasional' => 'nullable|numeric',
            'zakat_yayasan_wakaf' => 'nullable|numeric',
            'insuran' => 'nullable|numeric',
            'kwsp' => 'nullable|numeric',
            'i_destinasi' => 'nullable|numeric',
            'angkasa_bukan_pinjaman' => 'nullable|numeric',
        ]);

        // Update data in database
        $penyata->update($validatedData);

        return redirect()->route('penyata-gaji.index')->with('success', 'Penyata Gaji berjaya dikemaskini');
    }

    public function destroy(PenyataGaji $penyata) {
        $penyata->delete();
        return redirect()->route('penyata-gaji.index')->with('success', 'Penyata Gaji berjaya dipadam');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenyataGaji;
use App\Models\PinjamanPerumahan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PenyataGajiController extends Controller
{
    public function __construct()
    {
        // No middleware needed
    }

    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', null);

        $penyata_gaji = PenyataGaji::query();

        if (auth()->user()->isSuperAdmin() && $request->has('negeri') && $request->negeri !== '') {
            $penyata_gaji->whereHas('user', function ($query) use ($request) {
                $query->where('negeri', $request->negeri);
            });
        } elseif (auth()->user()->isSuperAdmin()) {
            $penyata_gaji->where('user_id', auth()->id());
        } else {
            $penyata_gaji->whereHas('user', function ($query) {
                $query->where('negeri', auth()->user()->negeri);
            });
        }

        if ($month && $year) {
            $penyata_gaji->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        } elseif ($year) {
            $penyata_gaji->whereYear('created_at', $year);
        } elseif ($month) {
            $penyata_gaji->whereMonth('created_at', $month);
        }

        $penyata_gaji = $penyata_gaji->paginate(10);

        return view('penyata_gaji.index', compact('penyata_gaji', 'year', 'month'));
    }

    public function create()
    {
        return view('penyata_gaji.create');
    }

    public function store(Request $request)
    {
        try {
            // Combine gred_huruf and gred_nombor into gred
            $request->merge([
                'gred' => $request->gred_huruf . $request->gred_nombor
            ]);

            $validatedData = $request->validate([
                'nama_pegawai' => 'required|string|max:255',
                'jantina' => 'required|string',
                'gred' => 'required|string',
                'gred_huruf' => 'required|string',
                'gred_nombor' => 'required|string',
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

            // Calculate totals
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

            $validatedData['jumlah_bukan_hutang'] = 
                ($validatedData['potongan_lembaga_th'] ?? 0) +
                ($validatedData['amanah_saham_nasional'] ?? 0) +
                ($validatedData['zakat_yayasan_wakaf'] ?? 0) +
                ($validatedData['insuran'] ?? 0) +
                ($validatedData['kwsp'] ?? 0) +
                ($validatedData['i_destinasi'] ?? 0) +
                ($validatedData['angkasa_bukan_pinjaman'] ?? 0);

            $validatedData['jumlah_keseluruhan'] = 
                $validatedData['jumlah_hutang'] + $validatedData['jumlah_bukan_hutang'];
            
            $validatedData['user_id'] = auth()->id();

            PenyataGaji::create($validatedData);

            return redirect()->route('penyata-gaji.index')->with('success', 'Penyata Gaji berjaya ditambah');

        } catch (\Exception $e) {
            Log::error('Error storing penyata gaji: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $penyataGaji = PenyataGaji::findOrFail($id);
        return view('penyata_gaji.show', compact('penyataGaji'));
    }

    public function edit($id)
    {
        $penyata = PenyataGaji::findOrFail($id);
        
        // Verify ownership
        if ($penyata->user_id != auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('penyata_gaji.edit', compact('penyata'));
    }

    public function update(Request $request, $id)
    {
        try {
            $penyata = PenyataGaji::findOrFail($id);
            
            // Verify ownership
            if ($penyata->user_id != auth()->id() && !auth()->user()->isSuperAdmin()) {
                abort(403, 'Unauthorized action.');
            }

            // Combine gred_huruf and gred_nombor into gred
            $request->merge([
                'gred' => $request->gred_huruf . $request->gred_nombor
            ]);

            $validatedData = $request->validate([
                'nama_pegawai' => 'required|string|max:255',
                'jantina' => 'required|string',
                'gred' => 'required|string',
                'gred_huruf' => 'required|string',
                'gred_nombor' => 'required|string',
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

            // Calculate totals
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

            $validatedData['jumlah_bukan_hutang'] = 
                ($validatedData['potongan_lembaga_th'] ?? 0) +
                ($validatedData['amanah_saham_nasional'] ?? 0) +
                ($validatedData['zakat_yayasan_wakaf'] ?? 0) +
                ($validatedData['insuran'] ?? 0) +
                ($validatedData['kwsp'] ?? 0) +
                ($validatedData['i_destinasi'] ?? 0) +
                ($validatedData['angkasa_bukan_pinjaman'] ?? 0);

            $validatedData['jumlah_keseluruhan'] = 
                $validatedData['jumlah_hutang'] + $validatedData['jumlah_bukan_hutang'];

            // $penyata->update($validatedData);

            if ($penyata->update($validatedData)) {
                $pinjaman = PinjamanPerumahan::where("penyata_gaji_id", $penyata->id)->first();
                if ($pinjaman) {
                    $pinjaman->nama_pegawai = $validatedData['nama_pegawai'];
                    $pinjaman->jumlah_pinjaman_perumahan = $validatedData['pinjaman_perumahan'];
                    $pinjaman->save();
                }
            }

            return redirect()->route('penyata-gaji.index')->with('success', 'Penyata Gaji berjaya dikemaskini');

        } catch (\Exception $e) {
            Log::error('Error updating penyata gaji: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengemaskini data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $penyata = PenyataGaji::findOrFail($id);
            
            // Verify ownership
            if ($penyata->user_id != auth()->id() && !auth()->user()->isSuperAdmin()) {
                abort(403, 'Unauthorized action.');
            }

            $penyata->delete();
            return redirect()->route('penyata-gaji.index')->with('success', 'Penyata Gaji berjaya dipadam');

        } catch (\Exception $e) {
            Log::error('Error deleting penyata gaji: ' . $e->getMessage());
            return redirect()->route('penyata-gaji.index')->with('error', 'Gagal memadam data: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $id_pegawai = $request->query('id_pegawai');
        $penyataGaji = PenyataGaji::where('id', $id_pegawai)->first();

        if ($penyataGaji) {
            return response()->json([
                'nama_pegawai' => $penyataGaji->nama_pegawai,
                'jumlah_keseluruhan' => $penyataGaji->jumlah_keseluruhan,
                'pinjaman_perumahan' => $penyataGaji->pinjaman_perumahan,
            ]);
        } else {
            return response()->json(null);  
        }
    }
}
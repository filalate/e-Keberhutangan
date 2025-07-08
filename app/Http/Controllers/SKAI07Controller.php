<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SKAI07;
use App\Models\PinjamanPerumahan;
use App\Models\User; // Import model User

class SKAI07Controller extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun dan bulan dari request, dengan default semasa
        $year = $request->input('year', date('Y'));  // Default tahun semasa
        $month = $request->input('month', null);  // Default bulan adalah null

        // Mulakan query untuk mendapatkan data
        $skai07 = SKAI07::query();

        // Periksa jika superadmin dan ada filter negeri
        if (auth()->user()->isSuperAdmin() && $request->has('negeri') && $request->negeri !== '') {
            // Superadmin memilih negeri tertentu
            $skai07->whereHas('user', function ($query) use ($request) {
                $query->where('negeri', $request->negeri);
            });
        } elseif (auth()->user()->isSuperAdmin()) {
             // Jika superadmin tidak memilih negeri, hanya data superadmin sahaja dipaparkan
             $skai07->where('user_id', auth()->id()); // Filter berdasarkan user_id superadmini
        } else {
            // Admin negeri hanya boleh lihat negeri sendiri
            $skai07->whereHas('user', function ($query) {
                $query->where('negeri', auth()->user()->negeri);
            });
        }

        // Condition untuk tahun dan bulan
        if ($month && $year) {
            // Jika kedua-dua tahun dan bulan dipilih
            $skai07->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        } elseif ($year) {
            // Jika hanya tahun dipilih
            $skai07->whereYear('created_at', $year);
        } elseif ($month) {
            // Jika hanya bulan dipilih, tanpa tahun
            $skai07->whereMonth('created_at', $month);
        }

        // Ambil data dan paginate
        $skai07 = $skai07->paginate(10);

        // Return view dengan data yang difilter
        return view('borang.index', compact('skai07', 'year', 'month'));
    }

    public function create()
    {
        $user_id = auth()->id();
        // Ambil senarai pegawai yang agregat_bersih > 60%
        $pinjaman = PinjamanPerumahan::where('agregat_bersih', '>', 60)
                                    ->where('user_id', $user_id)
                                    ->pluck('nama_pegawai', 'id'); // Hanya pegawai dengan agregat_bersih > 60%

                                    
        return view('borang.create', compact('pinjaman'));
    }

    public function store(Request $request)
{
    // Kira jumlah pendapatan
    $jumlahPendapatan = $request->gaji + $request->elaun + $request->sewa_rumah + 
                        $request->sewa_kenderaan + $request->sumbangan_suami_isteri + 
                        $request->lain_lain_pendapatan;

    // Kira jumlah perbelanjaan
    $jumlahPerbelanjaan = $request->rumah + $request->kereta + $request->motorsikal +
                          $request->komputer + $request->tabung_haji + $request->asb + 
                          $request->simpanan + $request->zakat + $request->lain2_bercagar + 
                          $request->pinjaman_peribadi + $request->kad_kredit + $request->lain2_tidak_bercagar;

    // Kira lebihan pendapatan
    $lebihanPendapatan = $jumlahPendapatan - $jumlahPerbelanjaan;

    // Kira peratusan liabiliti tidak bercagar
    $percentLiabilitiTidakBercagar = ($jumlahPendapatan > 0) ? 
        ($request->pinjaman_peribadi + $request->kad_kredit + $request->lain2_tidak_bercagar) / $jumlahPendapatan * 100 : 0;

    // Simpan dalam database
    SKAI07::create([
        'nama' => $request->nama,
        'no_kad_pengenalan' => $request->no_kad_pengenalan,
        'no_badan' => $request->no_badan,
        'gred' => $request->gred,
        'jawatan' => $request->jawatan,
        'gaji' => $request->gaji,
        'elaun' => $request->elaun,
        'sewa_rumah' => $request->sewa_rumah,
        'sewa_kenderaan' => $request->sewa_kenderaan,
        'sumbangan_suami_isteri' => $request->sumbangan_suami_isteri,
        'lain_lain_pendapatan' => $request->lain_lain_pendapatan,
        'rumah' => $request->rumah,
        'kereta' => $request->kereta,
        'motorsikal' => $request->motorsikal,
        'komputer' => $request->komputer,
        'tabung_haji' => $request->tabung_haji,
        'asb' => $request->asb,
        'simpanan' => $request->simpanan,
        'zakat' => $request->zakat,
        'lain2_bercagar' => $request->lain2_bercagar,
        'pinjaman_peribadi' => $request->pinjaman_peribadi,
        'kad_kredit' => $request->kad_kredit,
        'lain2_tidak_bercagar' => $request->lain2_tidak_bercagar,
        'jumlah_pendapatan' => $jumlahPendapatan,
        'jumlah_perbelanjaan' => $jumlahPerbelanjaan,
        'lebihan_pendapatan' => $lebihanPendapatan,
        'percent_liabiliti_tidak_bercagar' => $percentLiabilitiTidakBercagar,
        'jantina' => $request->jantina, // Add the gender field here
        'user_id' => auth()->id(), // Simpan user_id yang sedang log masuk
    ]);

    return redirect()->route('borang.index')->with('success', 'Data berjaya disimpan!');
}


    public function show($id)
    {
        $skai07 = SKAI07::findOrFail($id);
        return view('borang.show', compact('skai07'));
    }

    public function edit($id)
    {
        $skai07 = SKAI07::findOrFail($id);
        return view('borang.edit', compact('skai07'));
    }

    public function update(Request $request, $id) 
    {
        $skai07 = SKAI07::findOrFail($id);

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'no_kad_pengenalan' => 'required|numeric',
            'no_badan' => 'required|string',
            'gred' => 'required|string',
            'jawatan' => 'required|string',
            'gaji' => 'required|numeric',
            'elaun' => 'nullable|numeric',
            'sewa_rumah' => 'nullable|numeric',
            'sewa_kenderaan' => 'nullable|numeric',
            'sumbangan_suami_isteri' => 'nullable|numeric',
            'lain_lain_pendapatan' => 'nullable|numeric',
            'rumah' => 'nullable|numeric',
            'kereta' => 'nullable|numeric',
            'motorsikal' => 'nullable|numeric',
            'komputer' => 'nullable|numeric',
            'tabung_haji' => 'nullable|numeric',
            'asb' => 'nullable|numeric',
            'simpanan' => 'nullable|numeric',
            'zakat' => 'nullable|numeric',
            'lain2_bercagar' => 'nullable|numeric',
            'pinjaman_peribadi' => 'nullable|numeric',
            'kad_kredit' => 'nullable|numeric',
            'lain2_tidak_bercagar' => 'nullable|numeric'
        ]);

        // Kira semula jumlah pendapatan
        $jumlahPendapatan = $request->gaji + $request->elaun + $request->sewa_rumah + 
                            $request->sewa_kenderaan + $request->sumbangan_suami_isteri + 
                            $request->lain_lain_pendapatan;

        // Kira jumlah perbelanjaan
        $jumlahPerbelanjaan = $request->rumah + $request->kereta + $request->motorsikal +
                            $request->komputer + $request->tabung_haji + $request->asb + 
                            $request->simpanan + $request->zakat + $request->lain2_bercagar + 
                            $request->pinjaman_peribadi + $request->kad_kredit + $request->lain2_tidak_bercagar;

        // Kira lebihan pendapatan
        $lebihanPendapatan = $jumlahPendapatan - $jumlahPerbelanjaan;

        // Kira peratusan liabiliti tidak bercagar
        $percentLiabilitiTidakBercagar = ($jumlahPendapatan > 0) ? 
            ($request->pinjaman_peribadi + $request->kad_kredit + $request->lain2_tidak_bercagar) / $jumlahPendapatan * 100 : 0;

        $skai07->update(array_merge($validatedData, [
            'jumlah_pendapatan' => $jumlahPendapatan,
            'jumlah_perbelanjaan' => $jumlahPerbelanjaan,
            'lebihan_pendapatan' => $lebihanPendapatan,
            'percent_liabiliti_tidak_bercagar' => $percentLiabilitiTidakBercagar
        ]));

        return redirect()->route('borang.index')->with('success', 'Maklumat berjaya dikemaskini!');
    }

    public function getPegawaiDetails($id)
    {
        $pegawai = PinjamanPerumahan::find($id);  // Assuming PinjamanPerumahan model has the required data

        if ($pegawai) {
            return response()->json([
                'no_kad_pengenalan' => $pegawai->no_ic,
                'jawatan' => $pegawai->jawatan,
                'gred' => $pegawai->gred,
            ]);
        } else {
            return response()->json(['error' => 'Pegawai not found'], 404);
        }
    }

    public function destroy($id)
    {
        $skai07 = SKAI07::findOrFail($id); // Cari record berdasarkan ID
        $skai07->delete(); // Padam record

        return redirect()->route('borang.index')->with('success', 'Maklumat berjaya dipadam!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenyataGaji;
use App\Models\PinjamanPerumahan;
use App\Models\SKAI07;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Determine the state (negeri) to filter data
        $negeri = $request->negeri ?? auth()->user()->negeri;

        // Fetch data based on user role and selected state
        if (auth()->user()->role === 'superadmin' && $request->has('negeri')) {
            $data_penyata = PenyataGaji::where('negeri', $negeri)->count();
            $data_pinjaman = PinjamanPerumahan::where('negeri', $negeri)->count();
            $data_skai07 = SKAI07::where('negeri', $negeri)->count();
        } else {
            $data_penyata = PenyataGaji::where('negeri', auth()->user()->negeri)->count();
            $data_pinjaman = PinjamanPerumahan::where('negeri', auth()->user()->negeri)->count();
            $data_skai07 = SKAI07::where('negeri', auth()->user()->negeri)->count();
        }

        // Pass data to the view
        return view('dashboard', compact('data_penyata', 'data_pinjaman', 'data_skai07', 'negeri'));
    }

    public function getPenyataGajiStatsByNegeri(Request $request)
    {
        $negeriFilter = $request->input('negeri', null);
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', null);

        $hutangKeys = [
            'pinjaman_peribadi_bsn', 'pinjaman_perumahan', 'bayaran_balik_itp', 'bayaran_balik_bsh', 'ptptn',
            'kutipan_semula_emolumen', 'arahan_potongan_nafkah', 'komputer', 'pcb',
            'lain_lain_potongan_pembentungan', 'koperasi', 'berkat', 'angkasa'
        ];

        $bukanHutangKeys = [
            'potongan_lembaga_th', 'amanah_saham_nasional', 'zakat_yayasan_wakaf', 'insuran', 'kwsp', 'i_destinasi', 'angkasa_bukan_pinjaman'
        ];

        $selects = [
            'users.negeri',
            \DB::raw('MONTH(penyata_gaji.created_at) as bulan'),
        ];

        // Count distinct user yang ada > 0 untuk setiap jenis hutang
        foreach ($hutangKeys as $key) {
            $selects[] = \DB::raw("COUNT(DISTINCT CASE WHEN penyata_gaji.{$key} > 0 THEN penyata_gaji.user_id ELSE NULL END) as {$key}");
        }

        // Count distinct user yang ada > 0 untuk setiap jenis bukan hutang
        foreach ($bukanHutangKeys as $key) {
            $selects[] = \DB::raw("COUNT(DISTINCT CASE WHEN penyata_gaji.{$key} > 0 THEN penyata_gaji.user_id ELSE NULL END) as {$key}");
        }

        // Count distinct user yang ada sekurang-kurangnya 1 jenis hutang
        $hutangCases = [];
        foreach ($hutangKeys as $key) {
            $hutangCases[] = "CASE WHEN penyata_gaji.{$key} > 0 THEN 1 ELSE 0 END";
        }
        $hutangSumRaw = implode(' + ', $hutangCases);
        $selects[] = \DB::raw("COUNT(DISTINCT CASE WHEN ({$hutangSumRaw}) > 0 THEN penyata_gaji.user_id ELSE NULL END) as jumlah_hutang");

        // Count distinct user yang ada sekurang-kurangnya 1 jenis bukan hutang
        $bukanHutangCases = [];
        foreach ($bukanHutangKeys as $key) {
            $bukanHutangCases[] = "CASE WHEN penyata_gaji.{$key} > 0 THEN 1 ELSE 0 END";
        }
        $bukanHutangSumRaw = implode(' + ', $bukanHutangCases);
        $selects[] = \DB::raw("COUNT(DISTINCT CASE WHEN ({$bukanHutangSumRaw}) > 0 THEN penyata_gaji.user_id ELSE NULL END) as jumlah_bukan_hutang");

        $query = \DB::table('penyata_gaji')
            ->join('users', 'penyata_gaji.user_id', '=', 'users.id')
            ->select($selects)
            ->whereYear('penyata_gaji.created_at', $year);

        if ($month) {
            $query->whereMonth('penyata_gaji.created_at', $month);
        }

        if ($negeriFilter) {
            $query->where('users.negeri', $negeriFilter);
        }

        $query->groupBy('users.negeri', 'bulan')
            ->orderBy('users.negeri')
            ->orderBy('bulan');

        $data = $query->get();

        return response()->json($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PenyataGaji;
use App\Models\Skai07;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 


class GrafController extends Controller
{
    public function statistikKeterhutangan(Request $request)
{
    // Get filter parameters
    $negeri = $request->negeri ?? null;
    $tahun = $request->year ?? date('Y');
    $bulan = $request->month ?? date('n'); // Default to current month

    // If month is explicitly set to empty (Semua), then set $bulan to null
    // if ($request->has('month') && $request->month === '') {
    //     $bulan = null;
    // }

    // Define all possible months for the dropdown
    $all_bulan = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mac', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Ogos', 9 => 'Sept', 10 => 'Okt', 11 => 'Nov', 12 => 'Dis'
    ];

    // Base queries for Penyata Gaji and SKAI07
    $penyataGajiQuery = PenyataGaji::select(
            'users.negeri', 
            \DB::raw('MONTH(penyata_gaji.created_at) as bulan'),
            \DB::raw('COUNT(penyata_gaji.user_id) as jumlah_keseluruhan')
        )
        ->join('users', 'penyata_gaji.user_id', '=', 'users.id')
        ->whereYear('penyata_gaji.created_at', $tahun);

    $skai07Query = Skai07::select(
            'users.negeri', 
            \DB::raw('MONTH(skai07.created_at) as bulan'),
            \DB::raw('COUNT(skai07.user_id) as jumlah_keseluruhan')
        )
        ->join('users', 'skai07.user_id', '=', 'users.id')
        ->whereYear('skai07.created_at', $tahun);

    // Apply filters
    if (!empty($bulan) && $bulan!="all") {
        $penyataGajiQuery->whereMonth('penyata_gaji.created_at', $bulan);
        $skai07Query->whereMonth('skai07.created_at', $bulan);
    }

    if ($negeri) {
        $penyataGajiQuery->where('users.negeri', $negeri);
        $skai07Query->where('users.negeri', $negeri);
    }

    // Get the data
    $penyataGajiData = $penyataGajiQuery
        ->groupBy('users.negeri', \DB::raw('MONTH(penyata_gaji.created_at)'))
        ->get()
        ->groupBy('negeri')
        ->map(function ($item) {
            return $item->pluck('jumlah_keseluruhan', 'bulan')->toArray();
        })
        ->toArray();

    $skai07Data = $skai07Query
        ->groupBy('users.negeri', \DB::raw('MONTH(skai07.created_at)'))
        ->get()
        ->groupBy('negeri')
        ->map(function ($item) {
            return $item->pluck('jumlah_keseluruhan', 'bulan')->toArray();
        })
        ->toArray();

    // Define all possible states
    $allNegeri = [
        'IBU PEJABAT', 'JOHOR', 'KEDAH', 'KELANTAN', 'MELAKA', 
        'NEGERI SEMBILAN', 'PAHANG', 'PULAU PINANG', 'PERAK', 
        'PERLIS', 'SELANGOR', 'TERENGGANU', 'SARAWAK',
        'WILAYAH PERSEKUTUAN KUALA LUMPUR', 'WILAYAH PERSEKUTUAN LABUAN', 
        'WILAYAH PERSEKUTUAN PUTRAJAYA', 'FRAM WILAYAH UTARA', 
        'FRAM WILAYAH TIMUR', 'FRAM SABAH', 'FRAM SARAWAK'
    ];

    // Define all possible years
    $all_tahun = range(date('Y'), date('Y') - 5); 

    // Prepare the data
    $negeri_data = [];
    foreach ($allNegeri as $state) {
        $penyata = $penyataGajiData[$state] ?? [];
        $skai = $skai07Data[$state] ?? [];
        
        $total_penyata = array_sum($penyata);
        $total_skai = array_sum($skai);
        
        if ($total_penyata > 0 || $total_skai > 0) {
            $negeri_data[$state] = [
                'penyata_gaji' => $total_penyata,
                'skai07' => $total_skai
            ];
        }
    }

    // Build chart title
    $chart_title = 'Perbandingan Jumlah Penyata Gaji dan SKAI07';
    if ($bulan && $bulan != "all") {
        $chart_title .= ' Bulan ' . $all_bulan[$bulan];
    }
    if ($tahun) {
        $chart_title .= ' Tahun ' . $tahun;
    }

     if ($bulan) {
        $penyataGajiQuery->whereMonth('penyata_gaji.created_at', $bulan);
        $skai07Query->whereMonth('skai07.created_at', $bulan);
    }

    // Calculate totals
    $total_penyata_gaji = array_sum(array_column($negeri_data, 'penyata_gaji'));
    $total_skai07 = array_sum(array_column($negeri_data, 'skai07'));

    return view('graf.statistik_keterhutangan', [
        'negeri_data' => $negeri_data,
        'total_penyata_gaji' => $total_penyata_gaji,
        'total_skai07' => $total_skai07,
        'all_tahun' => $all_tahun,
        'all_bulan' => $all_bulan,
        'all_negeri' => $allNegeri,
        'chart_title' => $chart_title,
        'current_month' => date('n') 
    ]);
}

    public function kumpulanPerkhidmatan(Request $request)
{
    // Get filter parameters
    $negeri = $request->negeri ?? null;
    $tahun = $request->year ?? date('Y');  // Default to current year
    $bulan = $request->month ?? date('m'); // Default to current month

    // Define all possible months for the dropdown
    $all_bulan = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mac', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Ogos', 9 => 'Sept', 10 => 'Okt', 11 => 'Nov', 12 => 'Dis'
    ];

    // Base query for Penyata Gaji
    $penyataGajiQuery = DB::table('penyata_gaji')
        ->join('users', 'penyata_gaji.user_id', '=', 'users.id')
        ->whereYear('penyata_gaji.created_at', $tahun);

    // Apply month filter if provided and not "-- Semua --"
    if ($bulan && $bulan != "all") {
        $penyataGajiQuery->whereMonth('penyata_gaji.created_at', $bulan);
    }

    // Apply negeri filter if selected
    if ($negeri) {
        $penyataGajiQuery->where('users.negeri', $negeri);
    }

    // Get data for Penyata Gaji grouped by gred and jantina
    $penyataGajiData = $penyataGajiQuery
        ->select(
            'penyata_gaji.gred', 
            'penyata_gaji.jantina',  
            DB::raw('COUNT(penyata_gaji.user_id) as jumlah_penyata_gaji')
        )
        ->groupBy('penyata_gaji.gred', 'penyata_gaji.jantina')
        ->get();

    // Initialize data storage for grouped gred (only numeric part)
    $dataByGred = [];
    $labels = [];

    // Merge Penyata Gaji data
    foreach ($penyataGajiData as $data) {
        // Extract numeric part of Gred (e.g., 9A becomes 9)
        $gred = preg_replace('/\D/', '', $data->gred);  // Remove non-numeric characters
        $jantina = $data->jantina;

        // Initialize array if not already set
        if (!isset($dataByGred[$gred])) {
            $dataByGred[$gred] = [
                'lelaki' => 0,
                'perempuan' => 0,
                'penyata_gaji' => 0
            ];
        }

        // Count Penyata Gaji by gender
        if ($jantina == 'Lelaki') {
            $dataByGred[$gred]['lelaki'] += $data->jumlah_penyata_gaji;
        } else {
            $dataByGred[$gred]['perempuan'] += $data->jumlah_penyata_gaji;
        }
        $dataByGred[$gred]['penyata_gaji'] += $data->jumlah_penyata_gaji;

        // Add unique gred to labels
        if (!in_array($gred, $labels)) {
            $labels[] = $gred; // Save only the numeric part of gred
        }
    }

    // Prepare data for view
    $data = [
        'dataByGred' => $dataByGred,
        'labels' => $labels,  // Passing labels for X-axis (gred numbers)
        'selected_tahun' => $tahun,
        'selected_bulan' => $bulan,
        'selected_negeri' => $negeri,
        'all_tahun' => range(date('Y'), date('Y') - 5),
        'all_bulan' => $all_bulan, // Correctly assigned with key
        'all_negeri' => [
            'IBU PEJABAT', 'JOHOR', 'KEDAH', 'KELANTAN', 'MELAKA', 'NEGERI SEMBILAN', 'PAHANG',
            'PULAU PINANG', 'PERAK', 'PERLIS', 'SELANGOR', 'TERENGGANU', 'SARAWAK',
            'WILAYAH PERSEKUTUAN KUALA LUMPUR', 'WILAYAH PERSEKUTUAN LABUAN', 'WILAYAH PERSEKUTUAN PUTRAJAYA',
            'FRAM WILAYAH UTARA', 'FRAM WILAYAH TIMUR', 'FRAM SABAH', 'FRAM SARAWAK'
        ],
        'current_month' => date('n')
    ];

    return view('graf.kumpulan_perkhidmatan', $data);
}

    public function senaraiSkai07()
    {
        // View untuk senarai SKAI07
        return view('graf.senarai_skai07');
    }
}

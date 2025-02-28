<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SKAI07;

class SKAI07Controller extends Controller
{
    public function store(Request $request)
{
    $data = $request->all();

    // Kira jumlah pendapatan
    $jumlahPendapatan = $request->gaji + $request->elaun + $request->sewa_rumah + 
                        $request->sewa_kenderaan + $request->sumbangan_suami_isteri + 
                        $request->lain_lain_pendapatan;

    // Kira jumlah perbelanjaan (liabiliti bercagar + tidak bercagar)
    $jumlahPerbelanjaan = $request->rumah + $request->kereta + $request->motorsikal +
                          $request->komputer + $request->tabung_haji + $request->asb + 
                          $request->simpanan + $request->zakat + $request->lain2_bercagar + 
                          $request->pinjaman_peribadi + $request->kad_kredit + $request->lain2_tidak_bercagar;

    // Kira lebihan pendapatan (jumlah perbelanjaan - jumlah pendapatan)
    $lebihanPendapatan = $jumlahPendapatan - $jumlahPerbelanjaan;

    // Kira peratusan liabiliti tidak bercagar
    $percentLiabilitiTidakBercagar = ($jumlahPendapatan > 0) ? ($request->pinjaman_peribadi + $request->kad_kredit + $request->lain2_tidak_bercagar) / $jumlahPendapatan * 100 : 0;

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
        'jumlah_perbelanjaan' => $jumlahPerbelanjaan, // 
        'lebihan_pendapatan' => $lebihanPendapatan, // 
        'percent_liabiliti_tidak_bercagar' => $percentLiabilitiTidakBercagar
    ]);

    return redirect()->route('dashboard')->with('success', 'Maklumat berjaya disimpan!');
}
}
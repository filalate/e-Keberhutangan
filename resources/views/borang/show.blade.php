@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title">Butiran SKAI07</h2>

    <!-- Maklumat Pegawai -->
    <h4 class="section-title">Maklumat Pegawai</h4>
    <div class="info-box">
        <p><strong>Nama Pegawai:</strong> {{ $skai07->nama }}</p>
        <p><strong>No Kad Pengenalan:</strong> {{ $skai07->no_kad_pengenalan }}</p>
        <p><strong>No Badan:</strong> {{ $skai07->no_badan }}</p>
        <p><strong>Gred:</strong> {{ $skai07->gred}}</p>
        <p><strong>Jawatan:</strong> {{ $skai07->jawatan }}</p>
    </div>

    <hr>

    <!-- Pendapatan -->
    <h4 class="section-title">Pendapatan</h4>
    <div class="info-box">
        <p><strong>Gaji:</strong> RM{{ number_format($skai07->gaji, 2) }}</p>
        <p><strong>Elaun:</strong> RM{{ number_format($skai07->elaun, 2) }}</p>
        <p><strong>Sewa Rumah:</strong> RM{{ number_format($skai07->sewa_rumah, 2) }}</p>
        <p><strong>Sewa Kenderan:</strong> RM{{ number_format($skai07->sewa_kenderaan, 2) }}</p>
        <p><strong>Sumbangan Suami/Isteri:</strong> RM{{ number_format($skai07->sumbangan_suami_isteri, 2) }}</p>
        <p><strong>Lain-lain Pendapatan:</strong> RM{{ number_format($skai07->lain_lain_pendapatan, 2) }}</p>
    </div>

    <hr>

    <!-- Liabiliti Bercagar -->
    <h4 class="section-title">Liabiliti Bercagar</h4>
    <div class="info-box">
        <p><strong>Rumah:</strong> RM{{ number_format($skai07->rumah, 2) }}</p>
        <p><strong>Kereta:</strong> RM{{ number_format($skai07->kereta, 2) }}</p>
        <p><strong>Motorsikal:</strong> RM{{ number_format($skai07->motorsikal, 2) }}</p>
        <p><strong>Komputer:</strong> RM{{ number_format($skai07->komputer, 2) }}</p>
        <p><strong>Tabung Haji:</strong> RM{{ number_format($skai07->tabung_haji, 2) }}</p>
        <p><strong>ASB:</strong> RM{{ number_format($skai07->asb, 2) }}</p>
        <p><strong>Simpanan:</strong> RM{{ number_format($skai07->simpanan, 2) }}</p>
        <p><strong>Zakat</strong> RM{{ number_format($skai07->zakat, 2) }}</p>
        <p><strong>Lain-lain:</strong> RM{{ number_format($skai07->lain2_bercagar, 2) }}</p>
    </div>

    <hr>

    <!-- Liabiliti Tidak Bercagar -->
    <h4 class="section-title">Liabiliti Tidak Bercagar</h4>
    <div class="info-box">
        <p><strong>Pinjaman Peribadi:</strong> RM{{ number_format($skai07->pinjaman_peribadi, 2) }}</p>
        <p><strong>Kad Kredit:</strong> RM{{ number_format($skai07->kad_kredit, 2) }}</p>
        <p><strong>Lain-lain:</strong> RM{{ number_format($skai07->lain2_tidak_bercagar, 2) }}</p>
    </div>

    <hr>

    <!-- Ringkasan Kewangan -->
    <h4 class="section-title">Ringkasan Kewangan</h4>
    <div class="info-box">
        <p><strong>Jumlah Pendapatan:</strong> RM{{ number_format($skai07->jumlah_pendapatan, 2) }}</p>
        <p><strong>Jumlah Perbelanjaan:</strong> RM{{ number_format($skai07->jumlah_perbelanjaan, 2) }}</p>
        <p><strong>Lebihan Pendapatan:</strong> RM{{ number_format($skai07->lebihan_pendapatan, 2) }}</p>
        <p><strong>% Liabiliti Tidak Bercagar:</strong> {{ number_format($skai07->percent_liabiliti_tidak_bercagar, 2) }}%</p>
    </div>

    <div class="button-container">
        <a href="{{ route('borang.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title">Butiran Pinjaman Perumahan</h2>

    <!-- Maklumat Pegawai -->
    <h4 class="section-title">Maklumat Pegawai</h4>
    <div class="info-box">
        <p><strong>Nama Pegawai:</strong> {{ $pinjaman->nama_pegawai }}</p>
        <p><strong>No Kad Pengenalan:</strong> {{ $pinjaman->no_ic }}</p>
        <p><strong>Jawatan:</strong> {{ $pinjaman->jawatan }}</p>
        <p><strong>Gred:</strong> {{ $pinjaman->gred }}</p>
        <p><strong>Tempat Bertugas:</strong> {{ $pinjaman->tempat_bertugas }}</p>
    </div>

    <hr>

    <!-- Pendapatan & Potongan -->
    <h4 class="section-title">Maklumat Kewangan</h4>
    <div class="info-box">
        <p><strong>Jumlah Pendapatan:</strong> RM{{ number_format($pinjaman->jumlah_pendapatan, 2) }}</p>
        <p><strong>Jumlah Potongan:</strong> RM{{ number_format($pinjaman->jumlah_potongan, 2) }}</p>
        <p><strong>Jumlah Pinjaman Perumahan:</strong> RM{{ number_format($pinjaman->jumlah_pinjaman_perumahan, 2) }}</p>
    </div>

    <hr>

    <!-- Agregat Keterhutangan -->
    <h4 class="section-title">Analisis Keterhutangan</h4>
    <div class="info-box">
        <p><strong>Agregat Keterhutangan:</strong> {{ round($pinjaman->agregat_keterhutangan) }}%</p>
        <p><strong>Agregat Bersih Selepas Tolak Pinjaman Perumahan:</strong> {{ round($pinjaman->agregat_bersih) }}%</p>
    </div>

    <div class="button-container">
        <a href="{{ route('pinjaman-perumahan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>

</div>
@endsection

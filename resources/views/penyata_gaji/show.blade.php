@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title">
        <i class="fas fa-file-invoice-dollar"></i> Butiran Penyata Gaji
    </h2>

    <!-- Maklumat Pegawai -->
    <h4 class="section-title">
        <i class="fas fa-user-tie"></i> Maklumat Pegawai
    </h4>
    <div class="info-box">
        <p><strong>Nama Pegawai:</strong> {{ $penyataGaji->nama_pegawai }}</p>
        <p><strong>Jantina:</strong> {{ $penyataGaji->jantina }}</p>
        <p><strong>Gred:</strong> {{ $penyataGaji->gred }}</p>
    </div>

    <hr>

    <!-- Hutang -->
    <h4 class="section-title">
        <i class="fas fa-money-bill-wave"></i> Hutang
    </h4>
    <div class="info-box">
        <p><strong>Pinjaman Peribadi + BSN:</strong> RM{{ number_format($penyataGaji->pinjaman_peribadi_bsn, 2) }}</p>
        <p><strong>Pinjaman Perumahan:</strong> RM{{ number_format($penyataGaji->pinjaman_perumahan, 2) }}</p>
        <p><strong>Bayaran Balik ITP:</strong> RM{{ number_format($penyataGaji->bayaran_balik_itp, 2) }}</p>
        <p><strong>Bayaran Balik BSH:</strong> RM{{ number_format($penyataGaji->bayaran_balik_bsh, 2) }}</p>
        <p><strong>PTPTN:</strong> RM{{ number_format($penyataGaji->ptptn, 2) }}</p>
        <p><strong>Kutipan Semula Emolumen:</strong> RM{{ number_format($penyataGaji->kutipan_semula_emolumen, 2) }}</p>
        <p><strong>Arahan Potongan Nafkah:</strong> RM{{ number_format($penyataGaji->arahan_potongan_nafkah, 2) }}</p>
        <p><strong>Komputer:</strong> RM{{ number_format($penyataGaji->komputer, 2) }}</p>
        <p><strong>PCB:</strong> RM{{ number_format($penyataGaji->pcb, 2) }}</p>
        <p><strong>Lain-lain Potongan:</strong> RM{{ number_format($penyataGaji->lain_lain_potongan_pembentungan, 2) }}</p>
        <p><strong>Koperasi:</strong> RM{{ number_format($penyataGaji->koperasi, 2) }}</p>
        <p><strong>Berkat:</strong> RM{{ number_format($penyataGaji->berkat, 2) }}</p>
        <p><strong>Angkasa (Hutang):</strong> RM{{ number_format($penyataGaji->angkasa, 2) }}</p>
    </div>

    <hr>

    <!-- Bukan Hutang -->
    <h4 class="section-title">
        <i class="fas fa-hand-holding-usd"></i> Bukan Hutang
    </h4>
    <div class="info-box">
        <p><strong>Potongan Lembaga TH:</strong> RM{{ number_format($penyataGaji->potongan_lembaga_th, 2) }}</p>
        <p><strong>Amanah Saham Nasional:</strong> RM{{ number_format($penyataGaji->amanah_saham_nasional, 2) }}</p>
        <p><strong>Zakat / Yapiem / Yayasan Wakaf:</strong> RM{{ number_format($penyataGaji->zakat_yayasan_wakaf, 2) }}</p>
        <p><strong>Insuran:</strong> RM{{ number_format($penyataGaji->insuran, 2) }}</p>
        <p><strong>KWSP:</strong> RM{{ number_format($penyataGaji->kwsp, 2) }}</p>
        <p><strong>I Destinasi:</strong> RM{{ number_format($penyataGaji->i_destinasi, 2) }}</p>
        <p><strong>Angkasa (Bukan Pinjaman):</strong> RM{{ number_format($penyataGaji->angkasa_bukan_pinjaman, 2) }}</p>
    </div>

    <!-- Ringkasan Kewangan -->
    <h4 class="section-title">
        <i class="fas fa-calculator"></i> Ringkasan Kewangan
    </h4>
    <div class="info-box">
        <p><strong>Jumlah Hutang:</strong> RM{{ number_format($penyataGaji->jumlah_hutang, 2) }}</p>
        <p><strong>Jumlah Bukan Hutang:</strong> RM{{ number_format($penyataGaji->jumlah_bukan_hutang, 2) }}</p>
        <p><strong>Jumlah Keseluruhan:</strong> RM{{ number_format($penyataGaji->jumlah_keseluruhan, 2) }}</p>
    </div>

    <div class="button-container">
        <a href="{{ route('penyata-gaji.index') }}" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
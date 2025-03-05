@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Centered Title -->
    <h2 class="form-title">Borang Penyata Gaji</h2>

    <form action="{{ route('penyata-gaji.store') }}" method="POST">
        @csrf

        <h4 class="section-title">Maklumat Pegawai</h4>
        <div class="form-grid">
            <label for="nama_pegawai">Nama Pegawai:</label>
            <input type="text" id="nama_pegawai" name="nama_pegawai" class="form-control" required>
        </div>

        <hr>

        <!-- Hutang (Liabilities) -->
        <h4 class="section-title">Hutang</h4>
        <div class="form-grid">
            <label for="pinjaman_peribadi_bsn">Pinjaman Peribadi + BSN:</label>
            <input type="number" id="pinjaman_peribadi_bsn" name="pinjaman_peribadi_bsn" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="pinjaman_perumahan">Pinjaman Perumahan:</label>
            <input type="number" id="pinjaman_perumahan" name="pinjaman_perumahan" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="bayaran_itp">Bayaran Balik ITP:</label>
            <input type="number" id="bayaran_itp" name="bayaran_itp" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="bayaran_bsh">Bayaran Balik BSH:</label>
            <input type="number" id="bayaran_bsh" name="bayaran_bsh" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="ptptn">PTPTN:</label>
            <input type="number" id="ptptn" name="ptptn" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="kutipan_semula_emolumen">Kutipan Semula Emolumen:</label>
            <input type="number" id="kutipan_semula_emolumen" name="kutipan_semula_emolumen" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="arahan_potongan_nafkah">Arahan Potongan Nafkah:</label>
            <input type="number" id="arahan_potongan_nafkah" name="arahan_potongan_nafkah" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="komputer">Komputer:</label>
            <input type="number" id="komputer" name="komputer" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="pcb">PCB:</label>
            <input type="number" id="pcb" name="pcb" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="lain_lain_potongan">Lain-lain Potongan (Pembentungan):</label>
            <input type="number" id="lain_lain_potongan" name="lain_lain_potongan" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="koperasi">Koperasi:</label>
            <input type="number" id="koperasi" name="koperasi" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="berkat">Berkat:</label>
            <input type="number" id="berkat" name="berkat" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="angkasa_hutang">Angkasa (Hutang):</label>
            <input type="number" id="angkasa_hutang" name="angkasa_hutang" class="form-control" step="0.001">
        </div>

        <hr>

        <!-- Bukan Hutang (Non-Liabilities) -->
        <h4 class="section-title">Bukan Hutang</h4>
        <div class="form-grid">
            <label for="potongan_lembaga_th">Potongan Lembaga TH:</label>
            <input type="number" id="potongan_lembaga_th" name="potongan_lembaga_th" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="amanah_saham_nasional">Amanah Saham Nasional:</label>
            <input type="number" id="amanah_saham_nasional" name="amanah_saham_nasional" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="zakat_yapiem">Zakat / Yapiem / Yayasan Wakaf:</label>
            <input type="number" id="zakat_yapiem" name="zakat_yapiem" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="insuran">Insuran:</label>
            <input type="number" id="insuran" name="insuran" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="kwsp">KWSP:</label>
            <input type="number" id="kwsp" name="kwsp" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="i_destinasi">I Destinasi:</label>
            <input type="number" id="i_destinasi" name="i_destinasi" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="angkasa_bukan_pinjaman">Angkasa (Bukan Pinjaman):</label>
            <input type="number" id="angkasa_bukan_pinjaman" name="angkasa_bukan_pinjaman" class="form-control" step="0.001">
        </div>
        
        <div class="button-container">
            <a href="{{ route('penyata-gaji.index') }}" class="btn btn-secondary btn-base mt-3">Kembali</a>
            <button type="submit" class="btn btn-success btn-base mt-3">Simpan</button>
        </div>

    </form>
</div>
@endsection

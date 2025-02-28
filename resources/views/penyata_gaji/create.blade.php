@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Centered Title -->
    <h2 class="form-title">Borang Penyata Gaji</h2>

    <form action="{{ route('penyata-gaji.store') }}" method="POST">
        @csrf

        <h4 class="section-title">Maklumat Pegawai</h4>
        <div class="form-group">
            <label for="nama_pegawai">Nama Pegawai:</label>
            <input type="text" name="nama_pegawai" class="form-control" required>
        </div>

        <hr>

        <!-- Hutang (Liabilities) -->
        <h4 class="section-title">Hutang</h4>
        <div class="form-group">
            <label for="pinjaman_peribadi_bsn">Pinjaman Peribadi + BSN:</label>
            <input type="number" name="pinjaman_peribadi_bsn" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="pinjaman_perumahan">Pinjaman Perumahan:</label>
            <input type="number" name="pinjaman_perumahan" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="bayaran_itp">Bayaran Balik ITP:</label>
            <input type="number" name="bayaran_itp" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="bayaran_bsh">Bayaran Balik BSH:</label>
            <input type="number" name="bayaran_bsh" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="ptptn">PTPTN:</label>
            <input type="number" name="ptptn" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="kutipan_semula_emolumen">Kutipan Semula Emolumen:</label>
            <input type="number" name="kutipan_semula_emolumen" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="arahan_potongan_nafkah">Arahan Potongan Nafkah:</label>
            <input type="number" name="arahan_potongan_nafkah" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="komputer">Komputer:</label>
            <input type="number" name="komputer" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="pcb">PCB:</label>
            <input type="number" name="pcb" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="lain_lain_potongan">Lain-lain Potongan (Pembentungan):</label>
            <input type="number" name="lain_lain_potongan" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="koperasi">Koperasi:</label>
            <input type="number" name="koperasi" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="berkat">Berkat:</label>
            <input type="number" name="berkat" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="angkasa_hutang">Angkasa (Hutang):</label>
            <input type="number" name="angkasa_hutang" class="form-control" step="0.001">
        </div>

        <hr>

        <!-- Bukan Hutang (Non-Liabilities) -->
        <h4 class="section-title">Bukan Hutang</h4>
        <div class="form-group">
            <label for="potongan_lembaga_th">Potongan Lembaga TH:</label>
            <input type="number" name="potongan_lembaga_th" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="amanah_saham_nasional">Amanah Saham Nasional:</label>
            <input type="number" name="amanah_saham_nasional" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="zakat_yapiem">Zakat / Yapiem / Yayasan Wakaf:</label>
            <input type="number" name="zakat_yapiem" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="insuran">Insuran:</label>
            <input type="number" name="insuran" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="kwsp">KWSP:</label>
            <input type="number" name="kwsp" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="i_destinasi">I Destinasi:</label>
            <input type="number" name="i_destinasi" class="form-control" step="0.001">
        </div>
        <div class="form-group">
            <label for="angkasa_bukan_pinjaman">Angkasa (Bukan Pinjaman):</label>
            <input type="number" name="angkasa_bukan_pinjaman" class="form-control" step="0.001">
        </div>
        
        <div class="button-container">
            <button type="submit" class="btn btn-success mt-3">Simpan</button>
        </div>

    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title">Edit SKAI07</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('borang.update', $skai07->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Maklumat Pegawai Section -->
        <h4 class="section-title">Maklumat Pegawai</h4>
        <div class="form-grid">
            <label>Nama Pegawai:</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $skai07->nama) }}" required>
        </div>

        <div class="form-grid">
            <label>No Kad Pengenalan:</label>
            <input type="text" name="no_kad_pengenalan" class="form-control" value="{{ old('no_kad_pengenalan', $skai07->no_kad_pengenalan) }}" required>
        </div>

        <div class="form-grid">
            <label>No Badan:</label>
            <input type="text" name="no_badan" class="form-control" value="{{ old('no_badan', $skai07->no_badan) }}" required>
        </div>

        <div class="form-grid">
            <label>Gred:</label>
            <input type="text" name="gred" class="form-control" value="{{ old('gred', $skai07->gred) }}" required>
        </div>

        <div class="form-grid">
            <label>Jawatan:</label>
            <input type="text" name="jawatan" class="form-control" value="{{ old('jawatan', $skai07->jawatan) }}" required>
        </div>

       <!-- Pendapatan Section -->
        <h4 class="section-title">Pendapatan</h4>
        @php
            $pendapatan_fields = [
                'gaji' => 'Gaji',
                'elaun' => 'Elaun',
                'sewa_rumah' => 'Sewa Rumah',
                'sewa_kenderaan' => 'Sewa Kenderaan',
                'sumbangan_suami_isteri' => 'Sumbangan Suami/Isteri',
                'lain_lain_pendapatan' => 'Lain-lain Pendapatan'
            ];
        @endphp
        @foreach($pendapatan_fields as $name => $label)
            <div class="form-grid">
                <label>{{ $label }}:</label>
                <input type="number" name="{{ $name }}" class="form-control" value="{{ $skai07->$name }}" step="0.01">
            </div>
        @endforeach

        <!-- Liabiliti Bercagar Section -->
        <h4 class="section-title">Liabiliti Bercagar</h4>
        @php
            $liabiliti_bercagar_fields = [
                'rumah' => 'Rumah',
                'kereta' => 'Kereta',
                'motorsikal' => 'Motorsikal',
                'komputer' => 'Komputer',
                'tabung_haji' => 'Tabung Haji',
                'asb' => 'ASB',
                'simpanan' => 'Simpanan',
                'zakat' => 'Zakat',
                'lain2_bercagar' => 'Lain-lain'
            ];
        @endphp
        @foreach($liabiliti_bercagar_fields as $name => $label)
            <div class="form-grid">
                <label>{{ $label }}:</label>
                <input type="number" name="{{ $name }}" class="form-control" value="{{ $skai07->$name }}" step="0.01">
            </div>
        @endforeach

        <!-- Liabiliti Tidak Bercagar Section -->
        <h4 class="section-title">Liabiliti Tidak Bercagar</h4>
        @php
            $liabiliti_tidak_bercagar_fields = [
                'pinjaman_peribadi' => 'Pinjaman Peribadi',
                'kad_kredit' => 'Kad Kredit',
                'lain2_tidak_bercagar' => 'Lain-lain'
            ];
        @endphp
        @foreach($liabiliti_tidak_bercagar_fields as $name => $label)
            <div class="form-grid">
                <label>{{ $label }}:</label>
                <input type="number" name="{{ $name }}" class="form-control" value="{{ $skai07->$name }}" step="0.01">
            </div>
        @endforeach


        <!-- Ringkasan Kewangan Section -->
        <h4 class="section-title">Ringkasan Kewangan</h4>
        <div class="form-grid">
            <label>Jumlah Pendapatan:</label>
            <input type="number" id="jumlah_pendapatan" readonly value="{{ old('jumlah_pendapatan', $skai07->gaji + $skai07->elaun + $skai07->sewa_rumah + $skai07->sewa_kenderaan + $skai07->sumbangan_suami_isteri + $skai07->lain_lain_pendapatan) }}">

            <label>Jumlah Perbelanjaan:</label>
            <input type="number" id="jumlah_perbelanjaan" readonly value="{{ old('jumlah_perbelanjaan', $skai07->pinjaman_peribadi + $skai07->kad_kredit + $skai07->lain2_tidak_bercagar) }}">

            <label>Lebihan Pendapatan:</label>
            <input type="number" id="lebihan_pendapatan" readonly value="{{ old('lebihan_pendapatan', ($skai07->gaji + $skai07->elaun + $skai07->sewa_rumah + $skai07->sewa_kenderaan + $skai07->sumbangan_suami_isteri + $skai07->lain_lain_pendapatan) - ($skai07->pinjaman_peribadi + $skai07->kad_kredit + $skai07->lain2_tidak_bercagar)) }}">

            <label>% Liabiliti Tidak Bercagar:</label>
            <input type="text" id="percent_liabiliti_tidak_bercagar" readonly value="{{ old('percent_liabiliti_tidak_bercagar', 
            number_format(
                ($skai07->gaji + $skai07->elaun + $skai07->sewa_rumah + $skai07->sewa_kenderaan + $skai07->sumbangan_suami_isteri + $skai07->lain_lain_pendapatan) != 0 ? 
                (($skai07->pinjaman_peribadi + $skai07->kad_kredit + $skai07->lain2_tidak_bercagar) / ($skai07->gaji + $skai07->elaun + $skai07->sewa_rumah + $skai07->sewa_kenderaan + $skai07->sumbangan_suami_isteri + $skai07->lain_lain_pendapatan)) * 100 : 0, 2)) }}%">

        </div>

        <!-- Form Buttons -->
        <div class="button-container">
            <a href="{{ route('borang.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            <button type="submit" class="btn btn-warning mt-3">Kemaskini</button>
        </div>
    </form>
</div>
@endsection

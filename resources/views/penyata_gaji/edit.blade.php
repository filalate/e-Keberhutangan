@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title">Edit Penyata Gaji</h2>

    <form action="{{ route('penyata-gaji.update', $penyata->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h4 class="section-title">Maklumat Pegawai</h4>
        <div class="form-group">
            <label>Nama Pegawai:</label>
            <input type="text" name="nama_pegawai" class="form-control" value="{{ $penyata->nama_pegawai }}" required>
        </div>

        <h4 class="section-title">Hutang</h4>
        <div class="row">
            @php
                $hutang_fields = [
                    'pinjaman_peribadi_bsn' => 'Pinjaman Peribadi + BSN',
                    'pinjaman_perumahan' => 'Pinjaman Perumahan',
                    'bayaran_balik_itp' => 'Bayaran Balik ITP',
                    'bayaran_balik_bsh' => 'Bayaran Balik BSH',
                    'ptptn' => 'PTPTN',
                    'kutipan_semula_emolumen' => 'Kutipan Semula Emolumen 7',
                    'arahan_potongan_nafkah' => 'Arahan Potongan Nafkah',
                    'komputer' => 'Komputer',
                    'pcb' => 'PCB',
                    'lain_lain_potongan_pembentungan' => 'Lain-lain Potongan (Pembentungan)',
                    'koperasi' => 'Koperasi',
                    'berkat' => 'Berkat',
                    'angkasa' => 'Angkasa'
                ];
            @endphp
            @foreach($hutang_fields as $name => $label)
                <div class="col-md-4">
                    <label>{{ $label }}:</label>
                    <input type="number" name="{{ $name }}" class="form-control" value="{{ $penyata->$name }}" step="0.001">
                </div>
            @endforeach
        </div>

        <h4 class="section-title">Bukan Hutang</h4>
        <div class="row">
            @php
                $bukan_hutang_fields = [
                    'potongan_lembaga_th' => 'Potongan Lembaga TH',
                    'amanah_saham_nasional' => 'Amanah Saham Nasional',
                    'zakat_yayasan_wakaf' => 'Zakat / Yapiem / Yayasan Wakaf',
                    'insuran' => 'Insuran',
                    'kwsp' => 'KWSP',
                    'i_destinasi' => 'I Destinasi',
                    'angkasa_bukan_pinjaman' => 'Angkasa (Bukan Pinjaman)'
                ];
            @endphp
            @foreach($bukan_hutang_fields as $name => $label)
                <div class="col-md-4">
                    <label>{{ $label }}:</label>
                    <input type="number" name="{{ $name }}" class="form-control" value="{{ $penyata->$name }}" step="0.001">
                </div>
            @endforeach
        </div>

        <hr>
            <div class="button-container">
                <button type="submit" class="btn btn-warning btn-base mt-3">Kemaskini</button>
                <a href="{{ route('penyata-gaji.index') }}" class="btn btn-secondary btn-base mt-3">Kembali</a>
            </div>
    </form>

    <script>
        function updateCalculations() {
            let totalHutang = 0;
            document.querySelectorAll('.hutang').forEach(input => {
                totalHutang += parseFloat(input.value) || 0;
            });
            document.getElementById('jumlah_hutang').value = totalHutang;

            let totalBukanHutang = 0;
            document.querySelectorAll('.bukan-hutang').forEach(input => {
                totalBukanHutang += parseFloat(input.value) || 0;
            });
            document.getElementById('jumlah_bukan_hutang').value = totalBukanHutang;
        }

        document.querySelectorAll('.hutang, .bukan-hutang').forEach(input => {
            input.addEventListener('input', updateCalculations);
        });

        updateCalculations();
    </script>
</div>
@endsection

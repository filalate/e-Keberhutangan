@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title">
        <i class="fas fa-file-invoice-dollar"></i> Edit Penyata Gaji
    </h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('penyata-gaji.update', $penyata->id) }}" method="POST" id="penyataGajiForm">
        @csrf
        @method('PUT')

        <h4 class="section-title">
            <i class="fas fa-user-tie"></i> Maklumat Pegawai
        </h4>
        <div class="form-grid">
            <label for="nama_pegawai">Nama Pegawai:</label>
            <input type="text" id="nama_pegawai" name="nama_pegawai" class="form-control" value="{{ $penyata->nama_pegawai }}" required>
        </div>

        <hr>

        <!-- Jantina (Gender) -->
        <div class="form-grid">
            <label for="jantina">Jantina:</label>
            <select id="jantina" name="jantina" class="form-control" required>
                <option value="">Pilih Jantina</option>
                <option value="Lelaki" {{ $penyata->jantina == 'Lelaki' ? 'selected' : '' }}>Lelaki</option>
                <option value="Perempuan" {{ $penyata->jantina == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <hr>

        <!-- Gred: Split into letter and number -->
        <div class="form-grid">
            <label for="gred" style="flex: 1;">Gred:</label>
            <div style="display: flex; gap: 10px;">
                <!-- Huruf Gred -->
                <div style="flex: 1;">
                    <input type="text" id="gred_huruf" name="gred_huruf" class="form-control" 
                           value="{{ substr($penyata->gred, 0, 1) }}" placeholder="KB, N, S" required>
                </div>
                <!-- Nombor Gred -->
                <div style="flex: 1;">
                    <select id="gred_nombor" name="gred_nombor" class="form-control" required>
                        <option value="">Pilih Nombor Gred</option>
                        @for($i = 1; $i <= 15; $i++)
                            <option value="{{ $i }}" {{ substr($penyata->gred, 1) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        <hr>

        <!-- Hutang (Liabilities) -->
        <h4 class="section-title">
            <i class="fas fa-money-bill-wave"></i> Hutang
        </h4>
        @php
            $hutang_fields = [
                'pinjaman_peribadi_bsn' => 'Pinjaman Peribadi + BSN',
                'pinjaman_perumahan' => 'Pinjaman Perumahan',
                'bayaran_balik_itp' => 'Bayaran Balik ITP',
                'bayaran_balik_bsh' => 'Bayaran Balik BSH',
                'ptptn' => 'PTPTN',
                'kutipan_semula_emolumen' => 'Kutipan Semula Emolumen',
                'arahan_potongan_nafkah' => 'Arahan Potongan Nafkah',
                'komputer' => 'Komputer',
                'pcb' => 'PCB',
                'lain_lain_potongan_pembentungan' => 'Lain-lain Potongan (Pembentungan)',
                'koperasi' => 'Koperasi',
                'berkat' => 'Berkat',
                'angkasa' => 'Angkasa (Hutang)'
            ];
        @endphp
        @foreach($hutang_fields as $name => $label)
            <div class="form-grid">
                <label>{{ $label }}:</label>
                <input type="number" name="{{ $name }}" class="form-control hutang" value="{{ $penyata->$name }}" step="0.001">
            </div>
        @endforeach

        <hr>

        <!-- Bukan Hutang (Non-Liabilities) -->
        <h4 class="section-title">
            <i class="fas fa-hand-holding-usd"></i> Bukan Hutang
        </h4>
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
            <div class="form-grid">
                <label>{{ $label }}:</label>
                <input type="number" name="{{ $name }}" class="form-control bukan-hutang" value="{{ $penyata->$name }}" step="0.001">
            </div>
        @endforeach
        
        <!-- Financial Summary -->
        <h4 class="section-title">
            <i class="fas fa-calculator"></i> Ringkasan Kewangan
        </h4>
        <div class="form-grid">
            <label>Jumlah Hutang:</label>
            <input type="number" id="jumlah_hutang" name="jumlah_hutang" class="form-control" value="{{ $penyata->jumlah_hutang }}" readonly>
        </div>
        <div class="form-grid">
            <label>Jumlah Bukan Hutang:</label>
            <input type="number" id="jumlah_bukan_hutang" name="jumlah_bukan_hutang" class="form-control" value="{{ $penyata->jumlah_bukan_hutang }}" readonly>
        </div>
        <div class="form-grid">
            <label>Jumlah Keseluruhan:</label>
            <input type="number" id="jumlah_keseluruhan" name="jumlah_keseluruhan" class="form-control" value="{{ $penyata->jumlah_keseluruhan }}" readonly>
        </div>

        <div class="button-container">
            <a href="{{ route('penyata-gaji.index') }}" class="btn btn-secondary btn-base mt-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-warning btn-base mt-3">
                <i class="fas fa-save"></i> Kemaskini
            </button>
        </div>
    </form>

    <script>
        function updateCalculations() {
            // Calculate total hutang (liabilities)
            let totalHutang = 0;
            document.querySelectorAll('.hutang').forEach(input => {
                totalHutang += parseFloat(input.value) || 0;
            });
            document.getElementById('jumlah_hutang').value = totalHutang.toFixed(2);

            // Calculate total bukan hutang (non-liabilities)
            let totalBukanHutang = 0;
            document.querySelectorAll('.bukan-hutang').forEach(input => {
                totalBukanHutang += parseFloat(input.value) || 0;
            });
            document.getElementById('jumlah_bukan_hutang').value = totalBukanHutang.toFixed(2);

            // Calculate total keseluruhan (total)
            let totalKeseluruhan = totalHutang + totalBukanHutang;
            document.getElementById('jumlah_keseluruhan').value = totalKeseluruhan.toFixed(2);
        }

        // Attach event listeners to all input fields
        document.querySelectorAll('.hutang, .bukan-hutang').forEach(input => {
            input.addEventListener('input', updateCalculations);
        });

        // Initialize calculations on page load
        updateCalculations();
    </script>
</div>
@endsection
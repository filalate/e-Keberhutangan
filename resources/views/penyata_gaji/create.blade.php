@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Centered Title -->
    <h2 class="form-title">
        <i class="fas fa-file-invoice-dollar"></i> Borang Penyata Gaji
    </h2>

    <form action="{{ route('penyata-gaji.store') }}" method="POST" id="penyataGajiForm">
        @csrf

        <h4 class="section-title">
            <i class="fas fa-user-tie"></i> Maklumat Pegawai
        </h4>
        <div class="form-grid">
            <label for="nama_pegawai">Nama Pegawai:</label>
            <input type="text" id="nama_pegawai" name="nama_pegawai" class="form-control" required>
        </div>

        <hr>

        <!-- Jantina (Gender) -->
        <div class="form-grid">
            <label for="jantina">Jantina:</label>
            <select id="jantina" name="jantina" class="form-control" required>
                <option value="">Pilih Jantina</option>
                <option value="Lelaki">Lelaki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>

        <hr>

        <!-- Gred: Huruf dan Nombor Gred Sebelah-Sebelah -->
        <div class="form-grid">
            <label for="gred" style="flex: 1;">Gred:</label>
            <div style="display: flex; gap: 10px;">
                <!-- Huruf Gred -->
                <div style="flex: 1;">
                    <input type="text" id="gred_huruf" name="gred_huruf" class="form-control" placeholder="KB, N, S" required>
                </div>

                <!-- Nombor Gred -->
                <div style="flex: 1;">
                    <select id="gred_nombor" name="gred_nombor" class="form-control" required>
                        <option value="">Pilih Nombor Gred</option>
                        @for($i = 1; $i <= 15; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
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
        <div class="form-grid">
            <label for="pinjaman_peribadi_bsn">Pinjaman Peribadi + BSN:</label>
            <input type="number" id="pinjaman_peribadi_bsn" name="pinjaman_peribadi_bsn" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="pinjaman_perumahan">Pinjaman Perumahan:</label>
            <input type="number" id="pinjaman_perumahan" name="pinjaman_perumahan" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="bayaran_balik_itp">Bayaran Balik ITP:</label>
            <input type="number" id="bayaran_balik_itp" name="bayaran_balik_itp" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="bayaran_balik_bsh">Bayaran Balik BSH:</label>
            <input type="number" id="bayaran_balik_bsh" name="bayaran_balik_bsh" class="form-control" step="0.001">
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
            <label for="lain_lain_potongan_pembentungan">Lain-lain Potongan (Pembentungan):</label>
            <input type="number" id="lain_lain_potongan_pembentungan" name="lain_lain_potongan_pembentungan" class="form-control" step="0.001">
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
            <label for="angkasa">Angkasa (Hutang):</label>
            <input type="number" id="angkasa" name="angkasa" class="form-control" step="0.001">
        </div>

        <hr>

        <!-- Bukan Hutang (Non-Liabilities) -->
        <h4 class="section-title">
            <i class="fas fa-hand-holding-usd"></i> Bukan Hutang
        </h4>
        <div class="form-grid">
            <label for="potongan_lembaga_th">Potongan Lembaga TH:</label>
            <input type="number" id="potongan_lembaga_th" name="potongan_lembaga_th" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="amanah_saham_nasional">Amanah Saham Nasional:</label>
            <input type="number" id="amanah_saham_nasional" name="amanah_saham_nasional" class="form-control" step="0.001">
        </div>
        <div class="form-grid">
            <label for="zakat_yayasan_wakaf">Zakat / Yapiem / Yayasan Wakaf:</label>
            <input type="number" id="zakat_yayasan_wakaf" name="zakat_yayasan_wakaf" class="form-control" step="0.001">
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
        
        <!-- Financial Summary -->
        <h4 class="section-title">
            <i class="fas fa-calculator"></i> Ringkasan Kewangan
        </h4>
        <div class="form-grid">
            <label>Jumlah Hutang:</label>
            <input type="number" id="jumlah_hutang" name="jumlah_hutang" class="form-control" readonly>
        </div>
        <div class="form-grid">
            <label>Jumlah Bukan Hutang:</label>
            <input type="number" id="jumlah_bukan_hutang" name="jumlah_bukan_hutang" class="form-control" readonly>
        </div>
        <div class="form-grid">
            <label>Jumlah Keseluruhan:</label>
            <input type="number" id="jumlah_keseluruhan" name="jumlah_keseluruhan" class="form-control" readonly>
        </div>

        <div class="button-container">
            <a href="{{ route('penyata-gaji.index') }}" class="btn btn-secondary btn-base mt-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-hantar btn-base mt-3">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </form>

    <script>
        function calculateTotal() {
            // Calculate total hutang (liabilities)
            let hutangFields = [
                'pinjaman_peribadi_bsn', 'pinjaman_perumahan', 'bayaran_balik_itp',
                'bayaran_balik_bsh', 'ptptn', 'kutipan_semula_emolumen', 'arahan_potongan_nafkah',
                'komputer', 'pcb', 'lain_lain_potongan', 'koperasi', 'berkat', 'angkasa'
            ];

            let totalHutang = 0;
            hutangFields.forEach(function(id) {
                let el = document.getElementById(id);
                if (el) {
                    totalHutang += parseFloat(el.value) || 0;
                }
            });
            document.getElementById('jumlah_hutang').value = totalHutang.toFixed(2);

            // Calculate total bukan hutang (non-liabilities)
            let bukanHutangFields = [
                'potongan_lembaga_th', 'amanah_saham_nasional', 'zakat_yayasan_wakaf', 'insuran', 
                'kwsp', 'i_destinasi', 'angkasa_bukan_pinjaman'
            ];

            let totalBukanHutang = 0;
            bukanHutangFields.forEach(function(id) {
                let el = document.getElementById(id);
                if (el) {
                    totalBukanHutang += parseFloat(el.value) || 0;
                } 
            });
            document.getElementById('jumlah_bukan_hutang').value = totalBukanHutang.toFixed(2);

            // Calculate total keseluruhan (total)
            let totalKeseluruhan = totalHutang + totalBukanHutang;
            document.getElementById('jumlah_keseluruhan').value = totalKeseluruhan.toFixed(2);
        }

        // Attach event listeners to all input fields
        document.querySelectorAll('#penyataGajiForm input[type="number"]').forEach(function(input) {
            input.addEventListener('input', calculateTotal);
        });
    </script>
</div>
@endsection
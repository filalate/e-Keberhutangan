@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title">Borang Sistem Kawalan Akauntabiliti Integriti 07 (SKAI 07)</h2>

    <form action="{{ route('borang.store') }}" method="POST">
        @csrf

        <!-- Info Pegawai -->
        <h4 class="section-title">Maklumat Pegawai</h4>
        <div class="form-grid">
            <label for="nama">Nama Pegawai:</label>
            <input type="text" id="nama" name="nama">

            <label for="no_kad_pengenalan">No Kad Pengenalan:</label>
            <input type="text" id="no_kad_pengenalan" name="no_kad_pengenalan" required>

            <label for="no_badan">No Badan:</label>
            <input type="text" id="no_badan" name="no_badan" required>

            <label for="gred">Gred:</label>
            <input type="text" id="gred" name="gred" required>

            <label for="jawatan">Jawatan:</label>
            <input type="text" id="jawatan" name="jawatan" required>
        </div>

        <hr>

        <!-- Pendapatan -->
        <h4 class="section-title">Pendapatan</h4>
        <div class="form-grid">
            <label for="gaji">Gaji:</label>
            <input type="number" id="gaji" name="gaji" class="income" required step="0.001">

            <label for="elaun">Elaun:</label>
            <input type="number" id="elaun" name="elaun" class="income" step="0.001">

            <label for="sewa_rumah">Sewa Rumah:</label>
            <input type="number" id="sewa_rumah" name="sewa_rumah" class="income" step="0.001">

            <label for="sewa_kenderaan">Sewa Kenderaan:</label>
            <input type="number" id="sewa_kenderaan" name="sewa_kenderaan" class="income" step="0.001">

            <label for="sumbangan_suami_isteri">Sumbangan Suami/Isteri:</label>
            <input type="number" id="sumbangan_suami_isteri" name="sumbangan_suami_isteri" class="income" step="0.001">

            <label for="lain_lain_pendapatan">Lain-lain Pendapatan:</label>
            <input type="number" id="lain_lain_pendapatan" name="lain_lain_pendapatan" class="income" step="0.001">
        </div>

        <hr>

        <!-- Liabiliti Bercagar -->
        <h4 class="section-title">Liabiliti Bercagar</h4>
        <div class="form-grid">
            <label for="rumah">Rumah:</label>
            <input type="number" id="rumah" name="rumah" class="expense" step="0.001">

            <label for="kereta">Kereta:</label>
            <input type="number" id="kereta" name="kereta" class="expense" step="0.001">

            <label for="motorsikal">Motorsikal:</label>
            <input type="number" id="motorsikal" name="motorsikal" class="expense" step="0.001">

            <label for="komputer">Komputer:</label>
            <input type="number" id="komputer" name="komputer" class="expense" step="0.001">

            <label for="tabung_haji">Tabung Haji:</label>
            <input type="number" id="tabung_haji" name="tabung_haji" class="expense" step="0.001">

            <label for="asb">ASB:</label>
            <input type="number" id="asb" name="asb" class="expense" step="0.001">

            <label for="simpanan">Simpanan:</label>
            <input type="number" id="simpanan" name="simpanan" class="expense" step="0.001">

            <label for="zakat">Zakat:</label>
            <input type="number" id="zakat" name="zakat" class="expense" step="0.001">

            <label for="lain2_bercagar">Lain-lain:</label>
            <input type="number" id="lain2_bercagar" name="lain2_bercagar" class="expense" step="0.001">
        </div>

        <hr>

        <!-- Liabiliti Tidak Bercagar -->
        <h4 class="section-title">Liabiliti Tidak Bercagar</h4>
        <div class="form-grid">
            <label for="pinjaman_peribadi">Pinjaman Peribadi:</label>
            <input type="number" id="pinjaman_peribadi" name="pinjaman_peribadi" class="unsecured-expense" step="0.001">

            <label for="kad_kredit">Kad Kredit:</label>
            <input type="number" id="kad_kredit" name="kad_kredit" class="unsecured-expense" step="0.001">

            <label for="lain2_tidak_bercagar">Lain-lain:</label>
            <input type="number" id="lain2_tidak_bercagar" name="lain2_tidak_bercagar" class="unsecured-expense" step="0.001">
        </div>

        <hr>

        <!-- Ringkasan Kewangan -->
        <h4 class="section-title">Ringkasan Kewangan</h4>
        <div class="form-grid">
            <label for="jumlah_pendapatan">Jumlah Pendapatan:</label>
            <input type="number" id="jumlah_pendapatan" readonly>

            <label for="jumlah_perbelanjaan">Jumlah Perbelanjaan:</label>
            <input type="number" id="jumlah_perbelanjaan" readonly>

            <label for="lebihan_pendapatan">Lebihan Pendapatan:</label>
            <input type="number" id="lebihan_pendapatan" readonly>

            <label for="percent_liabiliti_tidak_bercagar">% Liabiliti Tidak Bercagar:</label>
            <input type="text" id="percent_liabiliti_tidak_bercagar" readonly>
        </div>

        <div class="button-container">
            <a href="{{ route('borang.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            <button type="submit" class="btn btn-hantar">Hantar</button>
        </div>

    </form>
</div>

<script>
    // Function to calculate totals
function calculateTotals() {
    // Get all the income fields and calculate total income
    let gaji = parseFloat(document.querySelector('[name="gaji"]').value) || 0;
    let elaun = parseFloat(document.querySelector('[name="elaun"]').value) || 0;
    let sewa_rumah = parseFloat(document.querySelector('[name="sewa_rumah"]').value) || 0;
    let sewa_kenderaan = parseFloat(document.querySelector('[name="sewa_kenderaan"]').value) || 0;
    let sumbangan_suami_isteri = parseFloat(document.querySelector('[name="sumbangan_suami_isteri"]').value) || 0;
    let lain_lain_pendapatan = parseFloat(document.querySelector('[name="lain_lain_pendapatan"]').value) || 0;

    // Calculate the total income
    let totalIncome = gaji + elaun + sewa_rumah + sewa_kenderaan + sumbangan_suami_isteri + lain_lain_pendapatan;
    document.getElementById('jumlah_pendapatan').value = totalIncome.toFixed(2); // 2 titik perpuluhan

    // Get all the secured liabilities fields and calculate total secured liabilities
    let rumah = parseFloat(document.querySelector('[name="rumah"]').value) || 0;
    let kereta = parseFloat(document.querySelector('[name="kereta"]').value) || 0;
    let motorsikal = parseFloat(document.querySelector('[name="motorsikal"]').value) || 0;
    let komputer = parseFloat(document.querySelector('[name="komputer"]').value) || 0;
    let tabung_haji = parseFloat(document.querySelector('[name="tabung_haji"]').value) || 0;
    let asb = parseFloat(document.querySelector('[name="asb"]').value) || 0;
    let simpanan = parseFloat(document.querySelector('[name="simpanan"]').value) || 0;
    let zakat = parseFloat(document.querySelector('[name="zakat"]').value) || 0;
    let lain2_bercagar = parseFloat(document.querySelector('[name="lain2_bercagar"]').value) || 0;

    // Calculate total secured liabilities
    let totalSecuredLiabilities = rumah + kereta + motorsikal + komputer + tabung_haji + asb + simpanan + zakat + lain2_bercagar;

    // Get all the unsecured liabilities fields and calculate total unsecured liabilities
    let pinjaman_peribadi = parseFloat(document.querySelector('[name="pinjaman_peribadi"]').value) || 0;
    let kad_kredit = parseFloat(document.querySelector('[name="kad_kredit"]').value) || 0;
    let lain2_tidak_bercagar = parseFloat(document.querySelector('[name="lain2_tidak_bercagar"]').value) || 0;

    // Calculate total unsecured liabilities
    let totalUnsecuredLiabilities = pinjaman_peribadi + kad_kredit + lain2_tidak_bercagar;

    // Calculate total expenses
    let totalExpenses = totalSecuredLiabilities + totalUnsecuredLiabilities;
    document.getElementById('jumlah_perbelanjaan').value = totalExpenses.toFixed(2); // 2 titik perpuluhan

    // Calculate surplus income
    let surplusIncome = totalIncome - totalExpenses;
    document.getElementById('lebihan_pendapatan').value = surplusIncome.toFixed(2); // 2 titik perpuluhan

    // Calculate percentage of unsecured liabilities
    let percentageUnsecuredLiabilities = (totalUnsecuredLiabilities / totalIncome) * 100;

    // Check if the result is a valid number (not NaN)
    if (isNaN(percentageUnsecuredLiabilities) || totalIncome === 0) {
        document.getElementById('percent_liabiliti_tidak_bercagar').value = '';
    } else {
        document.getElementById('percent_liabiliti_tidak_bercagar').value = percentageUnsecuredLiabilities.toFixed(2) + '%'; // 2 titik perpuluhan
    }
}

// Attach event listeners to all inputs that affect the calculations
document.querySelectorAll('input').forEach(function(input) {
    input.addEventListener('input', calculateTotals);
});

// Call calculateTotals once to set initial values
calculateTotals();

</script>

@endsection

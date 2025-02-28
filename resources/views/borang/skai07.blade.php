@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title">Borang Sistem Kawalan Akauntabiliti Integriti 07 (SKAI 07)</h2>

    <form action="{{ route('skai07.store') }}" method="POST">
        @csrf

        <!-- Info Pegawai -->
        <h4 class="section-title">Maklumat Pegawai</h4>
        <div class="form-grid">
            <label>Nama Pegawai:</label>
            <input type="text" name="nama" value="{{ old('nama', auth()->user()->name) }}">

            <label>No Kad Pengenalan:</label>
            <input type="text" name="no_kad_pengenalan" required>

            <label>No Badan:</label>
            <input type="text" name="no_badan" required>

            <label>Gred:</label>
            <input type="text" name="gred" required>

            <label>Jawatan:</label>
            <input type="text" name="jawatan" required>
        </div>

        <hr>

        <!-- Pendapatan -->
        <h4 class="section-title">Pendapatan</h4>
        <div class="form-grid">
            <label>Gaji:</label>
            <input type="number" name="gaji" class="income" required>

            <label>Elaun:</label>
            <input type="number" name="elaun" class="income">

            <label>Sewa Rumah:</label>
            <input type="number" name="sewa_rumah" class="income">

            <label>Sewa Kenderaan:</label>
            <input type="number" name="sewa_kenderaan" class="income">

            <label>Sumbangan Suami/Isteri:</label>
            <input type="number" name="sumbangan_suami_isteri" class="income">

            <label>Lain-lain Pendapatan:</label>
            <input type="number" name="lain_lain_pendapatan" class="income">
        </div>

        <hr>

        <!-- Liabiliti Bercagar -->
        <h4 class="section-title">Liabiliti Bercagar</h4>
        <div class="form-grid">
            <label>Rumah:</label>
            <input type="number" name="rumah" class="expense">

            <label>Kereta:</label>
            <input type="number" name="kereta" class="expense">

            <label>Motorsikal:</label>
            <input type="number" name="motorsikal" class="expense">

            <label>Komputer:</label>
            <input type="number" name="komputer" class="expense">

            <label>Tabung Haji:</label>
            <input type="number" name="tabung_haji" class="expense">

            <label>ASB:</label>
            <input type="number" name="asb" class="expense">

            <label>Simpanan:</label>
            <input type="number" name="simpanan" class="expense">

            <label>Zakat:</label>
            <input type="number" name="zakat" class="expense">

            <label>Lain-lain:</label>
            <input type="number" name="lain2_bercagar" class="expense">
        </div>

        <hr>

        <!-- Liabiliti Tidak Bercagar -->
        <h4 class="section-title">Liabiliti Tidak Bercagar</h4>
        <div class="form-grid">
            <label>Pinjaman Peribadi:</label>
            <input type="number" name="pinjaman_peribadi" class="unsecured-expense">

            <label>Kad Kredit:</label>
            <input type="number" name="kad_kredit" class="unsecured-expense">

            <label>Lain-lain:</label>
            <input type="number" name="lain2_tidak_bercagar" class="unsecured-expense">
        </div>

        <hr>

        <!-- Ringkasan Kewangan -->
        <h4 class="section-title">Ringkasan Kewangan</h4>
        <div class="form-grid">
            <label>Jumlah Pendapatan:</label>
            <input type="number" id="jumlah_pendapatan" readonly>

            <label>Jumlah Perbelanjaan:</label>
            <input type="number" id="jumlah_perbelanjaan" readonly>

            <label>Lebihan Pendapatan:</label>
            <input type="number" id="lebihan_pendapatan" readonly>

            <label>% Liabiliti Tidak Bercagar:</label>
            <input type="text" id="percent_liabiliti_tidak_bercagar" readonly>
        </div>

        <div class="button-container">
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
        document.getElementById('jumlah_pendapatan').value = totalIncome;

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
        document.getElementById('jumlah_perbelanjaan').value = totalExpenses;

        // Calculate surplus income
        let surplusIncome = totalIncome - totalExpenses;
        document.getElementById('lebihan_pendapatan').value = surplusIncome;

        // Calculate percentage of unsecured liabilities
        let percentageUnsecuredLiabilities = (totalUnsecuredLiabilities / totalIncome) * 100;

        // Check if the result is a valid number (not NaN)
        if (isNaN(percentageUnsecuredLiabilities) || totalIncome === 0) {
            // If it's NaN or the total income is 0, set the input to empty
            document.getElementById('percent_liabiliti_tidak_bercagar').value = '';
        } else {
            // If the calculation is valid, display the percentage with 2 decimal places
            document.getElementById('percent_liabiliti_tidak_bercagar').value = percentageUnsecuredLiabilities.toFixed(2) + '%';
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

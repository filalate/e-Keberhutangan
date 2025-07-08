@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title">Borang Pinjaman Perumahan</h2>

    @if ($errors->any())
        <div class="alert alert-danger" style="color:red">
            <strong>There were some problems with your input:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pinjaman-perumahan.store') }}" method="POST">
        @csrf

        <h4 class="section-title">Maklumat Pegawai</h4>
        <div class="form-grid">
            <label for="nama_pegawai">Nama Pegawai:</label>
            <select id="nama_pegawai" name="nama_pegawai" class="form-control" required onchange="fetchPenyataGaji()">
                <option value="">Pilih Nama Pegawai</option>
                @foreach($namaPegawaiList as $pegawai)
                <option value="{{ $pegawai->nama_pegawai }}" 
                        data-id="{{ $pegawai->id }}"
                        data-gred="{{ $pegawai->gred }}">
                    {{ $pegawai->nama_pegawai }}
                </option>
                @endforeach
            </select>

            <label for="no_ic">No Kad Pengenalan:</label>
            <input type="text" id="no_ic" name="no_ic" required>

            <label for="jawatan">Jawatan:</label>
            <input type="text" id="jawatan" name="jawatan" required>

            <label for="gred">Gred:</label>
            <input type="text" id="gred" name="gred" required readonly>

            <label for="tempat_bertugas">Tempat Bertugas:</label>
            <input type="text" id="tempat_bertugas" name="tempat_bertugas" required>
        </div>

        <hr>

        <!-- Maklumat Pendapatan -->
        <h4 class="section-title">Pendapatan</h4>
        <div class="form-grid">
            <label for="jumlah_pendapatan">Jumlah Pendapatan:</label>
            <input type="number" id="jumlah_pendapatan" name="jumlah_pendapatan" class="form-control" step="0.01" required oninput="calculateAgregat()">
        </div>

        <div class="form-grid">
            <label for="jumlah_potongan">Jumlah Potongan:</label>
            <input type="number" id="jumlah_potongan" name="jumlah_potongan" class="form-control" step="0.01" required readonly>
        </div>

        <div class="form-grid">
            <label for="agregat_keterhutangan">Agregat Keterhutangan:</label>
            <input type="text" id="agregat_keterhutangan" name="agregat_keterhutangan" class="form-control" readonly>
        </div> 

        <div class="form-grid">
            <label for="jumlah_pinjaman_perumahan">Jumlah Pinjaman Perumahan:</label>
            <input type="number" id="jumlah_pinjaman_perumahan" name="jumlah_pinjaman_perumahan" class="form-control" step="0.01" required readonly>
        </div> 

        <div class="form-grid">
            <label for="agregat_bersih">Agregat Bersih Selepas Tolak Pinjaman Perumahan:</label>
            <input type="text" id="agregat_bersih" name="agregat_bersih" class="form-control" readonly>
        </div> 

        <div class="button-container">
            <a href="{{ route('pinjaman-perumahan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            <button type="submit" class="btn btn-hantar">Hantar</button>
        </div>
    </form>
</div>

<script>
    function fetchPenyataGaji() {
    const selectNP = document.getElementById('nama_pegawai');
    const selectedOptionNP = selectNP.options[selectNP.selectedIndex];
    const idPegawai = selectedOptionNP.getAttribute('data-id');
    const gred = selectedOptionNP.getAttribute('data-gred');

    console.log("Selected gred:", gred); // Debugging line
    
    // Set the gred value immediately
    if (gred) {
        document.getElementById('gred').value = gred;
        } else {
            document.getElementById('gred').value = '';
        }

        if (idPegawai) {
            fetch(`/penyata-gaji/api/search?id_pegawai=${idPegawai}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data) {
                        console.log("Data received:", data); // Debugging line
                        document.getElementById('jumlah_potongan').value = parseFloat(data.jumlah_keseluruhan || 0).toFixed(2);
                        document.getElementById('jumlah_pinjaman_perumahan').value = parseFloat(data.pinjaman_perumahan || 0).toFixed(2);
                        calculateAgregat();
                    } else {
                        console.error('No data received');
                        alert('Data Penyata Gaji tidak ditemukan.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    alert('Error fetching data: ' + error.message);
                });
        } else {
            alert('Sila pilih nama pegawai.');
        }
    }

    function calculateAgregat() {
        console.log("calculateAgregat dipanggil"); // Debugging

        let jumlahPendapatan = parseFloat(document.getElementById('jumlah_pendapatan').value) || 0;
        let jumlahPotongan = parseFloat(document.getElementById('jumlah_potongan').value) || 0;
        let jumlahPinjamanPerumahan = parseFloat(document.getElementById('jumlah_pinjaman_perumahan').value) || 0;

        console.log("Jumlah Pendapatan:", jumlahPendapatan); // Debugging
        console.log("Jumlah Potongan:", jumlahPotongan); // Debugging
        console.log("Jumlah Pinjaman Perumahan:", jumlahPinjamanPerumahan); // Debugging

        if (jumlahPendapatan > 0) {
            let agregatKeterhutangan = (jumlahPotongan / jumlahPendapatan) * 100;
            agregatKeterhutangan = Math.round(agregatKeterhutangan);

            let agregatBersih = ((jumlahPotongan - jumlahPinjamanPerumahan) / jumlahPendapatan) * 100;
            agregatBersih = Math.round(agregatBersih);

            document.getElementById('agregat_keterhutangan').value = agregatKeterhutangan + "%";
            document.getElementById('agregat_bersih').value = agregatBersih + "%";
        } else {
            document.getElementById('agregat_keterhutangan').value = "";
            document.getElementById('agregat_bersih').value = "";
        }
    }

    // Event listener untuk input jumlah_pendapatan
    document.getElementById('jumlah_pendapatan').addEventListener('input', calculateAgregat);

    // Event listener untuk perubahan pada dropdown nama_pegawai
    document.getElementById('nama_pegawai').addEventListener('change', fetchPenyataGaji);
</script>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title">Kemaskini Borang Pinjaman Perumahan</h2>
    <form action="{{ route('pinjaman-perumahan.update', $pinjaman->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h4 class="section-title">Maklumat Pegawai</h4>
        <div class="form-grid">
            <label for="nama_pegawai">Nama Pegawai:</label>
            {{--
            <select id="nama_pegawai" name="nama_pegawai" class="form-control" required onchange="fetchPenyataGaji()">
                <option value="">Pilih Nama Pegawai</option>
                @foreach($namaPegawaiList as $nama)
                    <option value="{{ $nama->nama_pegawai }}" {{ $pinjaman->nama_pegawai == $nama->nama_pegawai ? 'selected' : '' }}>
                        {{ $nama->nama_pegawai }}
                    </option>
                @endforeach
            </select>
            --}}
            {{ $pinjaman->nama_pegawai }}

            <label for="no_ic">No Kad Pengenalan:</label>
            <input type="text" id="no_ic" name="no_ic" value="{{ $pinjaman->no_ic }}" required>

            <label for="jawatan">Jawatan:</label>
            <input type="text" id="jawatan" name="jawatan" value="{{ $pinjaman->jawatan }}" required>

            <label for="gred">Gred:</label>
            <input type="text" id="gred" name="gred" value="{{ $pinjaman->gred }}" required>

            <label for="tempat_bertugas">Tempat Bertugas:</label>
            <input type="text" id="tempat_bertugas" name="tempat_bertugas" value="{{ $pinjaman->tempat_bertugas }}" required>
        </div>

        <hr>

        <!-- Pendapatan -->
        <h4 class="section-title">Pendapatan</h4>
        <div class="form-grid">
            <label for="jumlah_pendapatan">Jumlah Pendapatan:</label>
            <input type="number" id="jumlah_pendapatan" name="jumlah_pendapatan" class="form-control" step="0.01" value="{{ $pinjaman->jumlah_pendapatan }}" required oninput="calculateAgregat()">
        </div>

        <div class="form-grid">
            <label for="jumlah_potongan">Jumlah Potongan:</label>
            <input type="number" id="jumlah_potongan" name="jumlah_potongan" class="form-control" step="0.01" value="{{ old('jumlah_potongan', $pinjaman->jumlah_potongan) }}" required readonly>
        </div>

        <div class="form-grid">
            <label for="agregat_keterhutangan">Agregat Keterhutangan:</label>
            <input type="text" id="agregat_keterhutangan" name="agregat_keterhutangan" class="form-control" value="{{ round($pinjaman->agregat_keterhutangan) }}%" required readonly>
        </div> 

        <div class="form-grid">
            <label for="jumlah_pinjaman_perumahan">Jumlah Pinjaman Perumahan:</label>
            <input type="number" id="jumlah_pinjaman_perumahan" name="jumlah_pinjaman_perumahan" class="form-control" step="0.01" value="{{ number_format($pinjaman->jumlah_pinjaman_perumahan, 2) }}" required readonly>
        </div> 

        <div class="form-grid">
            <label for="agregat_bersih">Agregat Bersih Selepas Tolak Pinjaman Perumahan:</label>
            <input type="text" id="agregat_bersih" name="agregat_bersih" class="form-control" value="{{ round($pinjaman->agregat_bersih) }}%" required readonly>
        </div> 

        <div class="button-container">
            <a href="{{ route('pinjaman-perumahan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            <button type="submit" class="btn btn-hantar">Kemaskini</button>
        </div>
    </form>
</div>

<script>
    // Fungsi untuk mengambil data penyata gaji berdasarkan nama pegawai
    function fetchPenyataGaji() {
        const namaPegawai = document.getElementById('nama_pegawai').value;

        if (namaPegawai) {
            fetch(`/penyata-gaji/search?nama_pegawai=${namaPegawai}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Data received:", data); // Debugging
                    if (data) {
                        // Kemas kini nilai potongan dan pinjaman perumahan
                        document.getElementById('jumlah_potongan').value = parseFloat(data.jumlah_keseluruhan || 0).toFixed(2);
                        document.getElementById('jumlah_pinjaman_perumahan').value = parseFloat(data.pinjaman_perumahan || 0).toFixed(2);

                        // Kira agregat berdasarkan nilai yang dikemas kini
                        calculateAgregat();
                    } else {
                        alert('Data Penyata Gaji tidak ditemukan.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }

    // Fungsi untuk mengira agregat keterhutangan dan agregat bersih
    function calculateAgregat() {
        const jumlahPendapatan = parseFloat(document.getElementById('jumlah_pendapatan').value) || 0;
        const jumlahPotongan = parseFloat(document.getElementById('jumlah_potongan').value) || 0;
        const jumlahPinjamanPerumahan = parseFloat(document.getElementById('jumlah_pinjaman_perumahan').value) || 0;

        // Kira agregat keterhutangan
        let agregatKeterhutangan = (jumlahPotongan / jumlahPendapatan) * 100;
        agregatKeterhutangan = Math.round(agregatKeterhutangan);

        // Kira agregat bersih
        let agregatBersih = ((jumlahPotongan - jumlahPinjamanPerumahan) / jumlahPendapatan) * 100;
        agregatBersih = Math.round(agregatBersih);

        // Kemas kini nilai agregat dalam input field
        document.getElementById('agregat_keterhutangan').value = agregatKeterhutangan + "%";
        document.getElementById('agregat_bersih').value = agregatBersih + "%";
    }

    // Event listener untuk memuatkan data penyata gaji apabila borang edit dibuka
    document.addEventListener('DOMContentLoaded', function() {
        // Dapatkan nama pegawai yang dipilih
        const namaPegawai = document.getElementById('nama_pegawai').value;

        // Jika nama pegawai telah dipilih, panggil fetchPenyataGaji()
        if (namaPegawai) {
            fetchPenyataGaji();
        }
    });

    // Event listener untuk menangani perubahan pada dropdown nama_pegawai
    document.getElementById('nama_pegawai').addEventListener('change', fetchPenyataGaji);
</script>
@endsection
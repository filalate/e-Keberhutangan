@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title text-center">Senarai Borang Pinjaman Perumahan</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Search Bar -->
        <div class="search-container">
            <input type="text" id="search" placeholder="Cari Nama Pegawai..." onkeyup="searchFunction()" />
            <i class="fa fa-search" id="search-icon"></i>
        </div>
        <!-- Button Tambah Borang -->
        <a href="{{ route('pinjaman-perumahan.create') }}" class="btn btn-primary">Tambah Borang</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
    <table class="table table-bordered" id="pinjamanTable">
        <thead>
            <tr>
                <th style="width: 50px;">Bil</th>
                <th>Nama Pegawai</th>
                <th>Jumlah Pinjaman Perumahan (RM)</th>
                <th>Agregat Keterhutangan (%)</th>
                <th>Agregat Bersih (%)</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pinjaman_perumahan as $index => $pinjaman)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pinjaman->nama_pegawai }}</td>
                    <td>RM{{ number_format($pinjaman->jumlah_pinjaman_perumahan, 2) }}</td>
                    <td>{{ round($pinjaman->agregat_keterhutangan) }}%</td>
                    <td>{{ round($pinjaman->agregat_bersih) }}%</td>
                    <td class="icon-actions">
                        <!-- Lihat -->
                        <a href="{{ route('pinjaman-perumahan.show', $pinjaman->id) }}" class="icon-hover">
                            <i class="fa fa-eye"></i>
                        </a>
                        <!-- Edit -->
                        <a href="{{ route('pinjaman-perumahan.edit', $pinjaman->id) }}" class="icon-hover">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <!-- Padam -->
                        <form action="{{ route('pinjaman-perumahan.destroy', $pinjaman->id) }}" method="POST" class="delete-form icon-hover">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-hover" onclick="return confirm('Anda pasti mahu padam?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

    {{ $pinjaman_perumahan->links() }}
</div>

<script>
    function searchFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById('search');
        filter = input.value.toUpperCase();
        table = document.getElementById('pinjamanTable');
        tr = table.getElementsByTagName('tr');

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName('td')[1]; // Kolom Nama Pegawai
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Title at the top, centered and bold -->
    <h2 class="form-title">Senarai Penyata Gaji</h2>
    
    <!-- Search bar and Add button next to each other -->
    <div class="d-flex">
        <div class="search-container">
            <input type="text" id="search" placeholder="Cari Nama Pegawai..." onkeyup="searchFunction()" />
            <i class="fa fa-search" id="search-icon"></i> <!-- Search icon -->
        </div>
        <a href="{{ route('penyata-gaji.create') }}" class="btn btn-primary ml-3">Tambah Penyata Gaji</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table" id="penyataTable">
        <thead>
            <tr>
                <th>Nama Pegawai</th>
                <th>Pinjaman Peribadi + BSN</th>
                <th>PTPTN</th>
                <th>Koperasi</th>
                <th>Zakat</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penyata as $p)
                <tr>
                    <td>{{ $p->nama_pegawai }}</td>
                    <td>RM{{ number_format($p->pinjaman_peribadi_bsn, 2) }}</td>
                    <td>RM{{ number_format($p->ptptn, 2) }}</td>
                    <td>RM{{ number_format($p->koperasi, 2) }}</td>
                    <td>RM{{ number_format($p->zakat_yayasan_wakaf, 2) }}</td>

                    <td class="icon-actions">
                        <!-- Butang dengan ikon -->
                        <a href="{{ route('penyata-gaji.show', $p->id) }}" class="icon-hover">
                            <i class="fa fa-eye"></i> <!-- Mata untuk View -->
                        </a>
                        <a href="{{ route('penyata-gaji.edit', $p->id) }}" class="icon-hover">
                            <i class="fa fa-pencil"></i> <!-- Pensel untuk Edit -->
                        </a>
                        <form action="{{ route('penyata-gaji.destroy', $p->id) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-hover" onclick="return confirm('Anda pasti mahu padam?')">
                                <i class="fa fa-trash"></i> <!-- Sampah untuk Padam -->
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $penyata->links() }}
</div>

<script>
    function searchFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById('search');
        filter = input.value.toUpperCase();
        table = document.getElementById('penyataTable');
        tr = table.getElementsByTagName('tr');

        // Loop through all table rows
        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName('td')[0]; // First column (Nama Pegawai)
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

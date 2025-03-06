@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title text-center">Senarai SKAI 07</h2>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-container">
            <input type="text" id="search" placeholder="Cari Nama Pegawai..." onkeyup="searchFunction()" />
            <i class="fa fa-search" id="search-icon"></i>
        </div>
        <a href="{{ route('borang.create') }}" class="btn btn-primary">Tambah SKAI 07</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered" id="skai07Table">
        <thead>
            <tr>
                <th style="width: 50px;">Bil</th>
                <th>Nama Pegawai</th>
                <th>No KP</th>
                <th>Gred</th>
                <th>Jawatan</th>
                <th>Gaji (RM)</th>
                <th style="width: 150px;">Liabiliti Tidak Bercagar (%)</th>
                <th style="width: 140px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($skai07 as $index => $s)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->no_kad_pengenalan }}</td>
                    <td>{{ $s->gred }}</td>
                    <td>{{ $s->jawatan }}</td>
                    <td>RM{{ number_format($s->gaji, 2) }}</td>
                    <td>{{ number_format($s->percent_liabiliti_tidak_bercagar, 2) }}%</td>
                    <td class="icon-actions">
                        <a href="{{ route('borang.show', $s->id) }}" class="icon-hover">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="{{ route('borang.edit', $s->id) }}" class="icon-hover">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <form action="{{ route('borang.destroy', $s->id) }}" method="POST" class="delete-form icon-hover">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-hover" onclick="return confirm('Anda pasti mahu padam?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Pagination Links -->
    <div class="pagination">
        {{ $skai07->links() }}
    </div>
</div>

<script>
    function searchFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById('search');
        filter = input.value.toUpperCase();
        table = document.getElementById('skai07Table');
        tr = table.getElementsByTagName('tr');

        // Loop through all rows (skipping the header row)
        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = "none";  // Initially hide the row
            td = tr[i].getElementsByTagName('td');

            // Loop through each cell in the row (for all columns)
            for (var j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";  // Show row if match is found
                        break;  // No need to check other cells once a match is found
                    }
                }
            }
        }
    }
</script>

@endsection

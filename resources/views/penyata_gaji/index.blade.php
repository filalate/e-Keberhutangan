@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title text-center">Senarai Penyata Gaji</h2>

    <!-- Display Toastr notification for success -->
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Success", {
                timeOut: 3000, // Show for 3 seconds
                progressBar: true // Enable progress bar
            });
        </script>
    @endif
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-container">
            <input type="text" id="search" placeholder="Cari Nama Pegawai..." onkeyup="searchFunction()" />
            <i class="fa fa-search" id="search-icon"></i>
        </div>
        <a href="{{ route('penyata-gaji.create') }}" class="btn btn-primary">Tambah Penyata Gaji</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered" id="penyataTable">
            <thead>
                <tr>
                    <th style="width: 50px;">Bil</th>
                    <th>Nama Pegawai</th>
                    <th>Jumlah Hutang (RM)</th>
                    <th>Jumlah Bukan Hutang (RM)</th>
                    <th>Jumlah Keseluruhan (RM)</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penyata_gaji as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $p->nama_pegawai }}</td>
                        <td>RM{{ number_format($p->jumlah_hutang, 2) }}</td>
                        <td>RM{{ number_format($p->jumlah_bukan_hutang, 2) }}</td>
                        <td>RM{{ number_format($p->jumlah_keseluruhan, 2) }}</td>
                        
                        <td class="icon-actions">
                            <a href="{{ route('penyata-gaji.show', $p->id) }}" class="icon-hover">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{ route('penyata-gaji.edit', $p->id) }}" class="icon-hover">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <form action="{{ route('penyata-gaji.destroy', $p->id) }}" method="POST" class="delete-form icon-hover" id="deleteForm{{ $p->id }}">
                                @csrf 
                                @method('DELETE')
                                <button type="button" class="icon-hover" onclick="confirmDelete({{ $p->id }})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $penyata_gaji->links() }}
</div>

<script>
    function searchFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById('search');
        filter = input.value.toUpperCase();
        table = document.getElementById('penyataTable');
        tr = table.getElementsByTagName('tr');

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName('td')[1];
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

    function confirmDelete(id) {
        // SweetAlert2 confirmation
        Swal.fire({
            title: 'Adakah anda pasti?',
            text: "Anda tidak akan dapat mengembalikan rekod ini selepas dipadam.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Padam',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }
</script>

@endsection

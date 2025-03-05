@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title text-center">Senarai Penyata Gaji</h2>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-container">
            <input type="text" id="search" placeholder="Cari Nama Pegawai..." onkeyup="searchFunction()" />
            <i class="fa fa-search" id="search-icon"></i>
        </div>
        <a href="{{ route('penyata-gaji.create') }}" class="btn btn-primary">Tambah Penyata Gaji</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered" id="penyataTable">
        <thead>
            <tr>
                <th style="width: 50px;">Bil</th>
                <th>Nama Pegawai</th>
                <th>Jumlah Hutang (RM)</th>
                <th>Jumlah Bukan Hutang (RM)</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penyata as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->nama_pegawai }}</td>

                    {{-- Kira jumlah hutang --}}
                    <td>
                        RM{{ number_format(
                            $p->pinjaman_peribadi_bsn +
                            $p->pinjaman_perumahan +
                            $p->bayaran_balik_itp +
                            $p->bayaran_balik_bsh +
                            $p->ptptn +
                            $p->kutipan_semula_emolumen +
                            $p->arahan_potongan_nafkah +
                            $p->komputer +
                            $p->pcb +
                            $p->lain_lain_potongan_pembentungan +
                            $p->koperasi +
                            $p->berkat +
                            $p->angkasa, 2) }}
                    </td>

                    {{-- Kira jumlah bukan hutang --}}
                    <td>
                        RM{{ number_format(
                            $p->potongan_lembaga_th +
                            $p->amanah_saham_nasional +
                            $p->zakat_yayasan_wakaf +
                            $p->insuran +
                            $p->kwsp +
                            $p->i_destinasi +
                            $p->angkasa_bukan_pinjaman, 2) }}
                    </td>

                    <td class="icon-actions">
                        <a href="{{ route('penyata-gaji.show', $p->id) }}" class="icon-hover">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="{{ route('penyata-gaji.edit', $p->id) }}" class="icon-hover">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <form action="{{ route('penyata-gaji.destroy', $p->id) }}" method="POST" class="delete-form icon-hover">
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
    {{ $penyata->links() }}
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
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title text-center">Senarai SKAI 07</h2>
    
    <!-- Display Toastr notification for success -->
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Success", {
                timeOut: 3000, // Show for 3 seconds
                progressBar: true // Enable progress bar
            });
        </script>
    @endif

    <!-- Form untuk filter tahun dan bulan -->
    <form method="GET" action="{{ route('borang.index') }}">
        <div class="d-flex mb-3">
            <!-- Dropdown Tahun -->
            <select name="year" class="form-control" onchange="this.form.submit()">
                <option value="" {{ is_null(request('year')) ? 'selected' : '' }}>-- Pilih Tahun --</option>
                @foreach(range(2023, date('Y')) as $yearOption)
                    <option value="{{ $yearOption }}" {{ request('year') == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                @endforeach
            </select>

            <!-- Dropdown Bulan -->
            <select name="month" class="form-control ml-2" onchange="this.form.submit()">
                <option value="" {{ is_null(request('month')) ? 'selected' : '' }}>-- Pilih Bulan --</option>
                @foreach(range(1, 12) as $monthOption)
                    <option value="{{ $monthOption }}" {{ request('month') == $monthOption ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($monthOption)->locale('ms')->isoFormat('MMMM') }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-container">
            <input type="text" id="search" placeholder="Cari Nama Pegawai..." onkeyup="searchFunction()" />
            <i class="fa fa-search" id="search-icon"></i>
        </div>
        <a href="{{ route('borang.create') }}" class="btn btn-primary">Tambah SKAI 07</a>
    </div>

    <div class="table-responsive">
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
</div>

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

    // Resetting the selections for year and month dropdowns on page load if no parameter is set
    window.addEventListener('DOMContentLoaded', function() {
        // Ambil parameter 'year' dan 'month' dari URL
        const year = new URLSearchParams(window.location.search).get('year');
        const month = new URLSearchParams(window.location.search).get('month');

        // Jika tahun atau bulan ada dalam URL, kita kosongkan parameter tersebut
        if (year || month) {
            // Ubah URL tanpa parameter 'year' dan 'month'
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.delete('year');
            currentUrl.searchParams.delete('month');
            
            // Redirect ke URL tanpa parameter
            window.history.replaceState(null, '', currentUrl);
            
            // Reset dropdown
            const yearSelect = document.getElementById("yearSelect");
            const monthSelect = document.getElementById("monthSelect");

            if (yearSelect) {
                yearSelect.selectedIndex = 0; // Reset year dropdown
            }

            if (monthSelect) {
                monthSelect.selectedIndex = 0; // Reset month dropdown
            }
        }
    });
</script>

@endsection

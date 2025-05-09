@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="form-title text-center">Senarai Borang Pinjaman Perumahan</h2>

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
    <form method="GET" action="{{ route('pinjaman-perumahan.index') }}">
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
        <!-- Search Bar -->
        <div class="search-container">
            <input type="text" id="search" placeholder="Cari Nama Pegawai..." onkeyup="searchFunction()" />
            <i class="fa fa-search" id="search-icon"></i>
        </div>
        <!-- Button Tambah Borang -->
        <a href="{{ route('pinjaman-perumahan.create') }}" class="btn btn-primary">Tambah Borang</a>
    </div>

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
                            <form action="{{ route('pinjaman-perumahan.destroy', $pinjaman->id) }}" method="POST" class="delete-form icon-hover" id="deleteForm{{ $pinjaman->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="icon-hover" onclick="confirmDelete({{ $pinjaman->id }})">
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

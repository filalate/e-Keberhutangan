@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard Pegawai</h2>

    <!-- Check if Data Exists -->
    @if($skai07->count() > 0)

        <!-- Chart -->
        <canvas id="skai07Chart"></canvas>

        <!-- Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah Pendapatan (RM)</th>
                    <th>Jumlah Liabiliti (RM)</th>
                    <th>Lebihan Pendapatan (RM)</th>
                    <th>% Liabiliti Tidak Bercagar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($skai07 as $data)
                    <tr>
                        <td>{{ $data->nama }}</td>
                        <td>RM{{ number_format($data->jumlah_pendapatan, 2) }}</td>
                        <td>RM{{ number_format($data->jumlah_liabiliti, 2) }}</td>
                        <td>RM{{ number_format($data->lebihan_pendapatan, 2) }}</td>
                        <td>{{ number_format($data->percent_liabiliti_tidak_bercagar, 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @else
        <p class="text-center text-gray-500 mt-5">Tiada data SKAI 07 yang tersedia.</p>
    @endif
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($skai07->count() > 0)
        var ctx = document.getElementById('skai07Chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($skai07->pluck('nama')), // Nama as labels
                datasets: [{
                    label: 'Jumlah Pendapatan (RM)',
                    data: @json($skai07->pluck('jumlah_pendapatan')),
                    backgroundColor: 'blue'
                }, {
                    label: 'Jumlah Liabiliti (RM)',
                    data: @json($skai07->pluck('jumlah_liabiliti')),
                    backgroundColor: 'red'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    @endif
</script>
@endsection

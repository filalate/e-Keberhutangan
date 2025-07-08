@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4 bg-white rounded-lg shadow border border-gray-300">
    <!-- Title Section -->
    <div class="px-2 sm:px-0 mb-4">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-700">Statistik Mengikut Gred</h2>
    </div>

    <!-- Filter Section -->
    <div class="bg-white p-3 sm:p-6 mb-4 sm:mb-6 rounded-lg shadow border border-gray-300">
        <form action="{{ route('graf.kumpulan-perkhidmatan') }}" method="GET" id="filterForm" class="flex flex-col sm:flex-row sm:space-x-4 space-y-3 sm:space-y-0">
            <div class="w-full sm:w-1/4">
                <label for="yearSelect" class="block text-xs sm:text-sm font-semibold text-gray-600 mb-1">Tahun</label>
                <select id="yearSelect" name="year" class="w-full text-sm sm:text-base border border-gray-300 rounded px-3 py-2">
                    @foreach($all_tahun as $tahun)
                        <option value="{{ $tahun }}" {{ request('year', date('Y')) == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-1/4">
                <label for="monthSelect" class="block text-xs sm:text-sm font-semibold text-gray-600 mb-1">Bulan</label>
                <select id="monthSelect" name="month" class="w-full text-sm sm:text-base border border-gray-300 rounded px-3 py-2">
                    <option value="all" @if(request('month')=='all') 'selected' @endif>-- Semua --</option>    
                    @foreach($all_bulan as $num => $name)
                        <option value="{{ $num }}" 
                            @if(request('month') !== null)
                                {{ request('month') == $num ? 'selected' : '' }}
                            @else
                                {{ $num == $current_month ? 'selected' : '' }}
                            @endif>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-1/4">
                <label for="stateSelect" class="block text-xs sm:text-sm font-semibold text-gray-600 mb-1">Negeri</label>
                <select name="negeri" class="w-full text-sm sm:text-base border border-gray-300 rounded px-3 py-2">
                    <option value="">-- Semua Negeri --</option>
                    @foreach($all_negeri as $state)
                        <option value="{{ $state }}" {{ request('negeri') == $state ? 'selected' : '' }}>{{ $state }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-1/4 flex space-x-2 self-end">
                <button type="submit" class="text-xs sm:text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded transition flex items-center justify-center w-full sm:w-auto flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="ml-1 sm:ml-2">Tapis</span>
                </button>

                <button type="button" id="resetBtn" class="text-xs sm:text-sm bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded transition flex items-center justify-center w-full sm:w-auto flex-1">
                    <i class="fas fa-sync-alt mr-1"></i>
                    <span>Reset</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Statistics Section -->
    <div class="bg-white p-4 mb-6 rounded-lg shadow border border-gray-300">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Statistik</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Total Lelaki -->
            <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                <div class="text-sm text-blue-800 font-medium">Jumlah Lelaki</div>
                <div class="text-2xl font-bold text-blue-600 mt-1">{{ number_format(array_sum(array_column($dataByGred, 'lelaki'))) }}</div>
                <div class="text-xs text-blue-500 mt-1">
                    @php
                        $totalL = array_sum(array_column($dataByGred, 'lelaki'));
                        $totalP = array_sum(array_column($dataByGred, 'perempuan'));
                        $percentage = $totalL + $totalP > 0 ? round(($totalL / ($totalL + $totalP)) * 100, 1) : 0;
                    @endphp
                    {{ $percentage }}% dari jumlah
                </div>
            </div>

            <!-- Total Perempuan -->
            <div class="bg-pink-50 p-3 rounded-lg border border-pink-100">
                <div class="text-sm text-pink-800 font-medium">Jumlah Perempuan</div>
                <div class="text-2xl font-bold text-pink-600 mt-1">{{ number_format(array_sum(array_column($dataByGred, 'perempuan'))) }}</div>
                <div class="text-xs text-pink-500 mt-1">
                    @php
                        $percentage = $totalL + $totalP > 0 ? round(($totalP / ($totalL + $totalP)) * 100, 1) : 0;
                    @endphp
                    {{ $percentage }}% dari jumlah
                </div>
            </div>

            <!-- Total Penyata Gaji -->
            <div class="bg-green-50 p-3 rounded-lg border border-green-100">
                <div class="text-sm text-green-800 font-medium">Jumlah Penyata Gaji</div>
                <div class="text-2xl font-bold text-green-600 mt-1">{{ number_format(array_sum(array_column($dataByGred, 'penyata_gaji'))) }}</div>
                <div class="text-xs text-green-500 mt-1">
                    @php
                        $totalPG = array_sum(array_column($dataByGred, 'penyata_gaji'));
                        $percentage = ($totalL + $totalP) > 0 ? round(($totalPG / ($totalL + $totalP)) * 100, 1) : 0;
                    @endphp
                    {{ $percentage }}% capaian
                </div>
            </div>
        </div>

        <!-- Top 5 Gred -->
        <div class="mt-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">5 Gred Tertinggi</h4>
            <div class="space-y-2">
                @php
                    $sortedGred = collect($dataByGred)->sortByDesc(function($item) {
                        return $item['lelaki'] + $item['perempuan'];
                    })->take(5);
                @endphp

                @foreach($sortedGred as $gred => $data)
                <div class="flex items-center">
                    <div class="w-24 text-sm font-medium text-gray-600">{{ $gred }}</div>
                    <div class="flex-1">
                        <div class="flex h-6 bg-gray-200 rounded overflow-hidden">
                            <div class="bg-blue-500" style="width: {{ ($data['lelaki'] / ($data['lelaki'] + $data['perempuan'])) * 100 }}%"></div>
                            <div class="bg-pink-500" style="width: {{ ($data['perempuan'] / ($data['lelaki'] + $data['perempuan'])) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="w-16 text-right text-sm font-medium text-gray-700">
                        {{ number_format($data['lelaki'] + $data['perempuan']) }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Graph Section with PDF Export -->
    <div class="bg-white p-4 mb-6 rounded-lg shadow border border-gray-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Visualisasi Data</h3>
            <button id="exportPdfBtn" class="text-xs bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1 rounded flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Eksport PDF
            </button>
        </div>
        <div class="h-80">
            <canvas id="gredChart"></canvas>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white p-4 rounded-lg shadow border border-gray-300">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Terperinci</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Gred</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Lelaki</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Perempuan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Penyata Gaji</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $totalLelaki = 0;
                        $totalPerempuan = 0;
                        $totalPenyataGaji = 0;
                    @endphp
                    
                    @foreach($dataByGred as $gred => $data)
                        @php
                            $jumlah = $data['lelaki'] + $data['perempuan'];
                            $totalLelaki += $data['lelaki'];
                            $totalPerempuan += $data['perempuan'];
                            $totalPenyataGaji += $data['penyata_gaji'];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200">{{ $gred }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 border border-gray-200 text-right">{{ number_format($data['lelaki']) }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 border border-gray-200 text-right">{{ number_format($data['perempuan']) }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 border border-gray-200 text-right">{{ number_format($jumlah) }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 border border-gray-200 text-right">{{ number_format($data['penyata_gaji']) }}</td>
                        </tr>
                    @endforeach
                    
                    <!-- Total Row -->
                    <tr class="bg-gray-100 font-semibold">
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200">JUMLAH</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 border border-gray-200 text-right">{{ number_format($totalLelaki) }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 border border-gray-200 text-right">{{ number_format($totalPerempuan) }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 border border-gray-200 text-right">{{ number_format($totalLelaki + $totalPerempuan) }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 border border-gray-200 text-right">{{ number_format($totalPenyataGaji) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    // Initialize Chart
    const labels = @json(array_keys($dataByGred));
    const lelaki = @json(array_column($dataByGred, 'lelaki'));
    const perempuan = @json(array_column($dataByGred, 'perempuan'));
    const penyataGaji = @json(array_column($dataByGred, 'penyata_gaji'));

    const ctx = document.getElementById('gredChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Lelaki',
                    data: lelaki,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Perempuan',
                    data: perempuan,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Penyata Gaji',
                    data: penyataGaji,
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    type: 'line',
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah'
                    }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Penyata Gaji'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // PDF Export Function for Graph Only
    document.getElementById('exportPdfBtn').addEventListener('click', function() {
        // Create a temporary canvas with higher resolution
        const tempCanvas = document.createElement('canvas');
        const tempCtx = tempCanvas.getContext('2d');
        const originalCanvas = document.getElementById('gredChart');
        
        // Set higher resolution
        tempCanvas.width = originalCanvas.width * 2;
        tempCanvas.height = originalCanvas.height * 2;
        
        // Apply styles to make it look good in PDF
        tempCtx.fillStyle = 'white';
        tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
        
        // Draw the chart
        tempCtx.drawImage(originalCanvas, 0, 0, tempCanvas.width, tempCanvas.height);
        
        // Create PDF
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'mm'
        });
        
        // Add title
        pdf.setFontSize(16);
        pdf.text('Statistik Mengikut Gred', 20, 15);
        
        // Add filter info if any
        pdf.setFontSize(10);
        @if(request('year'))
            pdf.text(`Tahun: {{ request('year') }}`, 20, 25);
        @endif
        @if(request('month') && request('month') != 'all')
            pdf.text(`Bulan: {{ $all_bulan[request('month')] }}`, 20, 30);
        @endif
        @if(request('negeri'))
            pdf.text(`Negeri: {{ request('negeri') }}`, 20, 35);
        @endif
        
        // Add generated date
        pdf.text(`Dijana pada: ${new Date().toLocaleString()}`, 20, 40);
        
        // Add chart image
        pdf.addImage(
            tempCanvas.toDataURL('image/png'),
            'PNG',
            20,
            45,
            260,
            120
        );
        
        // Save PDF
        pdf.save(`Statistik-Gred-${new Date().toISOString().slice(0, 10)}.pdf`);
    });

    // Reset filter button
    document.getElementById('resetBtn').addEventListener('click', function() {
        document.getElementById('filterForm').reset();
        window.location.href = '{{ route("graf.kumpulan-perkhidmatan") }}';
    });
</script>
@endsection
@extends('layouts.app')

@section('content')

<div class="container mx-auto px-2 sm:px-4 py-4 bg-white rounded-lg shadow border border-gray-300">
    <!-- Header -->
    <div class="px-2 sm:px-0">
        <h1 class="text-lg sm:text-xl font-bold text-gray-700 mb-1 sm:mb-2">Selamat Datang, {{ auth()->user()->name }}</h1>
        <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">Anda log masuk sebagai <strong class="text-blue-500">Superadmin</strong>.</p>
    </div>

    @if(auth()->user()->role === 'superadmin')
    <!-- State Selection -->
    <div class="bg-white p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg shadow border border-gray-300">
        <h4 class="text-base sm:text-lg font-semibold mb-2 sm:mb-3">Pilih Negeri</h4>
        <form action="{{ route('dashboard') }}" method="GET" id="negeriForm">
            <select name="negeri" class="w-full text-sm sm:text-base border border-gray-300 rounded px-3 py-2">
                <option value="">-- Pilih Negeri --</option>
                @foreach([ 
                    'IBU PEJABAT','JOHOR','KEDAH','KELANTAN','MELAKA','NEGERI SEMBILAN','PAHANG',
                    'PULAU PINANG','PERAK','PERLIS','SELANGOR','TERENGGANU','SARAWAK',
                    'WILAYAH PERSEKUTUAN KUALA LUMPUR','WILAYAH PERSEKUTUAN LABUAN','WILAYAH PERSEKUTUAN PUTRAJAYA',
                    'FRAM WILAYAH UTARA','FRAM WILAYAH TIMUR','FRAM SABAH','FRAM SARAWAK'
                ] as $negeri)
                    <option value="{{ $negeri }}" {{ request('negeri') == $negeri ? 'selected' : '' }}>{{ $negeri }}</option>
                @endforeach
            </select>
        </form>
    </div>
    @endif

    @if(auth()->user()->role === 'superadmin' && request('negeri'))
    <!-- State Data Links -->
    <div class="bg-white p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg shadow border border-gray-300">
        <h4 class="text-base sm:text-lg font-semibold mb-2 sm:mb-3">Data Negeri: {{ request('negeri') }}</h4>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('penyata-gaji.index', ['negeri' => request('negeri')]) }}" class="text-xs sm:text-sm bg-blue-600 hover:bg-blue-700 text-white font-medium px-3 py-1 sm:px-4 sm:py-2 rounded transition">Penyata Gaji</a>
            <a href="{{ route('pinjaman-perumahan.index', ['negeri' => request('negeri')]) }}" class="text-xs sm:text-sm bg-green-600 hover:bg-green-700 text-white font-medium px-3 py-1 sm:px-4 sm:py-2 rounded transition">Pinjaman</a>
            <a href="{{ route('borang.index', ['negeri' => request('negeri')]) }}" class="text-xs sm:text-sm bg-yellow-600 hover:bg-yellow-700 text-white font-medium px-3 py-1 sm:px-4 sm:py-2 rounded transition">SKAI07</a>
        </div>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white p-3 sm:p-6 mb-4 sm:mb-6 rounded-lg shadow border border-gray-300">
        <form id="filterForm" class="flex flex-col sm:flex-row sm:space-x-4 space-y-3 sm:space-y-0">
            <div class="w-full sm:w-1/3">
                <label for="yearSelect" class="block text-xs sm:text-sm font-semibold text-gray-600 mb-1">Tahun</label>
                <select id="yearSelect" name="year" class="w-full text-sm sm:text-base border border-gray-300 rounded px-3 py-2">
                    @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="w-full sm:w-1/3">
                <label for="monthSelect" class="block text-xs sm:text-sm font-semibold text-gray-600 mb-1">Bulan</label>
                <select id="monthSelect" name="month" class="w-full text-sm sm:text-base border border-gray-300 rounded px-3 py-2">
                    <option value="">-- Semua --</option>
                    @foreach([1=>'Jan',2=>'Feb',3=>'Mac',4=>'Apr',5=>'Mei',6=>'Jun',
                    7=>'Jul',8=>'Ogos',9=>'Sept',10=>'Okt',11=>'Nov',12=>'Dis'] as $num => $name)
                        <option value="{{ $num }}" {{ request('month', date('n')) == $num ? 'selected' : '' }}>{{ $name }}</option>
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

        <!-- Charts Section -->
        <div class="mt-4 sm:mt-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 text-center mb-3 sm:mb-4">Rekod Mengikut Jenis Potongan</h3>
            
            <!-- Hutang Chart -->
            <div class="bg-white p-2 sm:p-4 mb-4 sm:mb-6 rounded shadow border border-gray-200">
                <h4 class="text-sm sm:text-base font-semibold text-center mb-2 sm:mb-3">Potongan Hutang</h4>
                <div class="h-[250px] sm:h-[350px]">
                    <canvas id="hutangLineChart"></canvas>
                </div>
            </div>
            
            <!-- Bukan Hutang Chart -->
            <div class="bg-white p-2 sm:p-4 rounded shadow border border-gray-200">
                <h4 class="text-sm sm:text-base font-semibold text-center mb-2 sm:mb-3">Potongan Bukan Hutang</h4>
                <div class="h-[250px] sm:h-[350px]">
                    <canvas id="bukanHutangLineChart"></canvas>
                </div>
            </div>
        </div>

        <div id="noDataMessage" class="text-center py-4 text-red-600 font-medium text-sm sm:text-base"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    Chart.register(ChartDataLabels);
    
    let hutangLineChart = null;
    let bukanHutangLineChart = null;

    const chartColors = {
        hutang: {
            border: '#3b82f6',
            background: 'rgba(59, 130, 246, 0.1)',
            point: '#3b82f6'
        },
        bukanHutang: {
            border: '#10b981',
            background: 'rgba(16, 185, 129, 0.1)',
            point: '#10b981'
        }
    };

    function formatLabel(key) {
        const isMobile = window.innerWidth < 640;
        const replacements = {
            'pinjaman_peribadi_bsn': isMobile ? 'BSN' : 'Pinjaman BSN',
            'pinjaman_perumahan': isMobile ? 'Perumahan' : 'Pinjaman Perumahan',
            'bayaran_balik_itp': isMobile ? 'ITP' : 'Bayaran Balik ITP',
            'bayaran_balik_bsh': isMobile ? 'BSH' : 'Bayaran Balik BSH',
            'kutipan_semula_emolumen': isMobile ? 'Emolumen' : 'Kutipan Emolumen',
            'arahan_potongan_nafkah': isMobile ? 'Nafkah' : 'Arahan Nafkah',
            'lain_lain_potongan_pembentungan': isMobile ? 'Pembentungan' : 'Potongan Pembentungan',
            'potongan_lembaga_th': isMobile ? 'Lembaga TH' : 'Potongan Lembaga TH',
            'amanah_saham_nasional': isMobile ? 'ASN' : 'Amanah Saham Nasional',
            'zakat_yayasan_wakaf': isMobile ? 'Zakat' : 'Zakat/Yayasan Wakaf'
        };
        
        return replacements[key] || key.split('_').map(word => 
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');
    }

    function getLineChartOptions() {
        const isMobile = window.innerWidth < 640;
        
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: (value) => value > 0 ? (value > 1000 ? (value/1000).toFixed(1)+'k' : value) : '',
                    color: '#333',
                    font: { 
                        weight: 'bold', 
                        size: isMobile ? 8 : 10 
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw.toLocaleString()}`;
                        }
                    },
                    bodyFont: {
                        size: isMobile ? 12 : 14
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: !isMobile,
                        text: 'Jenis Potongan',
                        font: { 
                            weight: 'bold', 
                            size: isMobile ? 12 : 14 
                        }
                    },
                    grid: { display: false },
                    ticks: {
                        autoSkip: true,
                        maxRotation: isMobile ? 45 : 45,
                        minRotation: isMobile ? 45 : 45,
                        font: {
                            size: isMobile ? 10 : 12
                        }
                    }
                },
                y: {
                    title: {
                        display: !isMobile,
                        text: 'Jumlah (Orang)',
                        font: { 
                            weight: 'bold', 
                            size: isMobile ? 12 : 14 
                        }
                    },
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value > 1000 ? (value/1000).toFixed(1)+'k' : value;
                        },
                        font: {
                            size: isMobile ? 10 : 12
                        }
                    }
                }
            },
            elements: {
                line: {
                    tension: 0.3,
                    borderWidth: isMobile ? 2 : 3,
                    fill: true
                },
                point: {
                    radius: isMobile ? 3 : 5,
                    hoverRadius: isMobile ? 5 : 7,
                    backgroundColor: '#fff',
                    borderWidth: 2
                }
            }
        };
    }

    async function fetchChartData() {
        const selectedNegeri = '{{ request("negeri") }}';
        const year = document.getElementById('yearSelect').value;
        const month = document.getElementById('monthSelect').value;
        const noDataElem = document.getElementById('noDataMessage');

        noDataElem.textContent = '';
        
        try {
            let url = '{{ route("dashboard.penyata_gaji_stats_by_negeri") }}?year=' + year;
            if (month) url += '&month=' + month;
            if (selectedNegeri) url += '&negeri=' + encodeURIComponent(selectedNegeri);

            const response = await fetch(url);
            if (!response.ok) throw new Error('Rangkaian bermasalah');
            
            const data = await response.json();
            
            if (!data || data.length === 0) {
                noDataElem.textContent = 'Tiada data untuk tahun/bulan/negeri ini.';
                if (hutangLineChart) hutangLineChart.destroy();
                if (bukanHutangLineChart) bukanHutangLineChart.destroy();
                return;
            }

            // Define categories
            const hutangCategories = [
                'pinjaman_peribadi_bsn', 'pinjaman_perumahan', 'bayaran_balik_itp', 
                'bayaran_balik_bsh', 'ptptn', 'kutipan_semula_emolumen', 
                'arahan_potongan_nafkah', 'komputer', 'pcb',
                'lain_lain_potongan_pembentungan', 'koperasi', 'berkat', 'angkasa'
            ];

            const bukanHutangCategories = [
                'potongan_lembaga_th', 'amanah_saham_nasional', 
                'zakat_yayasan_wakaf', 'insuran', 'kwsp', 
                'i_destinasi', 'angkasa_bukan_pinjaman'
            ];

            // CORRECTED CALCULATION FUNCTION
            const calculateTotals = (categories, data) => {
                return categories.map(category => {
                    return data.reduce((sum, record) => sum + (parseInt(record[category] || 0)), 0);
                });
            };

            const hutangData = calculateTotals(hutangCategories, data);
            const bukanHutangData = calculateTotals(bukanHutangCategories, data);

            // Destroy existing charts
            if (hutangLineChart) hutangLineChart.destroy();
            if (bukanHutangLineChart) bukanHutangLineChart.destroy();

            // Create Hutang Line Chart
            const hutangCtx = document.getElementById('hutangLineChart').getContext('2d');
            hutangLineChart = new Chart(hutangCtx, {
                type: 'line',
                data: {
                    labels: hutangCategories.map(cat => formatLabel(cat)),
                    datasets: [{
                        label: 'Jumlah Hutang',
                        data: hutangData,
                        borderColor: chartColors.hutang.border,
                        backgroundColor: chartColors.hutang.background,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: chartColors.hutang.point,
                        pointHoverBackgroundColor: chartColors.hutang.point,
                        pointHoverBorderColor: '#fff'
                    }]
                },
                options: getLineChartOptions()
            });

            // Create Bukan Hutang Line Chart
            const bukanHutangCtx = document.getElementById('bukanHutangLineChart').getContext('2d');
            bukanHutangLineChart = new Chart(bukanHutangCtx, {
                type: 'line',
                data: {
                    labels: bukanHutangCategories.map(cat => formatLabel(cat)),
                    datasets: [{
                        label: 'Jumlah Bukan Hutang',
                        data: bukanHutangData,
                        borderColor: chartColors.bukanHutang.border,
                        backgroundColor: chartColors.bukanHutang.background,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: chartColors.bukanHutang.point,
                        pointHoverBackgroundColor: chartColors.bukanHutang.point,
                        pointHoverBorderColor: '#fff'
                    }]
                },
                options: getLineChartOptions()
            });

        } catch (error) {
            console.error('Error:', error);
            noDataElem.textContent = 'Ralat ketika memuat data. Sila cuba lagi.';
        }
    }

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (hutangLineChart || bukanHutangLineChart) {
                fetchChartData();
            }
        }, 200);
    });

    document.addEventListener('DOMContentLoaded', function() {
        fetchChartData();
        
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchChartData();
            });
        }

        // Add event listener for negeri select change
        const negeriSelect = document.querySelector('#negeriForm select[name="negeri"]');
        if (negeriSelect) {
            negeriSelect.addEventListener('change', function() {
                document.getElementById('negeriForm').submit();
            });
        }
    });

    // Reset filter button
    document.getElementById('resetBtn').addEventListener('click', function() {
        document.getElementById('filterForm').reset();
        window.location.href = '{{ route("dashboard") }}';
    });
</script>

@endsection

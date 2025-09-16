@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

    <!-- 🔹 Header -->
    <div class="bg-white shadow-sm rounded-xl mb-6">
        <div class="px-6 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Kiri: Tombol Kembali + Judul -->
            <div class="flex items-center gap-6">
                <!-- Tombol Kembali -->
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center px-3 py-2 rounded-md bg-gradient-to-r from-purple-600 to-indigo-600 
                          hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-medium shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-6-6 6-6" />
                    </svg>
                    Kembali
                </a>

                <!-- Info Utama -->
                <div class="flex flex-col leading-tight">
                    <span class="text-lg md:text-xl font-semibold text-gray-800">
                        Rangkuman Keuangan
                    </span>
                    <span class="text-sm text-gray-500 mt-0.5">
                        Rekap pemasukan, pengeluaran, dan profit tahun {{ $year }}
                    </span>
                </div>
            </div>

            <!-- Tombol Download -->
<a href="{{ route('admin.finance.summary.excel', ['year' => $year, 'bulan' => request('bulan')]) }}" 
   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
    <svg xmlns="http://www.w3.org/2000/svg" 
         fill="none" viewBox="0 0 24 24" 
         stroke-width="1.5" stroke="currentColor" 
         class="w-5 h-5 mr-2">
        <path stroke-linecap="round" stroke-linejoin="round" 
              d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5
                 A2.25 2.25 0 0021 18.75V16.5M7.5 10.5l4.5 
                 4.5m0 0l4.5-4.5m-4.5 4.5V3" />
    </svg>
    Download
</a>

        </div>
    </div>

    <!-- 🔹 Filter -->
    <div class="bg-white rounded-xl shadow mb-6 overflow-hidden">
        <!-- Header Filter -->
        <div class="flex items-center gap-2 bg-gray-50 border-b px-6 py-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6h18M3 14h18M3 18h18" />
            </svg>
            <h2 class="text-sm font-medium text-gray-600">Filter Rangkuman</h2>
        </div>

        <!-- Form Filter -->
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('admin.finance.summary') }}" 
                  class="flex flex-col md:flex-row md:items-end gap-6">

                <!-- Tahun -->
                <div class="flex flex-col flex-1">
                    <label class="text-sm font-medium text-gray-700">Tahun</label>
                    <select name="year" 
                            class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-300 text-gray-800
                                   focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                        @for($i = date('Y'); $i >= date('Y')-5; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Bulan -->
                <div class="flex flex-col flex-1">
                    <label class="text-sm font-medium text-gray-700">Bulan</label>
                    <input type="month" name="bulan" value="{{ request('bulan') }}"
                           class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-300 text-gray-800
                                  focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                </div>

                <!-- Tombol Aksi -->
                <div class="flex gap-2 md:ml-auto">
                    <button type="submit" 
                            class="px-4 py-2 rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 
                                   text-white text-sm font-medium shadow hover:from-purple-700 hover:to-indigo-700 
                                   transition">
                        Filter
                    </button>
                    <a href="{{ route('admin.finance.summary') }}" 
                       class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium 
                              border border-gray-300 shadow hover:bg-gray-200 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- 🔹 Ringkasan Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Pemasukan -->
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition flex items-start gap-3">
            <div class="p-2 bg-green-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 
                             3 .895 3 2-1.343 2-3 2m0-8v.01M12 20h0
                             m-6-4h12a2 2 0 002-2V6a2 2 0 00-2-2H6
                             a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <span class="text-sm text-gray-500">Total Pemasukan</span>
                <h3 class="text-2xl font-bold text-green-600">
                    Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}
                </h3>
            </div>
        </div>

        <!-- Total Pengeluaran -->
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition flex items-start gap-3">
            <div class="p-2 bg-red-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 20v-8m0 0l-4 4m4-4l4 4M4 4h16v4H4z" />
                </svg>
            </div>
            <div>
                <span class="text-sm text-gray-500">Total Pengeluaran</span>
                <h3 class="text-2xl font-bold text-red-600">
                    Rp {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}
                </h3>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition flex items-start gap-3">
            <div class="p-2 bg-cyan-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 12l5 5L20 7" />
                </svg>
            </div>
            <div>
                <span class="text-sm text-gray-500">Net Profit</span>
                <h3 class="text-2xl font-bold text-cyan-600">
                    Rp {{ number_format($summary['net_profit'], 0, ',', '.') }}
                </h3>
            </div>
        </div>

        <!-- Profit Margin -->
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition flex items-start gap-3">
            <div class="p-2 bg-indigo-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M7 20h10M9 16h6M12 4v12" />
                </svg>
            </div>
            <div>
                <span class="text-sm text-gray-500">Profit Margin</span>
                <h3 class="text-2xl font-bold text-indigo-600">
                    {{ $summary['profit_margin'] }}%
                </h3>
            </div>
        </div>
    </div>

    <!-- 🔹 Charts -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="md:col-span-2 bg-white p-4 shadow rounded-xl">
        <h3 class="mb-3 font-semibold text-gray-900">
            Trend Keuangan Bulanan {{ $year }}
        </h3>
        <div class="h-72">
            <canvas id="trendChart" class="w-full h-full"></canvas>
        </div>
    </div>

    <div class="bg-white p-4 shadow rounded-xl">
        <h3 class="mb-3 font-semibold text-gray-900">
            Komposisi Pemasukan
        </h3>
        <div class="h-72">
            <canvas id="komposisiChart" class="w-full h-full"></canvas>
        </div>
    </div>
</div>


    <!-- 🔹 Top 5 Pengeluaran -->
    <div class="bg-white rounded-xl shadow">
        <div class="flex items-center gap-2 p-4 border-b bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-5 w-5 text-red-500" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-bold text-gray-700">Top 5 Pengeluaran</h3>
        </div>

        <div class="divide-y">
            @php
                $total_pengeluaran = $top_pengeluaran->sum('total') ?: 1;
            @endphp

            @forelse ($top_pengeluaran as $row)
                @php
                    $persen = ($row->total / $total_pengeluaran) * 100;
                @endphp
                <div class="p-4">
                    <div class="flex justify-between items-center">
                        <p class="font-medium text-gray-700">{{ $row->keterangan }}</p>
                        <p class="font-semibold text-red-600">
                            Rp {{ number_format($row->total, 0, ',', '.') }}
                        </p>
                    </div>
                    <p class="text-sm text-gray-500">
                        {{ number_format($persen, 1) }}% dari total pengeluaran
                    </p>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                        <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ $persen }}%"></div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="w-8 h-8 text-gray-400 mb-2" fill="none" 
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M9 17v-6h6v6h5V7H4v10h5z" />
                        </svg>
                        Belum ada data pengeluaran
                    </div>
                </div>
            @endforelse
        </div>
    </div>

        <!-- 🔹 Rincian Pemasukan -->
    <div class="bg-white rounded-xl shadow mt-6">
        <div class="flex items-center gap-2 p-4 border-b bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-5 w-5 text-green-600" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 
                         3 .895 3 2-1.343 2-3 2m0-8v.01M12 20h0
                         m-6-4h12a2 2 0 002-2V6a2 2 0 00-2-2H6
                         a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <h3 class="text-lg font-bold text-gray-700">Rincian Pemasukan</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
            <!-- Voucher Online -->
            <div class="flex flex-col border-l-4 border-green-500 pl-3">
                <span class="text-sm text-gray-500">Voucher Online</span>
                <span class="text-lg font-semibold text-green-600">
                    Rp {{ number_format($rincian['voucher_online'] ?? 0, 0, ',', '.') }}
                </span>
            </div>

            <!-- Voucher Offline -->
            <div class="flex flex-col border-l-4 border-cyan-500 pl-3">
                <span class="text-sm text-gray-500">Voucher Offline</span>
                <span class="text-lg font-semibold text-green-600">
                    Rp {{ number_format($rincian['voucher_offline'] ?? 0, 0, ',', '.') }}
                </span>
            </div>

            <!-- Billing Online -->
            <div class="flex flex-col border-l-4 border-blue-500 pl-3">
                <span class="text-sm text-gray-500">Billing Online</span>
                <span class="text-lg font-semibold text-green-600">
                    Rp {{ number_format($rincian['billing_online'] ?? 0, 0, ',', '.') }}
                </span>
            </div>

            <!-- Billing Offline -->
            <div class="flex flex-col border-l-4 border-yellow-500 pl-3">
                <span class="text-sm text-gray-500">Billing Offline</span>
                <span class="text-lg font-semibold text-green-600">
                    Rp {{ number_format($rincian['billing_offline'] ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

<!-- 🔹 Metrik Pelanggan & Performa -->
<div class="mt-6">
    <h3 class="text-lg font-bold text-white-700 mb-4 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="h-5 w-5 text-white-700" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-3-3h-2v5zM2 20h5v-5H4a2 2 0 00-2 2v3zm5-9h10V4a2 2 0 00-2-2H9a2 2 0 00-2 2v7z" />
        </svg>
        Metrik Pelanggan & Performa
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Pelanggan Aktif -->
        <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-8 w-8 text-blue-600 mb-2" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-3-3h-2v5zM2 20h5v-5H4a2 2 0 00-2 2v3zm5-9h10V4a2 2 0 00-2-2H9a2 2 0 00-2 2v7z" />
            </svg>
            <h3 class="text-2xl font-bold text-blue-600">
                {{ $summary['total_pelanggan'] ?? 0 }}
            </h3>
            <span class="text-sm text-gray-500">Total Pelanggan Aktif</span>
        </div>

        <!-- Growth Rate -->
        <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-8 w-8 text-green-600 mb-2" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
            <h3 class="text-2xl font-bold text-green-600">
                {{ $metrics['growth_rate'] ?? 0 }}%
            </h3>
            <span class="text-sm text-gray-500">Growth Rate</span>
        </div>

        <!-- Churn Rate -->
        <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-8 w-8 text-yellow-500 mb-2" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 9l-7 7-7-7" />
            </svg>
            <h3 class="text-2xl font-bold text-yellow-500">
                {{ $metrics['churn_rate'] ?? 0 }}%
            </h3>
            <span class="text-sm text-gray-500">Churn Rate</span>
        </div>

        <!-- ARPU -->
        <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-8 w-8 text-cyan-600 mb-2" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 
                         3 .895 3 2-1.343 2-3 2m0-8v.01M12 20h0" />
            </svg>
            <h3 class="text-2xl font-bold text-cyan-600">
                Rp {{ number_format($metrics['arpu'] ?? 0, 0, ',', '.') }}
            </h3>
            <span class="text-sm text-gray-500">ARPU (Avg Revenue per User)</span>
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // 🔹 Data dari backend (kalau kosong/null → jadi [])
    const trendData = {!! json_encode($trend ?? []) !!};
    const komposisiData = {!! json_encode($komposisi ?? []) !!};

    // 🔹 Labels default bulan
    const trendLabels = trendData.length > 0 
        ? trendData.map(d => d.nama_bulan) 
        : ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

    // 🔹 Dataset dummy kalau data kosong
    const pemasukan = trendData.length > 0 
        ? trendData.map(d => d.pemasukan) 
        : [1000, 1200, 1500, 1700, 1600, 1800, 1900, 2100, 2200, 2000, 2300, 2500];

    const pengeluaran = trendData.length > 0 
        ? trendData.map(d => d.pengeluaran) 
        : [800, 900, 1100, 1200, 1000, 1300, 1400, 1500, 1600, 1500, 1700, 1800];

    const profit = trendData.length > 0 
        ? trendData.map(d => d.profit) 
        : pemasukan.map((p, i) => p - pengeluaran[i]);

    // 🔹 Komposisi data dummy
    const komposisiLabels = komposisiData.length > 0 
        ? komposisiData.map(d => d.kategori) 
        : ["Produk A", "Produk B", "Produk C", "Produk D"];

    const komposisiJumlah = komposisiData.length > 0 
        ? komposisiData.map(d => d.jumlah) 
        : [5000, 3000, 2000, 1000];

    // ===============================
    // 📊 Trend Keuangan (Line Chart)
    // ===============================
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: pemasukan,
                    borderColor: 'rgb(34,197,94)',   // hijau
                    backgroundColor: 'rgba(34,197,94,0.2)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaran,
                    borderColor: 'rgb(239,68,68)',   // merah
                    backgroundColor: 'rgba(239,68,68,0.2)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Profit',
                    data: profit,
                    borderColor: 'rgb(59,130,246)',  // biru
                    backgroundColor: 'rgba(59,130,246,0.2)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#000' // 🔹 warna label legend hitam
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#000' // 🔹 warna sumbu X hitam
                    }
                },
                y: {
                    ticks: {
                        color: '#000' // 🔹 warna sumbu Y hitam
                    }
                }
            }
        }
    });

    // ===============================
    // 📊 Komposisi Pemasukan (Pie Chart)
    // ===============================
    const ctxKomposisi = document.getElementById('komposisiChart').getContext('2d');
    new Chart(ctxKomposisi, {
        type: 'pie',
        data: {
            labels: komposisiLabels,
            datasets: [{
                label: 'Pemasukan',
                data: komposisiJumlah,
                backgroundColor: [
                    'rgba(34,197,94,0.6)',   // hijau
                    'rgba(59,130,246,0.6)',  // biru
                    'rgba(239,68,68,0.6)',   // merah
                    'rgba(234,179,8,0.6)',   // kuning
                ],
                borderColor: [
                    'rgb(34,197,94)',
                    'rgb(59,130,246)',
                    'rgb(239,68,68)',
                    'rgb(234,179,8)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#000' // 🔹 warna legend hitam
                    }
                }
            }
        }
    });

});

</script>

@endpush

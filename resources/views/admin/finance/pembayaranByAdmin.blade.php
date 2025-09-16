@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4">

    <!-- 🔹 Header -->
    <div class="bg-white shadow-sm rounded-xl mb-6">
        <div class="px-6 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Kiri: Tombol Kembali + Info Utama -->
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

                <!-- Info Utama / Judul -->
                <div class="flex flex-col leading-tight">
                    <span class="text-lg md:text-xl font-semibold text-gray-800">
                        Pembayaran By Admin
                    </span>
                    <span class="text-sm text-gray-500 mt-0.5">
                        Riwayat pembayaran manual yang dilakukan oleh admin
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔹 Filter Card -->
    <div class="bg-white rounded-xl shadow mb-6 overflow-hidden">
        <!-- Header Card -->
        <div class="flex items-center gap-2 bg-gray-50 border-b px-6 py-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M3 10h18M3 16h18" />
            </svg>
            <h2 class="text-sm font-medium text-gray-600">Filter Pembayaran</h2>
        </div>

        <!-- Isi Card -->
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('admin.finance.index') }}" class="flex flex-col md:flex-row md:items-end gap-6">
                <!-- Bulan -->
                <div class="flex flex-col flex-1">
                    <label class="text-sm font-medium text-gray-700">Bulan</label>
                    <input type="month" name="bulan" value="{{ request('bulan', now()->format('Y-m')) }}"
                           class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-300 text-gray-800
                                  focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                </div>

                <!-- Device/Admin -->
                <div class="flex flex-col flex-1">
                    <label class="text-sm font-medium text-gray-700">Device/Admin</label>
                    <select name="device" 
                            class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-300 text-gray-800
                                   focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                        <option value="">Semua Device</option>
                        @foreach($rekap as $d)
                            <option value="{{ $d['nama'] }}" {{ request('device') == $d['nama'] ? 'selected' : '' }}>
                                {{ $d['nama'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex gap-2 md:ml-auto">
                    <button type="submit"
                            class="px-4 py-1.5 rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 
                                   text-white text-sm font-medium shadow hover:from-purple-700 hover:to-indigo-700 
                                   transition">
                        Filter
                    </button>
                    <a href="{{ route('admin.finance.index') }}" 
                       class="px-4 py-1.5 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium 
                              border border-gray-300 shadow hover:bg-gray-200 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- 🔹 Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Pembayaran -->
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition flex items-start gap-3">
            <div class="p-2 bg-green-100 rounded-lg">
                <!-- 💰 Ikon Uang -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 
                             3 .895 3 2-1.343 2-3 2m0-8v.01M12 20h0
                             m-6-4h12a2 2 0 002-2V6a2 2 0 
                             00-2-2H6a2 2 0 00-2 2v8a2 2 0 
                             002 2z" />
                </svg>
            </div>
            <div>
                <span class="text-sm text-gray-500">Total Pembayaran</span>
                <h3 class="text-2xl font-bold text-green-600">
                    Rp. {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}
                </h3>
            </div>
        </div>

        <!-- Jumlah Transaksi -->
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition flex items-start gap-3">
            <div class="p-2 bg-blue-100 rounded-lg">
                <!-- 📑 Ikon Document -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 
                             01-2-2V5a2 2 0 012-2h5.586a1 1 0 
                             01.707.293l5.414 5.414a1 1 0 
                             01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <span class="text-sm text-gray-500">Jumlah Transaksi</span>
                <h3 class="text-2xl font-bold text-blue-600">
                    {{ $rekap->count() }}
                </h3>
            </div>
        </div>

        <!-- Customer Terlayani -->
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition flex items-start gap-3">
            <div class="p-2 bg-blue-100 rounded-lg">
                <!-- 👥 Ikon Users -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM4 14a4 4 0 014-4h4a4 4 0 014 4v1H4v-1z" />
                </svg>
            </div>
            <div>
                <span class="text-sm text-gray-500">Customer Terlayani</span>
                <h3 class="text-2xl font-bold text-blue-600">
                    {{ $summary['total_pelanggan'] }}
                </h3>
            </div>
        </div>

        <!-- Rata-rata per Transaksi -->
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition flex items-start gap-3">
            <div class="p-2 bg-yellow-100 rounded-lg">
                <!-- 📊 Ikon Chart -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M11 19V6m4 13V10m4 9V4M7 19v-4" />
                </svg>
            </div>
            <div>
                <span class="text-sm text-gray-500">Rata-rata per Transaksi</span>
                <h3 class="text-2xl font-bold text-yellow-600">
                    Rp. {{ $rekap->count() > 0 ? number_format($summary['total_pendapatan'] / $rekap->count(), 0, ',', '.') : 0 }}
                </h3>
            </div>
        </div>
    </div>

    <!-- 🔹 Tombol Export -->
    <div class="flex justify-end gap-2 mb-6">
        <!-- Export Excel -->
        <a href="{{ route('admin.finance.export.excel') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-green-500 text-green-600 
                  rounded-lg text-sm transition select-none
                  hover:bg-green-50 active:bg-green-100 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l6 6m0-6l-6 6"/>
            </svg>
            Export Excel
        </a>

        <!-- Export PDF -->
        <a href="{{ route('admin.finance.export.pdf') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-red-500 text-red-600 
                  rounded-lg text-sm transition select-none
                  hover:bg-red-50 active:bg-red-100 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Export PDF
        </a>
    </div>

    <!-- 🔹 Tabel Riwayat Pembayaran -->
    <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
        <!-- Header Tabel -->
        <div class="flex items-center gap-2 p-4 border-b bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M3 9h18M3 15h18M3 21h18" />
            </svg>
            <h3 class="text-lg font-bold text-gray-700">Riwayat Pembayaran Manual</h3>
        </div>

        <!-- Isi Tabel -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 border text-left">No</th>
                        <th class="px-4 py-3 border text-left">Tanggal</th>
                        <th class="px-4 py-3 border text-left">Customer</th>
                        <th class="px-4 py-3 border text-left">Device/Admin</th>
                        <th class="px-4 py-3 border text-right">Jumlah</th>
                        <th class="px-4 py-3 border text-left">Periode</th>
                        <th class="px-4 py-3 border text-left">Status</th>
                        <th class="px-4 py-3 border text-left">Dicatat Oleh</th>
                        <th class="px-4 py-3 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat ?? [] as $i => $r)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $i+1 }}</td>
                        <td class="px-4 py-2">{{ $r['tanggal'] }}</td>
                        <td class="px-4 py-2">{{ $r['deskripsi'] }}</td>
                        <td class="px-4 py-2">{{ $r['sumber'] }}</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($r['jumlah'],0,',','.') }}</td>
                        <td class="px-4 py-2">-</td>
                        <td class="px-4 py-2 capitalize">{{ $r['jenis'] }}</td>
                        <td class="px-4 py-2">Admin</td>
                        <td class="px-4 py-2 text-center">-</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 13h6m-3-3v6m-9 2a9 9 0 1118 0 9 9 0 01-18 0z"/>
                                </svg>
                                Tidak ada pembayaran manual ditemukan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

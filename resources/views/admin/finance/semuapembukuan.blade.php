@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

<!-- 🔹 Header Baru dalam Card yang Rapi -->
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
                    Semua Pembukuan
                </span>
                <span class="text-sm text-gray-500 mt-0.5">
                    Rekap pendapatan, setor, dan pengeluaran untuk semua perangkat
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6h18M3 14h18M3 18h18" />
        </svg>
        <h2 class="text-sm font-medium text-gray-600">Filter Transaksi</h2>
    </div>

    <!-- Isi Card -->
    <div class="px-6 py-4">
        <form method="GET" action="{{ route('admin.finance.index') }}" 
              class="flex flex-col md:flex-row md:items-end gap-6">

            <!-- Bulan -->
            <div class="flex flex-col flex-1">
                <label class="text-sm font-medium text-gray-700">Bulan</label>
                <input type="month" name="bulan" value="{{ request('bulan') }}"
                       class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-300 text-gray-800
                              focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
            </div>

            <!-- Jenis Transaksi -->
            <div class="flex flex-col flex-1">
                <label class="text-sm font-medium text-gray-700">Jenis Transaksi</label>
                <select name="jenis" 
                        class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-300 text-gray-800
                               focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                    <option value="semua" {{ request('jenis')=='semua' ? 'selected' : '' }}>Semua</option>
                    <option value="pemasukan" {{ request('jenis')=='pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ request('jenis')=='pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-2 md:ml-auto">
                <a href="{{ route('admin.finance.semua') }}" 
                   class="px-4 py-1.5 rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 
                               text-white text-sm font-medium shadow hover:from-purple-700 hover:to-indigo-700 
                               transition">
                    Filter
                </a>
                <a href="{{ route('admin.finance.semua') }}" 
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

   <!-- Total Pendapatan -->
    <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition flex items-start gap-3">
        <div class="p-2 bg-green-100 rounded-lg">
            <!-- 💰 Ikon Uang -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v.01M12 20h0m-6-4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
        </div>
        <div>
            <span class="text-sm text-gray-500">Total Pendapatan</span>
            <h3 class="text-2xl font-bold text-green-600">
                Rp. {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}
            </h3>
        </div>
    </div>


    <!-- Total Setor -->
    <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition flex items-start gap-3">
        <div class="p-2 bg-blue-100 rounded-lg">
            <!-- 📥 Ikon Setor -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
        </div>
        <div>
            <span class="text-sm text-gray-500">Total Setor</span>
            <h3 class="text-2xl font-bold text-blue-600">
                Rp. {{ number_format($summary['total_setor'], 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <!-- Total Pengeluaran -->
    <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition flex items-start gap-3">
        <div class="p-2 bg-red-100 rounded-lg">
            <!-- 📤 Ikon Pengeluaran -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20v-8m0 0l-4 4m4-4l4 4M4 4h16v4H4z" />
            </svg>
        </div>
        <div>
            <span class="text-sm text-gray-500">Total Pengeluaran</span>
            <h3 class="text-2xl font-bold text-red-600">
                Rp. {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}
            </h3>
        </div>
    </div>

   <!-- Sisa -->
<div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition flex items-start gap-3">
    <div class="p-2 bg-indigo-100 rounded-lg">
        <!-- 📊 Ikon Sisa (Bar Chart) -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 19V9m4 10V5m4 14v-7M5 19v-3" />
        </svg>
    </div>
    <div>
        <span class="text-sm text-gray-500">Sisa</span>
        <h3 class="text-2xl font-bold text-indigo-600">
            Rp. {{ number_format($summary['total_sisa'], 0, ',', '.') }}
        </h3>
    </div>
</div>


</div>
<!-- 🔹 Tombol Export di luar Card -->
<div class="flex justify-end gap-2 mb-3">
    <!-- Export Excel -->
    <a href="{{ route('admin.finance.export.excel') }}"
       class="inline-flex items-center gap-2 px-4 py-2 border border-green-500 text-green-600 
              rounded-lg text-sm transition select-none
              hover:bg-transparent active:bg-transparent focus:ring-0 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l6 6m0-6l-6 6"/>
        </svg>
        Export Excel
    </a>

    <!-- Export PDF -->
    <a href="{{ route('admin.finance.export.pdf') }}"
       class="inline-flex items-center gap-2 px-4 py-2 border border-red-500 text-red-600 
              rounded-lg text-sm transition select-none
              hover:bg-transparent active:bg-transparent focus:ring-0 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Export PDF
    </a>
</div>


<!-- 🔹 Tabel Rekap -->
<!-- 🔹 Tabel Riwayat Transaksi -->
<!-- 🔹 Tabel Riwayat Transaksi -->
<div class="bg-white rounded-xl shadow overflow-x-auto">
    <!-- Header Tabel -->
    <div class="flex items-center gap-2 p-4 border-b bg-gray-50">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="h-5 w-5 text-gray-600" 
             fill="none" viewBox="0 0 24 24" 
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M3 3h18M3 9h18M3 15h18M3 21h18" />
        </svg>
        <h3 class="text-lg font-bold text-gray-700">Riwayat Transaksi</h3>
    </div>

    <!-- Isi Tabel -->
    <table class="min-w-full text-sm border-collapse">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="px-3 py-2 text-left border">No</th>
                <th class="px-3 py-2 text-left border">Tanggal</th>
                <th class="px-3 py-2 text-left border">Jenis</th>
                <th class="px-3 py-2 text-right border">Jumlah</th>
                <th class="px-3 py-2 text-left border">Deskripsi</th>
                <th class="px-3 py-2 text-left border">Sumber</th>
                <th class="px-3 py-2 text-center border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayat as $index => $item)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-3 py-2">{{ $index + 1 }}</td>
                <td class="px-3 py-2">{{ $item->tanggal }}</td>
                <td class="px-3 py-2 capitalize">
                    @if($item->jenis == 'pemasukan')
                        <span class="text-green-600 font-semibold">Pemasukan</span>
                    @elseif($item->jenis == 'pengeluaran')
                        <span class="text-red-600 font-semibold">Pengeluaran</span>
                    @else
                        <span class="text-gray-600">-</span>
                    @endif
                </td>
                <td class="px-3 py-2 text-right">
                    Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                </td>
                <td class="px-3 py-2">{{ $item->deskripsi ?? '-' }}</td>
                <td class="px-3 py-2">{{ $item->sumber ?? '-' }}</td>
                <td class="px-3 py-2 text-center">
                    <div class="flex justify-center gap-2">
                        <!-- Detail -->
                        <a href="{{ route('transaksi.show', $item->id ?? 0) }}" 
                           class="p-1.5 rounded-lg border border-blue-500 text-blue-600 hover:bg-blue-50 transition"
                           title="Lihat Detail">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" 
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>

                        <!-- Edit -->
                        <a href="{{ route('transaksi.edit', $item->id ?? 0) }}" 
                           class="p-1.5 rounded-lg border border-yellow-500 text-yellow-600 hover:bg-yellow-50 transition"
                           title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" 
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z" />
                            </svg>
                        </a>

                        <!-- Hapus -->
                        <form action="{{ route('transaksi.destroy', $item->id ?? 0) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="p-1.5 rounded-lg border border-red-500 text-red-600 hover:bg-red-50 transition"
                                    title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" 
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6l1 1h4M4 7h4l1-1h6l1 1h4" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="w-8 h-8 text-gray-400 mb-2" fill="none" 
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M9 17v-6h6v6h5V7H4v10h5z" />
                        </svg>
                        Tidak ada transaksi
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>


</div>
@endsection

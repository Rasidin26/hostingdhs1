@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

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
                    Keuangan Admin
                </span>
                <span class="text-sm text-gray-500 mt-0.5">
    Total pendapatan dan pengeluaran ditampilkan untuk bulan ini
</span>

                </span>
            </div>
        </div>

        <!-- Kanan: Label Tambahan / Tag / Status (Optional) -->
        <div class="flex items-center gap-2">
            <span class="bg-gray-200 text-gray-800 text-xs px-2 py-1 rounded-full font-medium">
                Status: Aktif
            </span>
            <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full font-medium">
                Bulan: {{ \Carbon\Carbon::now()->format('M Y') }}
            </span>
        </div>

    </div>
</div>


    <!-- Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition">
            <h3 class="text-sm text-gray-500">Total Pendapatan</h3>
            <p class="text-2xl font-bold text-green-600">
                Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition">
            <h3 class="text-sm text-gray-500">Total Setor</h3>
            <p class="text-2xl font-bold text-blue-600">
                Rp {{ number_format($summary['total_setor'], 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition">
            <h3 class="text-sm text-gray-500">Total Pengeluaran</h3>
            <p class="text-2xl font-bold text-red-600">
                 Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition">
            <h3 class="text-sm text-gray-500">Total Sisa</h3>
            <p class="text-2xl font-bold text-indigo-600">
                Rp {{ number_format($summary['total_sisa'], 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition">
            <h3 class="text-sm text-gray-500">Total Pelanggan</h3>
            <p class="text-2xl font-bold text-purple-600">
                {{ $summary['total_pelanggan'] }}
            </p>
        </div>
    </div>
<!-- Search sederhana di pojok kiri -->
<div class="mb-6 flex items-center">
    <input type="text" id="search" placeholder="Cari nama admin..." 
        class="px-3 py-2 border rounded-md text-sm w-48 text-gray-800 focus:outline-none focus:ring-1 focus:ring-indigo-500">
    <button id="searchButton" 
        class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700 transition">
        Cari
    </button>
</div>


<!-- Tabel Rekap per Admin -->
<div class="bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
    <table id="rekapTable" class="w-full text-sm text-left border-collapse">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 border">Nama Admin</th>
                <th class="px-4 py-3 border">Total Transaksi</th>
                <th class="px-4 py-3 border">Total Setor</th>
                <th class="px-4 py-3 border">Total Pengeluaran</th>
                <th class="px-4 py-3 border">Sisa</th>
                <th class="px-4 py-3 border">Persentase</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
        @if($rekap->isEmpty())
            <tr>
                <td colspan="6" class="text-center py-6 text-gray-500">
                    Belum ada transaksi
                </td>
            </tr>
        @else
            @foreach($rekap as $r)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-2 border font-medium text-gray-700">
                    {{ $r['nama'] }}
                </td>
                <td class="px-4 py-2 border text-green-600">
                    Rp {{ number_format($r['total_transaksi'], 0, ',', '.') }}
                </td>
                <td class="px-4 py-2 border text-blue-600">
                    Rp {{ number_format($r['total_setor'], 0, ',', '.') }}
                </td>
                <td class="px-4 py-2 border text-red-600">
                    Rp {{ number_format($r['total_pengeluaran'], 0, ',', '.') }}
                </td>
                <td class="px-4 py-2 border text-indigo-600">
                    Rp {{ number_format($r['sisa'], 0, ',', '.') }}
                </td>
                <td class="px-4 py-2 border font-semibold text-gray-700">
                    {{ $r['persentase'] }}%
                </td>
            </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

<!-- 🔹 Tambahkan script JS di bawah -->
<script>
document.getElementById('searchButton').addEventListener('click', function() {
    let input = document.getElementById('search').value.toLowerCase().trim();
    let table = document.getElementById('rekapTable');
    let trs = table.getElementsByTagName('tr');
    let found = false;

    for (let i = 1; i < trs.length; i++) { // mulai dari 1 supaya skip thead
        let td = trs[i].getElementsByTagName('td')[0]; // kolom Nama Admin
        if (td) {
            let textValue = td.textContent || td.innerText;
            if (textValue.toLowerCase().indexOf(input) > -1) {
                trs[i].style.display = ""; // tampilkan
                found = true;
            } else {
                trs[i].style.display = "none"; // sembunyikan
            }
        }
    }

    if (!found) {
        alert('Nama admin tidak ditemukan!');
        // tampilkan kembali semua data setelah alert
        for (let i = 1; i < trs.length; i++) {
            trs[i].style.display = "";
        }
        // opsional: kosongkan input search
        document.getElementById('search').value = '';
    }
});


document.getElementById('searchButton').addEventListener('click', function() {
    let input = document.getElementById('search').value.toLowerCase().trim();
    let table = document.getElementById('rekapTable');
    let trs = table.getElementsByTagName('tr');
    let found = false;

    for (let i = 1; i < trs.length; i++) { // mulai dari 1 supaya skip thead
        let td = trs[i].getElementsByTagName('td')[0]; // kolom Nama Admin
        if (td) {
            let textValue = td.textContent || td.innerText;
            if (textValue.toLowerCase().indexOf(input) > -1) {
                trs[i].style.display = "";
                found = true; // ketemu set true
            } else {
                trs[i].style.display = "none";
            }
        }
    }

    if (!found) {
        // tampilkan popup jika tidak ada hasil
        alert('Nama admin tidak ditemukan!');
    }
});

</script>


</div>
@endsection

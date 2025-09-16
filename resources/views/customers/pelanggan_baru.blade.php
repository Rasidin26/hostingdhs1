@extends('layouts.app')

@section('content')
<div class="bg-gray-900 min-h-screen p-6 text-sm text-gray-800">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Header Atas -->
<div class="bg-white rounded-xl shadow flex flex-col md:flex-row md:items-center justify-between px-6 py-4 mb-6 gap-3">
    <!-- Kiri -->
    <div class="flex items-center gap-4">
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-2 text-white text-sm font-medium px-5 py-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 shadow hover:from-blue-600 hover:to-purple-600 transition-all">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        
        <!-- Info Judul -->
        <div class="flex flex-col">
            <div class="flex items-center gap-2 text-blue-600 font-semibold">
                <i class="bi bi-person-plus-fill text-blue-500"></i>
                Pelanggan Baru August 2025
            </div>
            <div class="flex items-center gap-1 text-gray-600 text-sm mt-1">
                <i class="bi bi-wifi text-gray-500"></i> DHS
            </div>
        </div>
    </div>
</div>


    <!-- Search + Tombol Aksi -->
    <div class="bg-gray-50 rounded-xl p-4 mb-6">
        <form method="GET" action="{{ route('customers.index') }}">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian Data Pelanggan</label>
            <div class="flex flex-wrap md:flex-nowrap items-center gap-2 mb-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, nomor telepon, atau ID pelanggan..."
                    class="flex-1 border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm">
                <button type="submit" class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-4 py-2 rounded hover:from-indigo-600 hover:to-purple-600 text-sm">
                    <i class="bi bi-search"></i> CARI
                </button>
                <a href="{{ route('customers.index') }}" class="bg-white border border-gray-300 px-4 py-2 rounded hover:bg-gray-100 text-gray-600 text-sm">
                    <i class="bi bi-x"></i> RESET
                </a>
            </div>
        </form>

        <!-- Tombol Aksi -->
        <div class="flex flex-wrap gap-2">
            <a href="/customers" class="flex items-center gap-2 border border-blue-600 text-blue-600 px-4 py-2 rounded shadow-sm hover:bg-blue-50 text-sm font-semibold">
                <i class="bi bi-people-fill"></i> DAFTAR PELANGGAN
            </a>
            <a href="#" class="flex items-center gap-2 border border-green-600 text-green-600 px-4 py-2 rounded shadow-sm hover:bg-green-50 text-sm font-semibold">
                <i class="bi bi-clipboard-plus-fill"></i> PENDAFTARAN
            </a>
            <a href="#" class="flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded shadow-sm hover:bg-orange-600 text-sm font-semibold">
                <i class="bi bi-arrow-left-right"></i> MIGRASI DATA
            </a>
            <a href="#" class="flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded shadow-sm hover:bg-red-700 text-sm font-semibold">
                <i class="bi bi-tools"></i> BERSIHKAN DATABASE
            </a>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="text-white text-center p-4 rounded-xl shadow bg-gradient-to-r from-indigo-500 to-purple-600">
            <div class="text-3xl font-bold">{{ $customers->count() }}</div>
            <div class="text-sm mt-1">Total Bulan Ini</div>
        </div>
        <div class="text-center p-4 rounded-xl shadow bg-white">
            <div class="text-3xl font-bold text-green-600">{{ $customers->where('status_bayar', 'Aktif')->count() }}</div>
            <div class="text-sm mt-1 text-gray-700">Aktif</div>
        </div>
        <div class="text-center p-4 rounded-xl shadow bg-white">
            <div class="text-3xl font-bold text-yellow-500">0</div>
            <div class="text-sm mt-1 text-gray-700">Menunggu</div>
        </div>
        <div class="text-center p-4 rounded-xl shadow bg-white">
            <div class="text-3xl font-bold text-cyan-500">0</div>
            <div class="text-sm mt-1 text-gray-700">Terpasang</div>
        </div>
    </div>
<!-- Filter -->
<!-- Tombol Filter -->
<div class="flex flex-wrap gap-2 mb-4">
    <button onclick="openAreaFilter()" 
        class="flex items-center gap-1 bg-white hover:bg-gray-100 border px-3 py-1 rounded-md text-gray-700 shadow-sm text-xs">
        <i class="bi bi-geo-alt text-purple-500"></i> Filter Area
    </button>

    <button onclick="openPackageFilter()" 
        class="flex items-center gap-1 bg-white hover:bg-gray-100 border px-3 py-1 rounded-md text-gray-700 shadow-sm text-xs">
        <i class="bi bi-box-seam text-purple-500"></i> Filter Paket
    </button>
</div>

 


    <!-- CARD: Daftar Pelanggan Baru + Tabel -->
    <div class="bg-white rounded-xl shadow p-4">
        <!-- Judul -->
        <div class="text-blue-600 font-semibold text-base mb-4 flex items-center gap-2">
            <i class="bi bi-book-fill"></i> Daftar Pelanggan Baru
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm rounded-lg overflow-hidden">
                <thead class="bg-gray-100 text-left text-gray-700">
                    <tr>
                        <th class="border px-3 py-2">No.</th>
                        <th class="border px-3 py-2">Nama</th>
                        <th class="border px-3 py-2">ID Pelanggan</th>
                        <th class="border px-3 py-2">PPPoE/Hotspot</th>
                        <th class="border px-3 py-2">Paket</th>
                        <th class="border px-3 py-2">Area</th>
                        <th class="border px-3 py-2">Tgl Register</th>
                        <th class="border px-3 py-2">Status</th>
                        <th class="border px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $index => $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2 text-center">{{ $index + 1 }}</td>
                        <td class="border px-3 py-2">
                            {{ $customer->nama }}
                            <div class="text-xs text-gray-500">{{ $customer->telepon }}</div>
                        </td>
                        <td class="border px-3 py-2">{{ $customer->id }}</td>
                        <td class="border px-3 py-2">{{ $customer->koneksi }}</td>
                        <td class="border px-3 py-2">{{ $customer->paket }}</td>
                        <td class="border px-3 py-2">{{ $customer->area }}</td>
                        <td class="border px-3 py-2">{{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y') }}</td>
                        <td class="border px-3 py-2">
                            <span class="px-2 py-1 text-xs rounded font-semibold
                                @if($customer->status_bayar == 'Aktif')
                                    bg-green-100 text-green-800
                                @elseif($customer->status_bayar == 'Menunggu')
                                    bg-yellow-100 text-yellow-800
                                @elseif($customer->status_bayar == 'Terpasang')
                                    bg-cyan-100 text-cyan-800
                                @else
                                    bg-gray-100 text-gray-600
                                @endif">
                                {{ $customer->status_bayar }}
                            </span>
                        </td>
                        <td class="border px-3 py-2 text-center space-x-1">
                            <a href="{{ route('customers.show', $customer->id) }}" class="inline-block text-blue-600 hover:text-blue-800" title="Lihat">👁</a>
                            <a href="{{ route('customers.edit', $customer->id) }}" class="inline-block text-purple-600 hover:text-purple-800" title="Edit">✏</a>
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">🗑</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-gray-500">Belum ada data pelanggan baru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- END CARD -->

<script>
function openAreaFilter() {
    Swal.fire({
        title: 'Filter Berdasarkan Area',
        html: `
            <form id="areaFilterForm" action="{{ route('customers.pelanggan_baru') }}" method="GET" class="text-left">
                <div class="mb-3">
                    <label for="area_id" class="block text-sm font-medium">Pilih Area</label>
                    <select id="area_id" name="area_id" class="form-control w-full px-3 py-2 border rounded mt-1">
                        <option value="">-- Semua Area --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                                {{ $area->nama_area }} ({{ $area->customers_count }} pelanggan)
                            </option>
                        @endforeach
                    </select>
                </div>
                <small class="text-gray-500">
                    Filter pelanggan berdasarkan area. Pilih "-- Semua Area --" untuk menampilkan semua.
                </small>
            </form>
        `,
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonText: 'Terapkan Filter',
        focusConfirm: false,
        showCloseButton: true,
        customClass: {
            confirmButton: 'bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700',
            cancelButton: 'bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400',
        },
        preConfirm: () => {
            document.getElementById('areaFilterForm').submit();
        }
    });
}

function openPackageFilter() {
    Swal.fire({
        title: 'Filter Berdasarkan Paket',
        html: `
            <form id="packageFilterForm" action="{{ route('customers.pelanggan_baru') }}" method="GET" class="text-left">
                <div class="mb-3">
                    <label for="package_id" class="block text-sm font-medium">Pilih Paket</label>
                    <select id="package_id" name="package_id" class="form-control w-full px-3 py-2 border rounded mt-1">
                        <option value="">-- Semua Paket --</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->nama_paket }} ({{ $package->customers_count }} pelanggan)
                            </option>
                        @endforeach
                    </select>
                </div>
                <small class="text-gray-500">
                    Filter pelanggan berdasarkan paket langganan. Pilih "-- Semua Paket --" untuk menampilkan semua.
                </small>
            </form>
        `,
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonText: 'Terapkan Filter',
        focusConfirm: false,
        showCloseButton: true, // tombol "X" di pojok kanan atas
        customClass: {
            confirmButton: 'bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700',
            cancelButton: 'bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400',
        },
        preConfirm: () => {
            document.getElementById('packageFilterForm').submit();
        }
    });
}

</script>
</div>
@endsection

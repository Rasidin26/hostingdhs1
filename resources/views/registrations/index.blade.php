@extends('layouts.app')

@section('content')
<div class="bg-gray-900 min-h-screen p-6 text-sm text-gray-800">

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
                    <i class="bi bi-clipboard-plus-fill text-blue-500"></i>
                    Pendaftaran WiFi
                </div>
                <div class="flex items-center gap-1 text-gray-600 text-sm mt-1">
                    <i class="bi bi-wifi text-gray-500"></i> DHS
                </div>
            </div>
        </div>

        <!-- Kanan -->
        <div>
            <a href="{{ route('customers.pelanggan_baru') }}" 
               class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-full shadow-sm text-sm font-medium">
                <i class="bi bi-person-plus-fill"></i> Pelanggan Baru
            </a>
        </div>
    </div>

    <!-- Link Pendaftaran Online -->
    <div class="bg-white rounded-xl shadow p-4 mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <!-- Kiri: Icon + Info -->
        <div class="flex items-start gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100">
                <i class="bi bi-link-45deg text-blue-600 text-lg"></i>
            </div>
            <div>
                <div class="text-blue-600 font-semibold text-sm">Link Pendaftaran Online</div>
                <div class="text-gray-600 text-xs">
                    Bagikan link ini kepada calon pelanggan untuk pendaftaran pemasangan WiFi secara online.
                </div>
            </div>
        </div>

        <!-- Kanan: Input URL + Tombol Salin -->
        <div class="flex items-center gap-2 flex-1 md:flex-none">
            <input type="text" readonly 
                   id="linkPendaftaran"
                   value="https://www.salfanet.my.id/billing"
                   class="flex-1 border border-gray-300 rounded px-3 py-2 text-gray-700 text-sm focus:outline-none">
            <button type="button"
                    onclick="copyToClipboard()" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium flex items-center gap-1 shadow">
                <i class="bi bi-clipboard"></i> SALIN
            </button>
        </div>
    </div>

    <!-- Manajemen Pendaftaran -->
    <div class="bg-white rounded-xl shadow p-4 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2 text-blue-600 font-semibold text-base">
                <i class="bi bi-list-ul"></i> Manajemen Pendaftaran
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1 text-sm text-gray-600">
                    <span class="text-blue-500 font-semibold">{{ $total }}</span> Total
                </div>
                <div class="flex items-center gap-1 text-sm text-gray-600">
                    <span class="text-yellow-500 font-semibold">{{ $menunggu }}</span> Menunggu
                </div>
                <div class="flex items-center gap-1 text-sm text-gray-600">
                    <span class="text-green-600 font-semibold">{{ $disetujui }}</span> Disetujui
                </div>
            </div>
        </div>

        <!-- Search + Filter + Buttons -->
        <form method="GET" action="{{ route('registrations.index') }}" class="flex flex-wrap md:flex-nowrap items-center gap-2">
            <!-- Search -->
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari berdasarkan nama atau telepon..."
                    class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>

            <!-- Filter -->
            <select name="status" 
                class="border border-gray-300 rounded-lg px- py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 min-w-[120px]">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>

            <!-- Buttons -->
            <button type="submit" 
                class="bg-gradient-to-r from-blue-500 to-purple-500 text-white px-4 py-2 rounded-lg text-sm hover:from-blue-600 hover:to-purple-600">
                <i class="bi bi-search"></i> Cari
            </button>
            <a href="{{ route('registrations.index') }}" 
                class="bg-white border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-100">
                <i class="bi bi-x"></i> Reset
            </a>
        </form>
    </div>

    <!-- CARD: Daftar Pendaftar -->
    <div class="bg-white rounded-xl shadow p-4">
        <div class="text-blue-600 font-semibold text-base mb-4 flex items-center gap-2">
            <i class="bi bi-list-ul"></i> Daftar Pendaftaran Pemasangan Wifi 
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm rounded-lg overflow-hidden">
                <thead class="bg-gray-100 text-left text-gray-700">
                    <tr>
                        <th class="border px-3 py-2">No.</th>
                        <th class="border px-3 py-2">Nama</th>
                        <th class="border px-3 py-2">Telepon</th>
                        <th class="border px-3 py-2">Email</th>
                        <th class="border px-3 py-2">Paket</th>
                        <th class="border px-3 py-2">Harga</th>
                        <th class="border px-3 py-2">Status</th>
                        <th class="border px-3 py-2">Tanggal</th>
                        <th class="border px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($registrations as $index => $reg)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2 text-center">{{ $index + 1 }}</td>
                        <td class="border px-3 py-2">{{ $reg->nama }}</td>
                        <td class="border px-3 py-2">{{ $reg->telepon }}</td>
                        <td class="border px-3 py-2">{{ $reg->email }}</td>
                        <td class="border px-3 py-2">{{ $reg->paket }}</td>
                        <td class="border px-3 py-2">{{ number_format($reg->harga) }}</td>
                        <td class="border px-3 py-2">
                            <span class="px-2 py-1 text-xs rounded font-semibold
                                @if($reg->status == 'menunggu')
                                    bg-yellow-100 text-yellow-800
                                @elseif($reg->status == 'disetujui')
                                    bg-green-100 text-green-800
                                @elseif($reg->status == 'ditolak')
                                    bg-red-100 text-red-800
                                @else
                                    bg-gray-100 text-gray-600
                                @endif">
                                {{ ucfirst($reg->status) }}
                            </span>
                        </td>
                        <td class="border px-3 py-2">{{ $reg->created_at->format('d/m/Y') }}</td>
                        <td class="border px-3 py-2 text-center space-x-1">
                            <form action="{{ route('registrations.approve', $reg) }}" method="POST" class="inline-block">@csrf
                                <button class="text-green-600 hover:text-green-800" title="Setujui">✔</button>
                            </form>
                            <form action="{{ route('registrations.reject', $reg) }}" method="POST" class="inline-block">@csrf
                                <button class="text-red-600 hover:text-red-800" title="Tolak">✖</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-gray-500">Belum ada data pendaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
function copyToClipboard() {
    const link = document.getElementById("linkPendaftaran").value;
    navigator.clipboard.writeText(link).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Link berhasil disalin!',
            timer: 2000,
            showConfirmButton: false,
            showCloseButton: true
        });
    }).catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Tidak dapat menyalin link',
            showCloseButton: true
        });
        console.error("Gagal menyalin: ", err);
    });
}

// ✅ Amankan data dari Blade agar tidak error
const isSearch = JSON.parse(`{!! json_encode(request('search') || request('status')) !!}`);
const isEmpty = JSON.parse(`{!! json_encode($registrations->isEmpty()) !!}`);

if (isSearch && isEmpty) {
    Swal.fire({
        icon: 'warning',
        title: 'Tidak ditemukan',
        text: 'Hasil pencarian tidak ditemukan!',
        timer: 2500,
        showConfirmButton: false,
        showCloseButton: true
    });

    // Optional: fokuskan ke input search
    document.querySelector("input[name='search']").focus();
}
</script>

@endsection

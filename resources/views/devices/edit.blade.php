@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-10 px-4 text-white">
    <div class="max-w-6xl mx-auto">

        <!-- Judul Halaman -->
        <h1 class="text-3xl font-bold mb-8 text-white">Edit Perangkat</h1>

        {{-- Notifikasi Sukses atau Error --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-800 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('devices.update', $device->id) }}" method="POST" class="bg-white text-gray-800 shadow-lg rounded-xl p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Informasi Perangkat -->
                <div>
                    <div class="flex items-center gap-2 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7l7 5-7 5z" />
                        </svg>
                        <h3 class="text-lg font-bold">Informasi Perangkat</h3>
                    </div>

                    <div class="mb-5">
                        <label class="block font-medium mb-1">Nama Perangkat *</label>
                        <input type="text" name="name" value="{{ old('name', $device->name) }}"
                               class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-5">
                        <label class="block font-medium mb-1">Nama DNS *</label>
                        <input type="text" name="dns_name" value="{{ old('dns_name', $device->dns_name) }}"
                               placeholder="Contoh: hotspot.domainanda.net"
                               class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="mb-5">
                        <label class="block font-medium mb-1">Alamat IP / Domain *</label>
                        <input type="text" name="ip_address" value="{{ old('ip_address', $device->ip_address) }}"
                               class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <small class="text-gray-600">Gunakan IP publik atau VPN. Jangan gunakan 192.168.x.x.</small>
                    </div>
                </div>

                <!-- Pengaturan Koneksi -->
                <div>
                    <div class="flex items-center gap-2 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-6 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12h.01M12 12h.01M9 12h.01M4 6h16M4 18h16M4 6v12" />
                        </svg>
                        <h3 class="text-lg font-bold">Pengaturan Koneksi</h3>
                    </div>

                    <div class="mb-5">
                        <label class="block font-medium mb-1">Port API *</label>
                        <input type="number" name="api_port" value="{{ old('api_port', $device->api_port ?? 8728) }}"
                               class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <small class="text-gray-600">Port default: 8728</small>
                    </div>

                    <div class="mb-5">
                        <label class="block font-medium mb-1">Nama Pengguna *</label>
                        <input type="text" name="username" value="{{ old('username', $device->username) }}"
                               class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-5">
                        <label class="block font-medium mb-1">Kata Sandi</label>
                        <input type="password" name="password"
                               class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="Biarkan kosong jika tidak ingin diubah">
                        <small class="text-gray-600">Kosongkan jika tidak ingin mengubah kata sandi.</small>
                    </div>

                    <!-- Tombol Uji Koneksi -->
                    <div class="flex justify-between items-center px-4 py-3 border-t border-gray-100 mt-5">
                        <a href="{{ route('devices.test', $device->id) }}" 
                           onclick="scrollToPerangkat()" 
                           class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M4 6h16M4 10h16M4 14h8m-8 4h8" />
                            </svg>
                            Uji Koneksi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end mt-10 space-x-4">
                <a href="{{ route('dashboard.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">Batal</a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-semibold">
                    Perbarui Perangkat
                </button>
            </div>

            <!-- Zona Berbahaya -->
            <div class="mt-12 border-t pt-6">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-4 rounded-md">
                    <div class="flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.054 0 1.502-1.27.732-2.01L13.732 4.99c-.77-.74-2.025-.74-2.795 0L3.34 16.99c-.77.74-.322 2.01.732 2.01z"/>
                        </svg>
                        <h4 class="font-bold">Zona Berbahaya</h4>
                    </div>
                    <p class="text-sm mb-4">Menghapus perangkat akan menghilangkan semua data dan konfigurasi yang terkait. Tindakan ini tidak dapat dibatalkan.</p>
                    <form action="{{ route('devices.destroy', $device->id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus perangkat ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-semibold">
                            Hapus Perangkat
                        </button>
                    </form>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

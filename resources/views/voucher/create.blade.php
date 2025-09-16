@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">

    {{-- HEADER UNGU --}}
    <div class="rounded-t-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-5 shadow">
        <h2 class="text-xl font-bold flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8.53 16.11a4.48 4.48 0 016.94 0M5.14 13.15a9 9 0 0113.72 0M1.75 10.18a13.5 13.5 0 0120.5 0M12 20h.01"/>
            </svg>
            <span>Buat Pengguna Hotspot</span>
        </h2>
        <p class="text-sm text-white opacity-90">Generate voucher dan user account untuk hotspot</p>
    </div>

    {{-- FORM --}}
    <div class="bg-white rounded-b-xl shadow p-6 border border-t-0">
        <form action="{{ route('voucher.store') }}" method="POST" class="space-y-8">
            @csrf

            {{-- Konfigurasi Dasar --}}
            <div>
                <h3 class="text-base font-semibold mb-3 text-gray-800 flex items-center space-x-2">
                     <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m4 4v-8" />
                    </svg>
                    <span>Konfigurasi Dasar</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Sales --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sales <span class="text-red-500">*</span></label>
                        <select name="sales_id" required class="w-full mt-1 bg-white text-gray-800 border border-gray-300 rounded px-3 py-2 focus:ring-purple-600 focus:border-purple-600">
                            <option value="">Pilih Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}">{{ $s->id }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jumlah --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" value="1" required
                               class="w-full mt-1 text-gray-800 border-gray-300 rounded px-3 py-2 focus:ring-purple-600 focus:border-purple-600">
                        <p class="text-xs text-gray-500 mt-1">Maksimal 25.000 pengguna per batch</p>
                    </div>

                    {{-- Profil --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Profil Hotspot <span class="text-red-500">*</span></label>
<select name="profile_id" id="profile_id" required
    class="w-full mt-1 bg-white text-gray-800 border border-gray-300 rounded px-3 py-2 focus:ring-purple-600 focus:border-purple-600">
    <option value="">Pilih Profil</option>
    @foreach($profiles as $p)
        <option value="{{ $p->id }}" data-harga="{{ $p->harga_jual }}">
            {{ $p->nama_profil }}
        </option>
    @endforeach
</select>

{{-- Input hidden untuk price --}}
<input type="hidden" name="price" id="price">

                    </div>

                    {{-- Tipe Pengguna --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe Pengguna <span class="text-red-500">*</span></label>
                        <select name="tipe_pengguna" required class="w-full mt-1 bg-white text-gray-800 border border-gray-300 rounded px-3 py-2 focus:ring-purple-600 focus:border-purple-600">
                            <option value="">Pilih Tipe</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Konfigurasi Username --}}
            <div>
                <h3 class="text-base font-semibold mb-3 text-gray-800 flex items-center space-x-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m4 4v-8" />
                    </svg>
                    <span>Konfigurasi Username</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Awalan Username</label>
                        <input type="text" name="awalan_username" placeholder="Contoh: WIFI, USER, dll"
                               class="w-full mt-1 text-gray-800 border-gray-300 rounded px-3 py-2 focus:ring-purple-600 focus:border-purple-600">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menghasilkan tanpa awalan</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe Karakter <span class="text-red-500">*</span></label>
                        <select name="tipe_karakter" required class="w-full mt-1 bg-white text-gray-800 border border-gray-300 rounded px-3 py-2 focus:ring-purple-600 focus:border-purple-600">
                            <option value="huruf">Huruf Besar [A-Z]</option>
                            <option value="angka">Angka [0-9]</option>
                            <option value="campuran">Campuran</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Batas Penggunaan --}}
            <div>
                <h3 class="text-base font-semibold mb-3 text-gray-800 flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 6v6l4 2m4-2a8 8 0 11-16 0 8 8 0 0116 0z" />
                    </svg>
                    <span>Batas Penggunaan</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Batas Waktu</label>
                        <input type="text" name="batas_waktu" placeholder="Contoh: 1h, 30m, 1d"
                               class="w-full mt-1 text-gray-800 border-gray-300 rounded px-3 py-2 focus:ring-purple-600 focus:border-purple-600">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk tidak terbatas</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Batas Kuota</label>
                        <input type="text" name="batas_kuota" placeholder="Contoh: 1G, 500M, 2000000"
                               class="w-full mt-1 text-gray-800 border-gray-300 rounded px-3 py-2 focus:ring-purple-600 focus:border-purple-600">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk tidak terbatas</p>
                    </div>
                </div>
            </div>

            {{-- TOMBOL --}}
            <div class="flex flex-wrap items-center gap-4">
                <button type="submit"
                        class="flex items-center bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded font-semibold shadow space-x-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Buat Pengguna</span>
                </button>

                <a href="/hotspot/users/index" class="flex items-center text-purple-600 hover:underline font-medium space-x-2 px-3 py-2 bg-purple-100 hover:bg-purple-200 rounded-md transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span>Lihat Daftar Pengguna</span>
                </a>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mt-4 text-green-600 font-medium">{{ session('success') }}</div>
            @endif
        </form>
    </div>
</div>
@endsection
<script>
    document.getElementById('profile_id').addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        let harga = selectedOption.getAttribute('data-harga');
        document.getElementById('price').value = harga;
    });
</script>

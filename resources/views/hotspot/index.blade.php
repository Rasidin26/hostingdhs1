@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header atas -->
    <div class="flex items-center justify-between mb-6">
        <!-- Kiri: Tombol Kembali dan Judul -->
        <div class="flex items-center space-x-6">
            <!-- Tombol Kembali -->
            <a href="{{ route('dashboard.index') }}"
               class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold px-5 py-2.5 rounded shadow inline-flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali ke Dashboard</span>
            </a>

            <!-- Judul dan Badge -->
            <div class="flex items-start space-x-2">
                <!-- Icon Hotspot -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mt-0.5 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M14.154 12.462h4.077v-1h-4.077v1zm0-2.77h4.077v-1h-4.077v1zm-8.385 5.616h6.616v-.166q0-.875-.88-1.355t-2.428-.48-2.429.48-.879 1.355v.166zm3.308-3.616q.633 0 1.066-.433.434-.434.434-1.067t-.434-1.067q-.433-.433-1.066-.433t-1.066.433q-.434.434-.434 1.067t.434 1.067q.433.433 1.066.433zm-6.693 7.538q-.691 0-1.153-.462T3 17.384V6.616q0-.691.463-1.154T4.616 5h14.77q.69 0 1.153.463T21 6.616v10.769q0 .69-.463 1.153t-1.154.462z"/>
                </svg>
                <div class="flex flex-col leading-tight">
                    <span class="text-white text-base font-semibold text-left">Profil Hotspot</span>
                    <span class="bg-gray-200 text-gray-800 text-xs px-2 py-0.5 rounded-full font-medium w-fit mt-0.5">DHS</span>
                </div>
            </div>
        </div>

<!-- Tombol Add Profile & Sinkronkan -->
<div class="flex items-center space-x-2">
    <!-- Add Profile -->
    <a href="{{ route('hotspot.create') }}"
       class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-3 py-2 rounded shadow text-sm">
        <!-- Icon Plus -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span>Add Profile</span>
    </a>

    <!-- Sinkronkan -->
    <a href="{{ route('hotspot.sync') }}" 
       class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-3 py-2 rounded shadow text-sm">
        <!-- Icon Refresh -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M4 4v6h6M20 20v-6h-6M5 19a9 9 0 0114-14l1 1" />
        </svg>
        <span>Sinkronkan</span>
    </a>
</div>


    </div>

    <!-- Wrapper Card -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 shadow-md rounded-lg mb-4">
        <!-- Header Card: Title -->
        <div class="p-4 border-b border-white/20 flex items-center justify-between text-white">
            <div class="flex items-center space-x-2">
                <!-- Icon Putih -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12c1.93 0 3.5-1.57 3.5-3.5S10.93 5 9 5 5.5 6.57 5.5 8.5 7.07 12 9 12zm0-5c.83 0 1.5.67 1.5 1.5S9.83 10 9 10s-1.5-.67-1.5-1.5S8.17 7 9 7zm0 6.75C6.66 13.75 2 14.92 2 17.25V18c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-.75c0-2.33-4.66-3.5-7-3.5zm-4.66 1.25c.84-.58 2.87-1.25 4.66-1.25s3.82.67 4.66 1.25H4.34zM15 12c1.93 0 3.5-1.57 3.5-3.5S16.93 5 15 5c-.54 0-1.04.13-1.5.35.63.89 1 1.98 1 3.15s-.37 2.26-1 3.15c.46.22.96.35 1.5.35zm0 1c-2.34 0-7 1.17-7 3.5V18c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-.75c0-2.02-3.5-3.17-5.96-3.44C15.96 13.14 16.76 12 18 12z"/>
                </svg>
                <h2 class="text-lg font-semibold">Profile Management</h2>
            </div>
            <div class="flex items-center space-x-2 text-sm">
                <span class="bg-white text-purple-700 px-3 py-0.5 rounded-full font-semibold text-xs shadow">
                    {{ $profiles->count() }} Profiles
                </span>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-b-lg overflow-x-auto">
            <table class="w-full table-auto text-sm border border-gray-300">
                <thead class="bg-gray-100 text-gray-700 text-left">
                    <tr>
                        <th class="p-2 border">No</th>
                        <th class="p-2 border">Nama Profil</th>
                        <th class="p-2 border">Batas Kecepatan</th>
                        <th class="p-2 border">Masa Berlaku</th>
                        <th class="p-2 border">Parent Queue</th>
                        <th class="p-2 border">Shared Users</th>
                        <th class="p-2 border">Harga</th>
                        <th class="p-2 border">Harga Jual</th>
                        <th class="p-2 border">Status</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @forelse($profiles as $i => $p)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2 border text-center">{{ $i + 1 }}</td>
                        <td class="p-2 border">{{ $p->nama_profil }}</td>
                        <td class="p-2 border">{{ $p->batas_kecepatan }}</td>
                        <td class="p-2 border text-center">{{ $p->masa_berlaku }}</td>
                        <td class="p-2 border">{{ $p->parent_queue }}</td>
                        <td class="p-2 border text-center">{{ $p->shared_users }}</td>
                        <td class="p-2 border text-green-600">Rp {{ number_format($p->harga_modal, 0, ',', '.') }}</td>
                        <td class="p-2 border text-blue-600">Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                        <td class="p-2 border text-center">
                            <span class="{{ $p->status == 'aktif' ? 'bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold' : 'bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
 n
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-gray-500">Belum ada profil yang tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

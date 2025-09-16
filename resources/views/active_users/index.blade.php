@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <!-- Kiri: Tombol kembali + Judul -->
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

            <!-- Judul -->
            <div class="flex items-start space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mt-0.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.154 12.462h4.077v-1h-4.077v1zm0-2.77h4.077v-1h-4.077v1zm-8.385 5.616h6.616v-.166q0-.875-.88-1.355t-2.428-.48-2.429.48-.879 1.355v.166zm3.308-3.616q.633 0 1.066-.433.434-.434.434-1.067t-.434-1.067q-.433-.433-1.066-.433t-1.066.433q-.434.434-.434 1.067t.434 1.067q.433.433 1.066.433zm-6.693 7.538q-.691 0-1.153-.462T3 17.384V6.616q0-.691.463-1.154T4.616 5h14.77q.69 0 1.153.463T21 6.616v10.769q0 .69-.463 1.153t-1.154.462z"/>
                </svg>
                <div class="flex flex-col leading-tight">
                    <span class="text-white text-base font-semibold">Pengguna Aktif</span>
                    <span class="bg-gray-200 text-gray-800 text-xs px-2 py-0.5 rounded-full font-medium w-fit mt-0.5">
                        {{ $activeUsers->count() }} Online
                    </span>
                </div>
            </div>
        </div>

        <!-- Kanan: Tombol Refresh -->
        <button onclick="location.reload();"
            class="flex items-center space-x-2 bg-gradient-to-r from-green-500 to-green-600 
                   hover:from-green-600 hover:to-green-700 text-white font-semibold 
                   px-4 py-2 rounded shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 2a1 1 0 00-1 1v5a1 1 0 001 1h5a1 1 0 100-2H6.41A7.963 7.963 0 0110 4a8 8 0 11-7.938 9.09 1 1 0 10-1.98.2A10 10 0 1010 2H4z" clip-rule="evenodd"/>
            </svg>
            <span>Refresh</span>
        </button>
    </div>

    <!-- Card -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 shadow-md rounded-lg mb-4">
        <!-- Header Card -->
        <div class="p-4 border-b border-white/20 flex items-center justify-between text-white">
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12c1.93 0 3.5-1.57 3.5-3.5S10.93 5 9 5 5.5 6.57 5.5 8.5 7.07 12 9 12zm0-5c.83 0 1.5.67 1.5 1.5S9.83 10 9 10s-1.5-.67-1.5-1.5S8.17 7 9 7zm0 6.75C6.66 13.75 2 14.92 2 17.25V18c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-.75c0-2.33-4.66-3.5-7-3.5zm-4.66 1.25c.84-.58 2.87-1.25 4.66-1.25s3.82.67 4.66 1.25H4.34zM15 12c1.93 0 3.5-1.57 3.5-3.5S16.93 5 15 5c-.54 0-1.04.13-1.5.35.63.89 1 1.98 1 3.15s-.37 2.26-1 3.15c.46.22.96.35 1.5.35zm0 1c-2.34 0-7 1.17-7 3.5V18c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-.75c0-2.02-3.5-3.17-5.96-3.44C15.96 13.14 16.76 12 18 12z"/>
                </svg>
                <h2 class="text-lg font-semibold">Data Pengguna Aktif</h2>
            </div>
            <span class="bg-white text-purple-700 px-3 py-0.5 rounded-full font-semibold text-xs shadow">
                Monitoring Live
            </span>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-b-lg overflow-x-auto">
            <table class="w-full table-auto text-sm border border-gray-300">
                <thead class="bg-gray-100 text-gray-700 text-left">
                    <tr>
                        <th class="p-2 border">Pengguna</th>
                        <th class="p-2 border">Profil</th>
                        <th class="p-2 border">Reseller</th>
                        <th class="p-2 border">Perangkat</th>
                        <th class="p-2 border">IP Address</th>
                        <th class="p-2 border">Penggunaan</th>
                        <th class="p-2 border">Data Masuk</th>
                        <th class="p-2 border">Data Keluar</th>
                        <th class="p-2 border">Waktu Aktif</th>
                        <th class="p-2 border">Mulai</th>
                        <th class="p-2 border">Berakhir</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @foreach($activeUsers as $user)
                        @php
                            $loginTime = $user->login_time ? \Carbon\Carbon::parse($user->login_time) : null;
                            $endTime = $loginTime ? $loginTime->copy()->addHours(7) : null;

                            if($endTime && now()->greaterThanOrEqualTo($endTime)) continue;

                            $totalUsage = $user->download + $user->upload;
                            $usagePercent = $totalUsage > 0 ? min(100, ($totalUsage / 500) * 100) : 0;
                            $progressColor = $usagePercent < 50 ? 'bg-green-500' : ($usagePercent < 80 ? 'bg-yellow-500' : 'bg-red-500');

                            $activeMinutes = $loginTime ? $loginTime->diffInMinutes(now()) : 0;
                            $activeFormatted = $activeMinutes >= 60 
                                ? floor($activeMinutes / 60) . "j " . ($activeMinutes % 60) . "m"
                                : $activeMinutes . "m";
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-2 border">{{ $user->username }}</td>
                            <td class="p-2 border">
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $user->profile->nama_profil ?? '-' }}
                                </span>
                            </td>
                            <td class="p-2 border">
                                {{ $user->sales->id ?? $user->sales->user->name ?? '-' }}
                            </td>
                            <td class="p-2 border">{{ $user->device_name ?? 'Unknown' }}</td>
                            <td class="p-2 border text-red-500 font-medium">{{ $user->ip_address ?? '-' }}</td>
                            <td class="p-2 border w-40">
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                                    <div class="{{ $progressColor }} h-2 rounded-full" style="width: {{ $usagePercent }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600">{{ number_format($totalUsage, 2) }} MB</span>
                            </td>
                            <td class="p-2 border text-green-600">{{ number_format($user->download, 2) }} MB</td>
                            <td class="p-2 border text-indigo-600">{{ number_format($user->upload, 2) }} MB</td>
                            <td class="p-2 border">{{ $activeFormatted }}</td>
                            <td class="p-2 border">{{ $loginTime ? $loginTime->format('Y-m-d H:i:s') : '-' }}</td>
                            <td class="p-2 border">{{ $endTime ? $endTime->format('Y-m-d H:i:s') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

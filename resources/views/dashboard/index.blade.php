@extends('layouts.app')

@section('head')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

    {{-- Informasi Keuangan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Pendapatan Voucher -->
        <div class="bg-gray-800 p-6 rounded-lg shadow-md">
            <h4 class="text-gray-400 text-sm">PENDAPATAN VOUCHER</h4>
            <p class="text-2xl font-bold mt-2 {{ $pendapatanVoucher == 0 ? 'text-gray-100' : 'text-white' }}">
                Rp {{ number_format($pendapatanVoucher, 0, ',', '.') }}
            </p>
            <p class="text-green-400 text-xs mt-1">
                Online: Rp {{ number_format($voucherOnlineMonth, 0, ',', '.') }} |
                Offline: Rp {{ number_format($voucherOfflineMonth, 0, ',', '.') }}
            </p>
        </div>

        <!-- Pembayaran Billing -->
        <div class="bg-gray-800 p-6 rounded-lg shadow-md">
            <h4 class="text-gray-400 text-sm">PEMBAYARAN BILLING</h4>
            <p class="text-2xl font-bold mt-2 {{ $pembayaranBilling == 0 ? 'text-gray-100' : 'text-white' }}">
                Rp {{ number_format($pembayaranBilling, 0, ',', '.') }}
            </p>
            <p class="text-gray-400 text-xs mt-1">Pembayaran tagihan customer</p>
            <span class="mt-3 inline-block bg-blue-200 text-blue-800 text-xs px-2 py-1 rounded">Auto Collection</span>
        </div>
    </div>

    {{-- Ringkasan Tagihan Outstanding --}}
    <div class="bg-white rounded-lg shadow-md border border-yellow-400 mb-8 relative">
        <div class="flex items-center bg-yellow-50 px-4 py-2 rounded-t-lg border-b border-yellow-200">
            <i class="fa-solid fa-triangle-exclamation text-yellow-600 mr-2"></i>
            <h3 class="text-base font-semibold text-gray-800">Ringkasan Tagihan Outstanding</h3>
        </div>

        <div class="absolute top-2 right-4 flex gap-2">
            <span class="bg-yellow-400 text-black text-xs font-semibold px-2 py-1 rounded">
                {{ $customerOverdue }} customer overdue
            </span>
            <span class="bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded">
                Data Quality: 100%
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 text-center gap-4 px-4 py-5">
            <div>
                <h4 class="text-gray-600 text-sm">TOTAL TAGIHAN</h4>
                <p class="font-bold text-xl {{ $totalTagihan == 0 ? 'text-gray-600' : 'text-red-600' }}">
                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                </p>
            </div>
            <div>
                <h4 class="text-gray-600 text-sm">CUSTOMER OVERDUE</h4>
                <p class="font-bold text-xl {{ $customerOverdue == 0 ? 'text-gray-600' : 'text-yellow-500' }}">
                    {{ $customerOverdue }} dari {{ $totalCustomer }}
                </p>
            </div>
            <div>
                <h4 class="text-gray-600 text-sm">KRITIS (>3 BULAN)</h4>
                <p class="font-bold text-xl {{ $customerKritis == 0 ? 'text-gray-600' : 'text-red-600' }}">
                    {{ $customerKritis }} customer
                </p>
            </div>
            <div>
                <h4 class="text-gray-600 text-sm">RATA-RATA TUNGGAKAN</h4>
                <p class="font-bold text-xl {{ $rataRataTunggakan == 0 ? 'text-gray-600' : 'text-blue-500' }}">
                    {{ $rataRataTunggakan }} bulan
                </p>
            </div>
        </div>
    </div>

    {{-- Statistik Customer --}}
    {{-- ... (biarkan tetap sama, sudah sesuai controller) ... --}}

    {{-- ✅ Laporan Penjualan --}}
    <div class="mt-6 mb-8">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-white bg-gray-900 px-4 py-2 rounded shadow">
                Laporan Penjualan
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Penjualan Hari Ini --}}
            <div class="bg-gray-800 rounded-xl shadow border border-yellow-400 hover:shadow-lg transition">
                <div class="flex gap-3 items-start p-5">
                    <div class="h-10 w-10 rounded-lg bg-yellow-200 flex items-center justify-center text-yellow-700 shadow">
                        <i class="fa-regular fa-calendar"></i>
                    </div>
                    <div class="flex-1 text-white">
                        <p class="text-xs font-semibold text-yellow-300 tracking-wide">PENJUALAN HARI INI</p>
                        <p class="text-2xl font-bold mt-1">
                            Rp {{ number_format($salesTodayAmount, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-300">{{ $salesTodayCount ?? 0 }} transaksi</p>

                        <div class="mt-4">
                            <div class="h-2 w-full bg-gray-600 rounded">
                                <div class="h-2 rounded bg-yellow-400"
                                     style="width: {{ isset($todayPercent) ? (int)$todayPercent : 0 }}%;"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4 text-gray-300">
                            <div class="text-xs flex items-center gap-2">
                                <i class="fa-regular fa-clock"></i>
                                Update terakhir: {{ $lastUpdate ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Penjualan Bulan Ini --}}
            <div class="bg-gray-800 rounded-xl shadow border border-green-500 hover:shadow-lg transition">
                <div class="flex gap-3 items-start p-5">
                    <div class="h-10 w-10 rounded-lg bg-emerald-200 flex items-center justify-center text-emerald-700 shadow">
                        <i class="fa-regular fa-calendar-check"></i>
                    </div>
                    <div class="flex-1 text-white">
                        <p class="text-xs font-semibold text-emerald-300 tracking-wide">PENJUALAN BULAN INI</p>
                        <p class="text-2xl font-bold mt-1">
                            Rp {{ number_format($salesMonthAmount, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-300">{{ $salesMonthCount ?? 0 }} transaksi</p>

                        <div class="mt-4">
                            <div class="h-2 w-full bg-gray-600 rounded">
                                <div class="h-2 rounded bg-emerald-500"
                                     style="width: {{ isset($monthPercent) ? (int)$monthPercent : 0 }}%;"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4 text-gray-300">
                            <div class="text-xs flex items-center gap-2">
                                <i class="fa-regular fa-calendar"></i>
                                Periode: {{ $currentPeriod ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- Perangkat Saya --}}
<div class="bg-gray-800 bg-opacity-80 rounded-xl shadow-md p-5 mb-8 border border-purple-500">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-lg font-semibold text-white flex items-center gap-2">
            <i class="fa-solid fa-network-wired text-purple-400"></i>
            Perangkat Saya
        </h4>
        <a href="{{ route('devices.create') }}"
           class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-md shadow text-sm transition">
            <i class="fa-solid fa-plus text-xs"></i>
            Tambah
        </a>
    </div>

    <!-- Isi -->
    @if($devices->isEmpty())
        <div class="text-center py-6 text-gray-400 text-sm">
            <i class="fa-solid fa-circle-info text-gray-500 mb-1"></i><br>
            Belum ada perangkat yang ditambahkan.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($devices as $device)
                <div class="bg-gray-900 bg-opacity-70 border border-gray-700 rounded-lg p-4 shadow-sm hover:shadow-md hover:scale-[1.01] transition flex flex-col justify-between">
                    
                    <!-- Nama & Status -->
                    <div class="flex items-center justify-between mb-2">
                        <h5 class="text-sm font-semibold text-white">{{ $device->name }}</h5>
@if(session('mikrotik_connected') && session('mikrotik_device_id') === $device->id)
    <span class="text-[10px] font-medium bg-green-600/20 text-green-400 px-2 py-0.5 rounded-full">
        Online
    </span>
@else
    <span class="text-[10px] font-medium bg-red-600/20 text-red-400 px-2 py-0.5 rounded-full">
        Offline
    </span>
@endif


                    </div>

                    <!-- IP -->
                    <p class="text-xs text-gray-400 flex items-center gap-1 mb-3">
                        <i class="fa-solid fa-wifi text-purple-400 text-xs"></i> {{ $device->ip_address }}
                    </p>

                    <!-- Aksi -->
                    <div class="flex justify-end">
    @if(session('mikrotik_connected') && session('mikrotik_device_id') == $device->id)
    <form action="{{ route('devices.mikrotik.disconnect') }}" method="POST">
        @csrf
        <button type="submit"
            class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded-md shadow flex items-center gap-1 transition">
            <i class="fa-solid fa-power-off text-[11px]"></i> Logout
        </button>
    </form>
@else
    <a href="{{ route('devices.mikrotik.login', $device) }}"
       class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded-md shadow flex items-center gap-1 transition">
        <i class="fa-solid fa-right-to-bracket"></i> Login
    </a>
@endif

</div>

                </div>
            @endforeach
        </div>
    @endif
</div>



</div>
@endsection

@push('scripts')
<script>
    function scrollToPerangkat() {
        setTimeout(() => {
            const el = document.getElementById('perangkat');
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 300);
    }
</script>
@endpush

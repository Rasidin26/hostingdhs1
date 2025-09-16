@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-black text-white px-4"
     style="background: url('/images/bg-hotspot.jpg') no-repeat center center / cover;">
    
    <!-- Gambar Voucher -->
    <img src="/images/voucher-banner.png" alt="Voucher Wifi" class="w-80 rounded-lg shadow-lg mb-4">

    <!-- Tab Menu -->
    <div class="flex mb-2 bg-gray-700 rounded-lg overflow-hidden w-80">
        <button class="flex-1 py-2 bg-red-600 text-white font-semibold">Voucher</button>
        <button class="flex-1 py-2 bg-gray-600 text-gray-300">Member</button>
    </div>

    <!-- Input Voucher -->
    <div class="w-80 mb-4">
        <form method="POST" action="{{ route('voucher.login') }}">
            @csrf
            <input type="text" name="username" placeholder="Kode Voucher"
                   class="w-full px-4 py-2 rounded bg-black/70 text-white text-center placeholder-gray-300 mb-3 focus:outline-none focus:ring-2 focus:ring-red-400">
            
            <button type="submit"
                    class="w-full bg-yellow-500 text-black font-bold py-2 rounded shadow hover:bg-yellow-600">
                MASUK
            </button>
        </form>
    </div>

    <!-- Logo DHS -->
    <div class="w-32 mb-4">
        <img src="/images/logo.png.png" alt="DHS Logo" class="mx-auto">
    </div>

    <!-- Paket Internet -->
    <div class="bg-gray-800 rounded-lg px-4 py-3 w-80 text-sm">
        <ul class="space-y-1 text-gray-100">
            <li>• 150.000 / 20 Mbps</li>
            <li>• 240.000 / 30 Mbps</li>
            <li>• 300.000 / 40 Mbps</li>
        </ul>
        <p class="mt-3 text-center">
            Mau pasang di rumah? Hubungi Kami:<br>
            <span class="font-semibold">0881-0231-87630</span>
        </p>
        <p class="text-center text-xs mt-2">Support by PT. SAKTI WIJAYA NETWORK</p>
    </div>
</div>
@endsection

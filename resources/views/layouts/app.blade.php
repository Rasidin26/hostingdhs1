<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tempat CSS tambahan per halaman -->
    @stack('styles')

    <style>
        aside::-webkit-scrollbar { width: 6px; }
        aside::-webkit-scrollbar-track { background: transparent; }
        aside::-webkit-scrollbar-thumb { background-color: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        aside:hover::-webkit-scrollbar-thumb { background-color: rgba(255, 255, 255, 0.3); }
    </style>
</head>
<body class="bg-gray-900 text-white"
      x-data="{ 
          openHotspot: JSON.parse(localStorage.getItem('openHotspot')) || false,
          openCustomer: JSON.parse(localStorage.getItem('openCustomer')) || false,              
          openBilling: JSON.parse(localStorage.getItem('openBilling')) || false
      }"
      x-init="
          $watch('openHotspot', val => localStorage.setItem('openHotspot', JSON.stringify(val)));
          $watch('openCustomer', val => localStorage.setItem('openCustomer', JSON.stringify(val)));
          $watch('openBilling', val => localStorage.setItem('openBilling', JSON.stringify(val)));
      ">

@php
    // Helper kelas aktif
    function isActivePath($pattern){ return request()->is($pattern); }
    function isActiveRoute($name){ return request()->routeIs($name); }

    // Flag koneksi mikrotik dari session
    $isMikrotikConnected = session('mikrotik_connected', false);
@endphp

<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 h-screen w-64 bg-gray-800 border-r border-gray-700 overflow-y-auto">
    <div class="flex items-center justify-center py-6 border-b border-gray-700">
        <img src="{{ asset('images/logo.png.png') }}" class="h-20 w-20 rounded-full" alt="Logo">
    </div>

    <nav class="p-4 space-y-4 text-sm">
        <!-- Group: Dasbor Router -->
        <div>
            <h2 class="text-gray-400 uppercase text-xs font-bold mb-2">Dasbor Router</h2>
     <a href="{{ route('dashboard.index') }}"
               class="block py-2 px-4 rounded {{ request()->routeIs('dashboard.index') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                Dashboard Laravel
            </a>
        <div class="{{ !$isMikrotikConnected ? 'pointer-events-none opacity-50' : '' }}">


            <a href="{{ route('dashboard') }}"
               class="block py-2 px-4 rounded {{ isActiveRoute('dashboard') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                Dasbor Mikrotik
            </a>


            <a href="{{ url('/peta') }}"
               class="block py-2 px-4 rounded {{ request()->is('peta*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                Peta ODC/ODP
            </a>
        </div>

        <!-- Group: Manajemen Hotspot -->
        <div class="{{ !$isMikrotikConnected ? 'pointer-events-none opacity-50' : '' }}">
            <h2 class="text-gray-400 uppercase text-xs font-bold mb-2">Manajemen Jaringan</h2>
            <button @click="openHotspot = !openHotspot" 
                    class="w-full flex justify-between items-center py-2 px-4 rounded hover:bg-gray-700 focus:outline-none">
                <span class="flex items-center gap-2">
                    <i class="bi bi-wifi"></i>
                    Manajemen Hotspot
                </span>
                <svg class="w-4 h-4 transform transition-transform duration-200"
                     :class="{ 'rotate-180': openHotspot }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="openHotspot" class="ml-6 mt-2 space-y-1" x-transition>
                <a href="/hotspot/users/index"
                   class="block py-1 px-2 rounded {{ isActivePath('hotspot/users*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                    Daftar Pengguna
                </a>
                <a href="/pengguna-aktif"
                   class="block py-1 px-2 rounded {{ isActivePath('pengguna-aktif') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                    Pengguna Aktif
                </a>
                <a href="/voucher/create"
                   class="block py-1 px-2 rounded {{ isActivePath('voucher/create') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                    Buat Voucher
                </a>
                <a href="/hotspot"
                   class="block py-1 px-2 rounded {{ isActivePath('hotspot') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                    Profil Hotspot
                </a>
                <a href="/template-voucher"
                   class="block py-1 px-2 rounded {{ isActivePath('template-voucher*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                    Template Voucher
                </a>
            </div>
        </div>

        <!-- Group: Pelanggan & Billing -->
        <div class="{{ !$isMikrotikConnected ? 'pointer-events-none opacity-50' : '' }}">
            <h2 class="text-gray-400 uppercase text-xs font-bold mb-2">Pelanggan & Billing</h2>
            <button @click="openCustomer = !openCustomer" 
                    class="w-full flex justify-between items-center py-2 px-4 rounded hover:bg-gray-700 focus:outline-none">
                <span class="flex items-center gap-2">
                    <i class="bi bi-people"></i>
                    Manajemen Pelanggan
                </span>
                <svg class="w-4 h-4 transform transition-transform duration-200"
                     :class="{ 'rotate-180': openCustomer }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="openCustomer" class="ml-6 mt-2 space-y-1" x-transition>
                <a href="/customers"
                   class="block py-1 px-2 rounded {{ isActivePath('customers*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                    Daftar Pelanggan
                </a>
                <a href="/packages"
                   class="block py-1 px-2 rounded {{ isActivePath('packages*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                    Paket Layanan
                </a>
                <a href="/areas"
                   class="block py-1 px-2 rounded {{ isActivePath('areas*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                    Cakupan Area
                </a>
            </div>

            <!-- Billing -->
            <button @click="openBilling = !openBilling" 
                    class="w-full flex justify-between items-center py-2 px-4 rounded hover:bg-gray-700 focus:outline-none">
                <span class="flex items-center gap-2">
                    <i class="bi bi-cash-stack"></i>
                    Billing & Keuangan
                </span>
                <svg class="w-4 h-4 transform transition-transform duration-200"
                     :class="{ 'rotate-180': openBilling }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="openBilling" class="ml-6 mt-2 space-y-1" x-transition>
                <a href="/billing/pembukuan"
                   class="block py-1 px-2 rounded {{ isActivePath('billing/pembukuan*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                    Pembukuan
                </a>
                <a href="/billing/pembayaran"
                   class="block py-1 px-2 rounded {{ isActivePath('billing/pembayaran*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                    Pembayaran Pelanggan
                </a>
                <a href="/billing/invoice"
                   class="block py-1 px-2 rounded {{ isActivePath('billing/invoice*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                    Invoice & Faktur
                </a>
            </div>
        </div>

        <!-- Logout -->
        <div class="pt-4 border-t border-gray-700">
            <a href="#" class="block py-2 px-4 rounded hover:bg-gray-700 text-red-400 hover:text-red-200">Logout</a>
        </div>
    </nav>
</aside>


<!-- Main Content -->
<div class="ml-64 min-h-screen flex flex-col">
    <header class="h-24 bg-gray-800 border-b border-gray-700 flex items-center justify-between px-4 pl-4">
        <div class="text-lg font-semibold">
            {{ $header ?? '' }}
        </div>
        <div class="hidden sm:flex sm:items-center">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-1 text-sm font-medium text-gray-300 hover:text-white transition">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 20 20" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 7l3-3 3 3m0 6l-3 3-3-3"/>
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                         onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </header>
    <main class="p-4">
        @yield('content')
    </main>
</div>

<!-- JS global -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Tempat JS tambahan per halaman -->
@stack('scripts')

<script>
    const sidebar = document.getElementById('sidebar');
    sidebar.addEventListener('scroll', () => {
        localStorage.setItem('sidebarScroll', sidebar.scrollTop);
    });
    window.addEventListener('load', () => {
        const savedScroll = localStorage.getItem('sidebarScroll');
        if (savedScroll) {
            sidebar.scrollTop = savedScroll;
        }
    });
</script>
</body>
</html>

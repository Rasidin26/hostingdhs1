@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-6 space-y-8">

    @if(isset($error))
        <div class="bg-red-100 text-red-700 p-4 rounded-xl shadow mb-6">
            {{ $error }}
        </div>
    @endif

    {{-- HEADER DEVICE --}}
<div class="bg-white rounded-2xl shadow p-6 flex flex-col lg:flex-row items-center justify-between gap-6">
    <div class="flex items-center gap-4">
        <div class="bg-purple-100 text-purple-600 w-14 h-14 flex items-center justify-center rounded-xl text-2xl">
            <i class="fa-solid fa-server"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Mikrotik</h1>
            <div class="flex items-center gap-3 mt-1">
    <span class="font-semibold text-gray-600">
        {{ $device->name ?? '-' }}
    </span>
    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-md text-sm">
        {{ $device->ip_address ?? '-' }}:{{ $device->api_port ?? '-' }}
    </span>
</div>

            <div class="mt-2 flex items-center gap-2">
                <div class="h-4 w-40 bg-green-200 rounded-full overflow-hidden">
                    <div class="h-full bg-green-600 w-full"></div>
                </div>
                <span class="text-sm text-green-700 font-semibold">Terhubung</span>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        @if($device)
        <a href="{{ route('dashboard', $device->id) }}"
           class="px-4 py-2 bg-white border rounded-lg shadow text-gray-600 hover:bg-gray-50 flex items-center gap-2">
            <i class="fa-solid fa-rotate-right"></i> Refresh
        </a>
        @endif
        <a href="{{ route('devices.index') }}"
           class="px-4 py-2 bg-white border rounded-lg shadow text-gray-600 hover:bg-gray-50 flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>


{{-- STATS BOXES --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    {{-- CPU LOAD --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-microchip text-gray-500"></i>
            <p class="text-xs text-gray-500 uppercase">CPU LOAD</p>
        </div>
        <h3 class="text-2xl font-bold text-gray-800" data-stat="cpu">{{ $stats['cpu'] ?? 0 }}%</h3>
    </div>

    {{-- MEMORY --}}
    @php
        $memory = $stats['memory'] ?? 0;
        $memoryColor = $memory < 50 ? 'green-500' : ($memory < 75 ? 'yellow-500' : 'red-500');
    @endphp
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-memory text-gray-500"></i>
            <p class="text-xs text-gray-500 uppercase">MEMORY</p>
        </div>
        <h3 class="text-2xl font-bold text-gray-800"><span data-stat="memory">{{ $memory }}</span>%</h3>
        <div class="w-full h-2 bg-gray-200 rounded-full mt-2">
            <div class="h-full rounded-full bg-{{ $memoryColor }}" style="width: {{ $memory }}%" data-stat="memory_bar"></div>
        </div>
    </div>

    {{-- ACTIVE USERS --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-users text-gray-500"></i>
            <p class="text-xs text-gray-500 uppercase">ACTIVE USERS</p>
        </div>
        <p class="text-xs text-gray-500 mt-1">Hotspot: <span data-stat="hotspot">{{ $stats['hotspot'] ?? 0 }}</span></p>
        <p class="text-xs text-gray-500">PPP: <span data-stat="ppp">{{ $stats['ppp'] ?? 0 }}</span></p>
    </div>

    {{-- TOTAL INTERFACE --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-network-wired text-gray-500"></i>
            <p class="text-xs text-gray-500 uppercase">INTERFACES</p>
        </div>
        <h3 class="text-2xl font-bold text-gray-800" data-stat="traffic">{{ $stats['traffic'] ?? 0 }}</h3>
    </div>
</div>


{{-- REAL-TIME TRAFFIC --}}
<div class="bg-white rounded-2xl shadow p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-chart-line"></i> Real-time Traffic Monitor
            </h3>
            <p class="text-sm text-gray-500">Monitor bandwidth usage per interface</p>
        </div>
        <div class="flex items-center gap-4">
            <label class="text-sm text-gray-600">Interface:</label>
            <select id="ifaceSelect" class="border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">
                @foreach($interfaces as $iface)
                    @if(isset($iface['name']))
                        <option value="{{ $iface['name'] }}">{{ $iface['name'] }}</option>
                    @endif
                @endforeach
            </select>
            <div class="flex gap-2">
                <button id="startTraffic"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2">
                    <i class="fa-solid fa-play"></i> Start
                </button>
                <button id="stopTraffic"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2">
                    <i class="fa-solid fa-stop"></i> Stop
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Chart --}}
        <div class="lg:col-span-2 bg-gray-50 border rounded-xl p-4">
            <h4 class="font-semibold text-gray-700 mb-2">Real-time Traffic Chart</h4>
            <canvas id="trafficChart" class="h-64"></canvas>
            <div class="flex justify-between items-center text-xs text-gray-600 mt-3">
                <span>Current RX: <span id="rxRate">0</span> Mbps</span>
                <span>Current TX: <span id="txRate">0</span> Mbps</span>
                <span>Total: <span id="totalRate">0</span> Mbps</span>
            </div>
        </div>

        {{-- Stats --}}
        <div class="bg-white border rounded-xl p-4 shadow-sm flex flex-col h-full">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold text-gray-700">Interface Statistics</h4>
                <span class="text-xs text-gray-500 flex items-center gap-1">
                    <i class="fa-regular fa-clock"></i> <span id="lastUpdate">--:--:--</span>
                </span>
            </div>
            <div class="space-y-2 text-sm">
                <div class="bg-blue-100 text-blue-700 px-3 py-2 rounded flex justify-between">
                    <span><i class="fa-solid fa-download"></i> Download Rate</span>
                    <span id="statDownload">0 Mbps</span>
                </div>
                <div class="bg-green-100 text-green-700 px-3 py-2 rounded flex justify-between">
                    <span><i class="fa-solid fa-upload"></i> Upload Rate</span>
                    <span id="statUpload">0 Mbps</span>
                </div>
                <div class="bg-purple-100 text-purple-700 px-3 py-2 rounded flex justify-between">
                    <span><i class="fa-solid fa-arrows-up-down"></i> Total Rate</span>
                    <span id="statTotal">0 Mbps</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t text-sm space-y-2">
                <h5 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-yellow-500"></i> Peak Usage
                </h5>
                <p class="flex justify-between text-gray-700">
                    <span class="font-medium">Peak Download</span>
                    <span id="peakDownload" class="font-bold text-blue-700">0 Mbps</span>
                </p>
                <p class="flex justify-between text-gray-700">
                    <span class="font-medium">Peak Upload</span>
                    <span id="peakUpload" class="font-bold text-green-700">0 Mbps</span>
                </p>
                <p class="flex justify-between text-gray-700">
                    <span class="font-medium">Peak Total</span>
                    <span id="peakTotal" class="font-bold text-purple-700">0 Mbps</span>
                </p>
            </div>
        </div>
    </div>



    {{-- Footer Update --}}
<div class="text-sm text-gray-500 flex justify-between items-center mt-4 px-2">
    <span>
        <i class="fa-regular fa-clock"></i> Terakhir update:
        <span data-stat="updated_at">{{ now()->format('d/m/Y, H:i:s') }}</span>
    </span>
    {{-- <a href="#" class="text-blue-600 hover:underline">SALFA NET - Mikrotik Management</a> --}}
</div>
</div>

{{-- SERVICES OVERVIEW --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

    {{-- HOTSPOT --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-3">
            <i class="fa-solid fa-wifi"></i> Hotspot Overview
        </h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Profiles</p>
                <p class="text-xl font-bold text-gray-800" data-stat="hotspot_profiles">
                    {{ $hotspot['profiles'] ?? 0 }}
                </p>
            </div>
            <div>
                <p class="text-gray-500">Total Users</p>
                <p class="text-xl font-bold text-gray-800" data-stat="hotspot_users">
                    {{ $hotspot['total_users'] ?? 0 }}
                </p>
            </div>
            <div>
                <p class="text-gray-500">Active</p>
                <p class="text-xl font-bold text-green-600" data-stat="hotspot_active">
                    {{ $hotspot['active'] ?? 0 }}
                </p>
            </div>
            <div>
                <p class="text-gray-500">Bindings</p>
                <p class="text-xl font-bold text-gray-800" data-stat="hotspot_bindings">
                    {{ $hotspot['bindings'] ?? 0 }}
                </p>
            </div>
        </div>
    </div>

    {{-- PPPOE --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-3">
            <i class="fa-solid fa-plug"></i> PPPoE Overview
        </h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Servers</p>
                <p class="text-xl font-bold text-gray-800" data-stat="pppoe_servers">
                    {{ $pppoe['servers'] ?? 0 }}
                </p>
            </div>
            <div>
                <p class="text-gray-500">Profiles</p>
                <p class="text-xl font-bold text-gray-800" data-stat="pppoe_profiles">
                    {{ $pppoe['profiles'] ?? 0 }}
                </p>
            </div>
            <div>
                <p class="text-gray-500">Secrets</p>
                <p class="text-xl font-bold text-gray-800" data-stat="pppoe_secrets">
                    {{ $pppoe['secrets'] ?? 0 }}
                </p>
            </div>
            <div>
                <p class="text-gray-500">Active</p>
                <p class="text-xl font-bold text-green-600" data-stat="pppoe_active">
                    {{ $pppoe['active'] ?? 0 }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- SYSTEM INFORMATION --}}
<div class="bg-white rounded-2xl shadow p-6 mt-6">
    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2 mb-6">
        <i class="fa-solid fa-circle-info text-blue-600"></i> System Information
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Hardware --}}
        <div class="bg-gray-50 border rounded-xl p-4 shadow-sm">
            <h4 class="font-semibold text-gray-700 flex items-center gap-2 mb-3">
                <i class="fa-solid fa-gear text-gray-600"></i> Hardware
            </h4>
            <div class="space-y-2 text-sm">
                <p class="flex justify-between text-gray-700">
                    <span>Board Name</span>
                    <span class="font-medium">{{ $system['board_name'] ?? '-' }}</span>
                </p>
                <p class="flex justify-between text-gray-700">
                    <span>Architecture</span>
                    <span class="font-medium">{{ $system['architecture'] ?? '-' }}</span>
                </p>
                <p class="flex justify-between text-gray-700">
                    <span>CPU</span>
                    <span class="font-medium">{{ $system['cpu'] ?? '-' }}</span>
                </p>
                <p class="flex justify-between text-gray-700">
                    <span>CPU Count</span>
                    <span class="font-medium">{{ $system['cpu_count'] ?? '-' }}</span>
                </p>
            </div>
        </div>

        {{-- Software --}}
        <div class="bg-gray-50 border rounded-xl p-4 shadow-sm">
            <h4 class="font-semibold text-gray-700 flex items-center gap-2 mb-3">
                <i class="fa-solid fa-code text-gray-600"></i> Software
            </h4>
            <div class="space-y-2 text-sm">
                <p class="flex justify-between text-gray-700">
                    <span>Version</span>
                    <span class="font-medium">{{ $system['version'] ?? '-' }}</span>
                </p>
                <p class="flex justify-between text-gray-700">
                    <span>Uptime</span>
                    <span class="font-medium">{{ $system['uptime'] ?? '-' }}</span>
                </p>
                <p class="flex justify-between text-gray-700">
                    <span>Free Memory</span>
                    <span class="font-medium">{{ $system['free_memory'] ?? '-' }}</span>
                </p>
                <p class="flex justify-between text-gray-700">
                    <span>Free Storage</span>
                    <span class="font-medium">{{ $system['free_storage'] ?? '-' }}</span>
                </p>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deviceId = {{ $device->id ?? 'null' }};
    let intervalId = null;
    let selectedInterface = null;

    // === Chart Init ===
    const ctx = document.getElementById('trafficChart').getContext('2d');
    const trafficChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                { label: 'Download (Mbps)', data: [], borderColor: 'blue', fill: false },
                { label: 'Upload (Mbps)', data: [], borderColor: 'green', fill: false }
            ]
        },
        options: {
            responsive: true,
            animation: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // === Update Stats ===
    function updateStats() {
        axios.get(`/mikrotik/stats/${deviceId}`)
            .then(res => {
                if (res.data.error) {
                    console.error(res.data.error);
                    return;
                }
                const stats = res.data.stats;
                const interfaces = res.data.interfaces;

                // --- Device Stats ---
                document.querySelector("[data-stat='cpu']").innerText = stats.cpu + '%';
                document.querySelector("[data-stat='memory']").innerText = stats.memory + '%';
                document.querySelector("[data-stat='memory_bar']").style.width = stats.memory + '%';
                document.querySelector("[data-stat='hotspot']").innerText = stats.hotspot;
                document.querySelector("[data-stat='ppp']").innerText = stats.ppp;
                document.querySelector("[data-stat='traffic']").innerText = stats.traffic;
                document.querySelector("[data-stat='updated_at']").innerText = stats.updated_at;

                // --- Dropdown Interface ---
                const select = document.querySelector("select");
                select.innerHTML = ""; // reset dulu biar tidak dobel
                if (interfaces.length > 0) {
                    interfaces.forEach(i => {
                        const opt = document.createElement('option');
                        opt.value = i.name;
                        opt.textContent = i.name;
                        if (i.name === selectedInterface) opt.selected = true;
                        select.appendChild(opt);
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.disabled = true;
                    opt.textContent = 'Tidak ada interface';
                    select.appendChild(opt);
                }

                // --- Update Traffic Chart (kalau ada data rx/tx di API) ---
                if (stats.rx !== undefined && stats.tx !== undefined) {
                    const now = new Date().toLocaleTimeString();

                    if (trafficChart.data.labels.length > 20) {
                        trafficChart.data.labels.shift();
                        trafficChart.data.datasets[0].data.shift();
                        trafficChart.data.datasets[1].data.shift();
                    }

                    trafficChart.data.labels.push(now);
                    trafficChart.data.datasets[0].data.push(stats.rx); // Mbps
                    trafficChart.data.datasets[1].data.push(stats.tx); // Mbps
                    trafficChart.update();

                    document.getElementById('rxRate').innerText = stats.rx;
                    document.getElementById('txRate').innerText = stats.tx;
                    document.getElementById('totalRate').innerText = stats.total ?? (stats.rx + stats.tx);
                }
            });
    }

    // === Start & Stop Buttons ===
    document.getElementById('startTraffic').addEventListener('click', function(){
        selectedInterface = document.querySelector("select").value;
        if (!intervalId) {
            updateStats();
            intervalId = setInterval(updateStats, 5000);
        }
    });

    document.getElementById('stopTraffic').addEventListener('click', function(){
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        }
    });

    // === Ganti Interface ===
    document.querySelector("select").addEventListener('change', function(){
        selectedInterface = this.value;
        trafficChart.data.labels = [];
        trafficChart.data.datasets[0].data = [];
        trafficChart.data.datasets[1].data = [];
        trafficChart.update();
    });
});
</script>

@endsection

@extends('layouts.app')

@section('content')
<style>
    input, select, textarea {
        color: #1f2937 !important; /* Tailwind text-gray-800 */
        background-color: #ffffff !important; /* bg-white */
    }
</style>

<div class="container mx-auto px-4 py-6">
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('customers.index') }}"
               class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold px-4 py-2 rounded flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pelanggan
            </a>
            <div class="text-right">
                <div class="text-lg font-semibold text-blue-600 flex items-center gap-2">
                    <i class="bi bi-person-plus"></i> Tambah Pelanggan Baru
                </div>
                <div class="mt-1 text-xs bg-purple-600 text-white px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                    <i class="bi bi-wifi"></i> DHS
                </div>
            </div>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulir -->
        <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Formulir Data Pelanggan -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h2 class="text-base font-semibold text-gray-800 mb-1 flex items-center gap-1">
                    <i class="bi bi-journal-text"></i> Formulir Data Pelanggan
                </h2>
                <p class="text-sm text-gray-500 mb-4">Lengkapi informasi pelanggan dengan benar</p>

                <h3 class="text-sm font-semibold text-blue-600 mb-4 flex items-center gap-1">
                    <i class="bi bi-person-circle"></i> Informasi Pribadi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required autocomplete="off"
                               class="mt-1 p-2 w-full border rounded text-gray-800 bg-white" placeholder="Masukkan nama lengkap pelanggan">
                        <p class="text-xs text-gray-500 mt-1">Nama lengkap sesuai identitas resmi</p>
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required 
                               pattern="[0-9]{10,15}" autocomplete="off"
                               class="mt-1 p-2 w-full border rounded text-gray-800 bg-white" placeholder="Contoh: 081234567890">
                        <p class="text-xs text-gray-500 mt-1">Nomor telepon aktif (10–15 digit)</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" autocomplete="off"
                               class="mt-1 p-2 w-full border rounded text-gray-800 bg-white" placeholder="contoh@email.com">
                        <p class="text-xs text-gray-500 mt-1">Email untuk komunikasi dan notifikasi (opsional)</p>
                    </div>

                    <!-- Tipe Koneksi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe Koneksi <span class="text-red-500">*</span></label>
                        <select name="koneksi" required
                            class="mt-1 p-2 w-full border rounded text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih tipe koneksi internet</option>
                            @foreach(['PPPoE', 'Hotspot', 'Static IP'] as $tipe)
                                <option value="{{ $tipe }}" {{ old('koneksi') == $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Jenis koneksi yang akan digunakan pelanggan</p>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="alamat_lengkap" rows="2" required autocomplete="off"
                                  class="mt-1 p-2 w-full border rounded text-gray-800 bg-white"
                                  placeholder="Masukkan alamat lengkap untuk instalasi">{{ old('alamat_lengkap') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Alamat lengkap untuk proses instalasi dan penagihan</p>
                    </div>
                </div>
            </div>

            <!-- Lokasi Pemasangan -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h2 class="text-base font-semibold text-blue-600 mb-1 flex items-center gap-1">
                    <i class="bi bi-geo-alt-fill"></i> Lokasi Pemasangan
                </h2>
                <p class="text-sm text-gray-500 mb-4">
                    Tentukan lokasi pemasangan dengan mengklik pada peta atau menggunakan lokasi saat ini
                </p>

                <!-- Peta -->
<!-- Peta -->
<!-- Peta -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white border-0">
        <h4 class="fw-bold mb-1 text-primary">
            <i class="fa-solid fa-map-location-dot me-2"></i> Lokasi Pemasangan
        </h4>
        <p class="text-muted small mb-0">
            Tentukan lokasi pemasangan dengan mengklik pada peta atau gunakan lokasi saat ini
        </p>
    </div>
    <div class="card-body p-0">
        <div id="map" style="height:400px; border-radius: 0 0 10px 10px; overflow: hidden;"></div>
    </div>
</div>


                <!-- Tombol Gunakan Lokasi -->
                <div class="mb-4">
                    <button type="button" onclick="getCurrentLocation()"
                        class="px-4 py-2 border border-indigo-500 text-indigo-600 hover:bg-indigo-50 rounded flex items-center gap-2 text-sm font-medium">
                        <i class="bi bi-geo"></i> Gunakan Lokasi Saat Ini
                    </button>
                    <p id="koordinat" class="text-sm text-gray-600 mt-2">
                        Koordinat: <span id="display-koordinat">-</span>
                    </p>
                </div>

                <!-- Input Koordinat -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" autocomplete="off"
                            class="mt-1 p-2 w-full border rounded text-gray-800 bg-white" placeholder="Latitude">
                    </div>
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" autocomplete="off"
                            class="mt-1 p-2 w-full border rounded text-gray-800 bg-white" placeholder="Longitude">
                    </div>
                </div>
            </div>

            <!-- Informasi Layanan Internet -->
<div class="bg-white p-6 rounded-2xl shadow ring-1 ring-gray-200">
    <h2 class="text-xl font-semibold text-blue-600 mb-6 border-b pb-2">Informasi Layanan Internet</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Paket Internet -->
        <div>
            <label class="block text-sm font-medium text-blue-600 mb-1">
                Paket Internet <span class="text-red-500">*</span>
            </label>
            <select id="packageSelect" name="package_id" required
                class="mt-1 p-2 w-full border rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-blue-400">
                <option value="">Pilih paket</option>
                @foreach ($packages as $package)
                    <option value="{{ $package->id }}" data-harga="{{ $package->harga }}">
                        {{ $package->nama_paket }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Hidden input harga -->
        <input type="hidden" name="harga" id="hargaHidden">

        <!-- Area Layanan -->
        <div>
            <label class="block text-sm font-medium text-blue-600 mb-1">Area Layanan <span class="text-red-500">*</span></label>
            <select id="areaSelect" name="area_id" class="form-control">
                <option value="">-- Pilih Area --</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" data-polygon='@json($area->polygon)'>
                        {{ $area->nama_area }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tanggal Instalasi -->
        <div>
            <label class="block text-sm font-medium text-blue-600 mb-1">Tanggal Instalasi</label>
            <input type="date" name="tanggal_instalasi" value="{{ old('tanggal_instalasi') }}"
                class="mt-1 p-2 w-full border rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-blue-400">
        </div>

        <!-- Tanggal Tagihan -->
        <div>
            <label class="block text-sm font-medium text-blue-600 mb-1">Tanggal Tagihan</label>
            <input type="date" name="tanggal_tagihan" value="{{ old('tanggal_tagihan') }}"
                class="mt-1 p-2 w-full border rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-blue-400">
        </div>

        <!-- Status Pembayaran -->
        <div>
            <label class="block text-sm font-medium text-blue-600 mb-1">Status Pembayaran</label>
            <select name="status_pembayaran"
                class="mt-1 p-2 w-full border rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-blue-400">
                <option value="Belum Lunas" selected>Belum Lunas</option>
                <option value="Lunas">Lunas</option>
            </select>
            <p class="text-xs text-gray-500 mt-1">Default Belum Lunas</p>
        </div>

        <!-- Catatan -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-blue-600 mb-1">Catatan</label>
            <textarea name="catatan" rows="3" class="mt-1 p-2 w-full border rounded-lg text-gray-800 bg-white focus:ring-2 focus:ring-blue-400">{{ old('catatan') }}</textarea>
        </div>
    </div>
</div>

            <!-- Tombol Aksi -->
            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('customers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">Batal</a>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">Simpan</button>
            </div>

            <div class="bg-blue-100 text-blue-900 text-sm rounded-xl p-4 flex items-start gap-3 mt-4">
                <div class="mt-1">
                    <i class="bi bi-info-circle-fill text-blue-800"></i>
                </div>
                <div>
                    <span class="font-semibold">Informasi:</span> Field yang bertanda <span class="text-red-500">*</span> wajib diisi. Pastikan semua data telah benar sebelum menyimpan.
                </div>
            </div>
        </form>
    </div>
    
</div>
@endsection

@push('scripts')
<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
let map, marker;

function initLeafletMap() {
    // Default kalau geolocation gagal
    const fallbackPos = [-6.564953, 107.757134]; 

    map = L.map('map').setView(fallbackPos, 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    marker = L.marker(fallbackPos, { draggable: true }).addTo(map);

    // Update input sesuai posisi marker
    updateLatLng({ lat: fallbackPos[0], lng: fallbackPos[1] });

    // Coba ambil lokasi device
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const pos = [lat, lng];

            map.setView(pos, 18);
            marker.setLatLng(pos);

            updateLatLng({ lat, lng });
        }, function() {
            console.warn("Gagal dapat lokasi, pakai fallback");
        });
    } else {
        console.warn("Browser tidak mendukung geolokasi");
    }

    marker.on('dragend', function(e) {
        updateLatLng(e.target.getLatLng());
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateLatLng(e.latlng);
    });

    document.getElementById('latitude').addEventListener('input', updateFromInput);
    document.getElementById('longitude').addEventListener('input', updateFromInput);

    setTimeout(() => {
        map.invalidateSize();
    }, 500);
}


function updateLatLng(latlng) {
    document.getElementById('latitude').value = latlng.lat;
    document.getElementById('longitude').value = latlng.lng;
    document.getElementById('display-koordinat').innerText =
        `${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`;
}

// 🔥 Fungsi baru: kalau user ketik manual latitude & longitude
let typingTimer;
// 🔥 Pastikan element ada dulu baru kasih listener
document.addEventListener('DOMContentLoaded', () => {
    const alamatInput = document.querySelector('textarea[name="alamat_lengkap"]');
    if (alamatInput) {
        let typingTimer;
        alamatInput.addEventListener('input', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(geocodeAddress, 1200); // tunggu 1.2 detik setelah selesai ngetik
        });
    }
});

function geocodeAddress() {
    const alamatInput = document.querySelector('textarea[name="alamat_lengkap"]');
    if (!alamatInput) return;
    const alamat = alamatInput.value.trim();
    if (alamat.length < 3) return;

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(alamat)}&polygon_geojson=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                const pos = [lat, lng];

                // Update marker & center
                map.setView(pos, 13);
                marker.setLatLng(pos);
                updateLatLng({ lat, lng });

                // Kalau ada geojson polygon dari Nominatim
                if (data[0].geojson) {
                    if (areaPolygon) map.removeLayer(areaPolygon);

                    areaPolygon = L.geoJSON(data[0].geojson, {
                        color: "blue",
                        weight: 1,
                        fillColor: "blue",
                        fillOpacity: 0.2
                    }).addTo(map);

                    map.fitBounds(areaPolygon.getBounds());
                }
            }
        })
        .catch(err => {
            console.error("Geocoding error:", err);
        });
}



function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const pos = [lat, lng];

            map.setView(pos, 18);
            marker.setLatLng(pos);

            updateLatLng({ lat, lng });
        }, function() {
            alert("Gagal mendapatkan lokasi Anda");
        });
    } else {
        alert("Browser Anda tidak mendukung Geolokasi.");
    }
}

window.addEventListener('load', initLeafletMap);
let areaPolygon;

document.getElementById('areaSelect').addEventListener('change', function () {
    let selected = this.options[this.selectedIndex];
    let polygonData = selected.getAttribute('data-polygon');

    if (polygonData) {
        let coords = JSON.parse(polygonData);

        // hapus polygon lama
        if (areaPolygon) {
            map.removeLayer(areaPolygon);
        }

        // tambahin polygon baru
        areaPolygon = L.polygon(coords, {
            color: "blue",
            weight: 2,
            fillColor: "blue",
            fillOpacity: 0.2
        }).addTo(map);

        // zoom ke area polygon
        map.fitBounds(areaPolygon.getBounds());
    }
});
if (data[0].geojson) {
    // kalau ada polygon, pakai polygon
    if (areaPolygon) map.removeLayer(areaPolygon);

    areaPolygon = L.geoJSON(data[0].geojson, {
        color: "blue",
        weight: 2,
        fillColor: "blue",
        fillOpacity: 0.2
    }).addTo(map);

    map.fitBounds(areaPolygon.getBounds());
} else if (data[0].boundingbox) {
    // kalau polygon ga ada, buat rectangle dari boundingbox
    const bbox = data[0].boundingbox.map(Number); // [south, north, west, east]
    const rectangle = [
        [bbox[0], bbox[2]], // SW
        [bbox[1], bbox[3]]  // NE
    ];

    if (areaPolygon) map.removeLayer(areaPolygon);

    areaPolygon = L.rectangle(rectangle, {
        color: "blue",
        weight: 2,
        fillColor: "blue",
        fillOpacity: 0.2
    }).addTo(map);

    map.fitBounds(areaPolygon.getBounds());
}
document.addEventListener('DOMContentLoaded', function () {
    const packageSelect = document.getElementById('packageSelect');
    const hargaInput = document.getElementById('hargaPaket');

    packageSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const price = selected.getAttribute('data-price') || 0;

        // Format harga (Rp)
        hargaInput.value = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(price);
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const packageSelect = document.getElementById('packageSelect');
    const hargaHidden = document.getElementById('hargaHidden');

    packageSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const harga = selected.getAttribute('data-harga') || 0;
        hargaHidden.value = harga; // simpan langsung ke hidden input
    });
});


</script>

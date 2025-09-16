@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-triangle-exclamation"></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- 🔹 Header Baru dalam Card -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="flex items-center justify-between">
            <!-- Kiri: Tombol Kembali + Info Device -->
            <div class="flex items-center space-x-6">
              <!-- Tombol Kembali -->
                <a href="{{ route('dashboard.index') }}"
                class="inline-flex items-center px-3 py-2 rounded-md bg-gradient-to-r from-purple-600 to-indigo-600 
                        hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-medium shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-6-6 6-6" />
                    </svg>
                    Kembali ke dashboard
                </a>
                                <!-- Info Device -->
                <div class="flex flex-col leading-tight">
                    <span class="fw-semibold fs-5 text-dark">Device: DHS</span>
                    <span class="bg-gray-200 text-gray-800 text-xs px-2 py-0.5 rounded-full font-medium w-fit mt-0.5">
                        Peta Infrastruktur FTTH
                    </span>
                </div>
            </div>

            <!-- Kanan: Tombol Aksi -->
            <div class="flex space-x-2">
                <button id="btnRefreshTop" class="btn btn-dark">
                    <i class="fa-solid fa-rotate"></i> Refresh
                </button>

                <form action="{{ route('pelanggan.sinkron') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-primary"
                        style="background: linear-gradient(90deg,#6a11cb,#2575fc);border:none;">
                        <i class="fa-solid fa-user-group"></i> Sinkronkan
                    </button>
                </form>

        
            </div>
        </div>
    </div>

</div>

<div class="row mb-4">
    <!-- Total Lokasi -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-info">
                <h5>Total Lokasi</h5>
                <p class="stat-number">{{ count($odc) + count($odp) }}</p>
            </div>
            <div class="stat-icon bg-blue">
                <i class="fa-solid fa-map"></i>
            </div>
        </div>
    </div>

    <!-- Lokasi ODC -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-info">
                <h5>Lokasi ODC</h5>
                <p class="stat-number">{{ count($odc) }}</p>
            </div>
            <div class="stat-icon bg-red">
                <i class="fa-solid fa-database"></i>
            </div>
        </div>
    </div>

    <!-- Lokasi ODP -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-info">
                <h5>Lokasi ODP</h5>
                <p class="stat-number">{{ count($odp) }}</p>
            </div>
            <div class="stat-icon bg-green">
                <i class="fa-solid fa-network-wired"></i>
            </div>
        </div>
    </div>

    <!-- Lokasi ONT -->
<div class="col-md-3">
    <div class="stat-card">
        <div class="stat-info">
            <h5>Lokasi ONT</h5>
            <p class="stat-number">{{ count($ont ?? []) }}</p>
        </div>
        <div class="stat-icon bg-yellow">
            <i class="fa-solid fa-wifi"></i>
        </div>
    </div>
</div>

</div>
<!-- 🔹 Pencarian + Tambah -->
<div class="card shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
        
        <!-- Kiri: Search -->
        <div class="d-flex align-items-center flex-grow-1" style="gap: 6px; max-width: 600px;">
            <input type="text" id="searchInput" class="form-control"
                   placeholder="Cari ODC atau ODP...">
            <button id="btnSearch" class="btn text-white"
                style="background: linear-gradient(90deg,#6a11cb,#2575fc); border:none;">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
      <button id="btnRefresh" class="btn"
    style="background: rgba(248,249,250,0.8); color:#444; border:1px solid #ccc; border-radius:8px;">
    <i class="fa-solid fa-rotate-right"></i> Refresh
</button>
        </div>

 <!-- Tombol buka modal -->
<button class="btn btn-primary" onclick="tambahLokasi()">
    <i class="fa-solid fa-plus"></i> Tambah Lokasi
</button>


    </div>
</div>






    <!-- 🔹 Card Peta -->
<div class="card shadow-sm mb-4">
    <div class="card-header text-center bg-white border-0">
        <h4 class="fw-bold mb-1" style="color:#2d3748;">
            <i class="fa-solid fa-map-location-dot me-2 text-primary"></i>
            Peta Jaringan ODC & ODP
        </h4>
        <p class="text-muted small mb-0">Visualisasi lokasi ODC dan ODP pada peta interaktif</p>
    </div>
    <div class="card-body p-0">
        <div id="map" style="height: 600px; border-radius: 0 0 10px 10px; overflow: hidden;"></div>
    </div>
</div>

<!-- Modal Wrapper -->
<div id="formLokasiModal" class="modal-custom">
    <div class="modal-content-custom">
        <!-- Tombol close -->
        <span class="close-btn" onclick="closeModal('formLokasiModal')">&times;</span>

        <!-- Form Tambah / Edit Lokasi -->
        <h4 id="formTitle" class="text-center mb-4">Tambah Lokasi Baru</h4>
<form id="lokasiForm" action="{{ route('lokasi.store') }}" method="POST">
    @csrf
    <input type="hidden" name="id" id="form_id">

    <!-- Pilih Tipe -->
    <div class="mb-3">
        <label for="tipe" class="form-label">Tipe Lokasi *</label>
        <select name="tipe" id="tipe" class="form-select" required>
            <option value="">-- Pilih Tipe --</option>
            <option value="ODC">ODC</option>
            <option value="ODP">ODP</option>
            <option value="ONT">ONT</option>
        </select>
    </div>

    <!-- Nama Lokasi -->
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Lokasi *</label>
        <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama lokasi" required>
    </div>

    <!-- Kode Lokasi -->
    <div class="mb-3">
        <label for="kode" class="form-label">Kode Lokasi *</label>
        <input type="text" name="kode" id="kode" class="form-control"
               placeholder="Contoh: ODC-001, ODP-A123, atau ONT-001" required>
    </div>

    <!-- Dropdown ODC (khusus ODP) -->
    <div class="mb-3" id="odcSelect" style="display:none;">
        <label for="odc_id" class="form-label">Pilih ODC (Hanya untuk ODP)</label>
        <select name="odc_id" id="odc_id" class="form-select">
            <option value="">-- Pilih ODC --</option>
            @foreach($odc as $o)
                <option value="{{ $o->id }}">{{ $o->kode }} - {{ $o->nama }}</option>
            @endforeach
        </select>
    </div>

    <!-- Dropdown ODP (khusus ONT) -->
    <div class="mb-3" id="odpSelect" style="display:none;">
        <label for="odp_id" class="form-label">Pilih ODP (Hanya untuk ONT)</label>
        <select name="odp_id" id="odp_id" class="form-select">
            <option value="">-- Pilih ODP --</option>
            @foreach($odp as $p)
                <option value="{{ $p->id }}">{{ $p->kode }} - {{ $p->nama }}</option>
            @endforeach
        </select>
    </div>

    <!-- Latitude & Longitude -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="lat" class="form-label">Latitude *</label>
            <input type="text" name="lat" id="lat" class="form-control" required placeholder="-6.565809">
        </div>
        <div class="col-md-6 mb-3">
            <label for="lng" class="form-label">Longitude *</label>
            <input type="text" name="lng" id="lng" class="form-control" required placeholder="107.757815">
        </div>
    </div>

    <!-- Field tambahan untuk ONT -->
    <div id="ontFields" style="display:none;">
        <div class="mb-3">
            <label for="nama_client" class="form-label">Nama Client</label>
            <input type="text" name="nama_client" id="nama_client" class="form-control" placeholder="Nama pelanggan">
        </div>

        <div class="mb-3">
            <label for="id_pelanggan" class="form-label">ID Pelanggan</label>
            <input type="text" name="id_pelanggan" id="id_pelanggan" class="form-control" placeholder="Nomor ID pelanggan">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status Pelanggan</label>
            <select name="status" id="status" class="form-select">
                <option value="Aktif">Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="area_id" class="form-label">Area</label>
            <select name="area_id" id="area_id" class="form-select">
                <option value="">-- Pilih Area --</option>
                @foreach($areas as $a)
                    <option value="{{ $a->id }}">{{ $a->nama_area }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="package_id" class="form-label">Paket</label>
            <select name="package_id" id="package_id" class="form-select">
                <option value="">-- Pilih Paket --</option>
                @foreach($packages as $p)
                    <option value="{{ $p->id }}">{{ $p->nama_paket }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="device" class="form-label">Device</label>
            <input type="text" name="device" id="device" class="form-control" placeholder="Jenis device (contoh: DHS)">
        </div>
    </div>

    <!-- Info tambahan -->
    <div class="mb-3">
        <label for="info" class="form-label">Info (Opsional)</label>
        <textarea name="info" id="info" class="form-control" rows="3" placeholder="Masukkan detail tambahan"></textarea>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-save me-2"></i> Simpan Lokasi
        </button>
    </div>
</form>

    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- FontAwesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    /* Style icon bulat */
    .icon-odc, .icon-odp {
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        box-shadow: 0 0 3px rgba(0,0,0,0.4);
        color: white;
        font-size: 14px;
    }
    .icon-odc { background: #e63946; } /* merah */
    .icon-odp { background: #00bfa5; } /* hijau tosca */
.icon-ont {
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    box-shadow: 0 0 3px rgba(0,0,0,0.4);
    color: white;
    font-size: 14px;
    background: #fbbf24; /* kuning cerah */
}

    /* Style popup */
    .custom-popup {
        font-family: 'Segoe UI', sans-serif;
        min-width: 220px;
    }
    .custom-popup h5 {
        font-weight: 700;
        margin-bottom: 2px;
    }
    .custom-popup small {
        color: #666;
        font-size: 12px;
    }
    .custom-popup .info {
        margin: 6px 0;
        font-size: 13px;
    }
    .btn-edit {
        background: linear-gradient(90deg, #6a11cb, #2575fc);
        color: white !important;
        border: none;
    }
    .btn-edit:hover {
        opacity: 0.9;
    }
    .btn-hapus {
        background: #e63946;
        color: white !important;
        border: none;
    }
    .btn-hapus:hover {
        opacity: 0.9;
    }

    .stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

.stat-card.clicked {
    animation: clickPulse 0.3s ease;
}

@keyframes clickPulse {
    0%   { transform: scale(1); }
    50%  { transform: scale(0.95); }
    100% { transform: scale(1); }
}

.stat-info h5 {
    font-size: 14px;
    color: #555;
    margin: 0;
}

.stat-number {
    font-size: 22px;
    font-weight: bold;
    margin: 0;
    color: #000;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
}

/* Warna icon */
.bg-blue { background: #3b82f6; }
.bg-red { background: #f87171; }
.bg-green { background: #34d399; }
.bg-yellow { background: #facc15; }


#searchInput {
    flex: 1;              /* biar input melar */
    min-width: 250px;     /* batas minimal */
    border-radius: 8px;
    font-size: 14px;
}

#btnSearch, #btnRefresh {
    border-radius: 8px;
    font-size: 14px;
    padding: 6px 14px;
    white-space: nowrap;
}


#btnSearch:hover, #btnRefresh:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.12);
}

#btnSearch:active, #btnRefresh:active {
    transform: scale(0.96);
}
/* Modal Wrapper */
.modal-custom {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1050;
    background: rgba(0,0,0,0.25); /* kasih backdrop halus */
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.modal-content-custom {
    position: relative;
    background: #fff;
    border-radius: 14px;
    padding: 24px 22px;
    width: 100%;
    max-width: 500px;
    height: auto;
    overflow: visible;   /* penting, jangan auto */
    box-shadow: 0 12px 40px rgba(0,0,0,0.25);
}
.modal-content-custom {
    width: 100%;
    max-width: 500px;
    max-height: 90vh;   /* modal nggak melebihi layar */
    overflow-y: auto;   /* kalau isi lebih panjang, scroll muncul */
    border-radius: 14px;
    padding: 24px 22px;
}

.modal-content-custom {
    background: #fff;
    border-radius: 14px;
    padding: 24px 22px;   /* ⬅️ padding bikin isi form agak masuk ke dalam */
    width: 100%;
    max-width: 500px;

}
.close-btn {
    position: absolute;   /* ⬅️ posisinya absolut, ikut layar, bukan kotak form */
    top: 14px;
    right: 18px;
}



/* Animasi masuk */
@keyframes fadeInUp {
    from { transform: translateY(40px); opacity: 0; }
    to   { transform: translateY(0); opacity: 1; }
}

/* Tombol close */
.close-btn {
    position: absolute;
    top: 14px;
    right: 18px;
    font-size: 26px;
    cursor: pointer;
    color: #999;
    transition: 0.2s;
}
.close-btn:hover { color: #333; transform: scale(1.15); }

/* Title */
#formTitle {
    font-weight: 700;
    font-size: 20px;
    color: #2d3748;
}

/* Label */
.form-label {
    font-weight: 600;
    color: #444;
    margin-bottom: 6px;
    font-size: 14px;
}

/* Input, select, textarea */
.form-control, .form-select {
    border-radius: 10px;
    border: 1px solid #d1d5db;
    padding: 10px 12px;
    font-size: 14px;
    transition: 0.2s;
}
.form-control:focus, .form-select:focus {
    border-color: #6a11cb;
    box-shadow: 0 0 0 3px rgba(106,17,203,0.15);
}

/* Textarea lebih halus */
textarea.form-control {
    resize: vertical;
    min-height: 90px;
}

/* Tombol Simpan */
#lokasiForm button[type="submit"] {
    background: linear-gradient(90deg,#6a11cb,#2575fc);
    border: none;
    padding: 12px 0;
    font-size: 15px;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.2s;
}
#lokasiForm button[type="submit"]:hover {
    opacity: 0.9;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(106,17,203,0.25);
}

</style>

<script>
document.addEventListener("DOMContentLoaded", function() {

    var map = L.map('map').setView([-6.565809, 107.757815], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // 🔴 Icon ODC
    var odcIcon = L.divIcon({
        className: "custom-odc-icon",
        html: `<div class="icon-odc"><i class="fa-solid fa-database"></i></div>`,
        iconSize: [30, 30],
        iconAnchor: [15, 30]
    });

    // 🟢 Icon ODP
    var odpIcon = L.divIcon({
        className: "custom-odp",
        html: '<div class="icon-odp"><i class="fa-solid fa-network-wired"></i></div>',
        iconSize: [26, 26],
        iconAnchor: [13, 13]
    });

    // 🔵 Icon ONT
    var ontIcon = L.divIcon({
        className: "custom-ont",
        html: '<div class="icon-ont"><i class="fa-solid fa-wifi"></i></div>',
        iconSize: [26, 26],
        iconAnchor: [13, 13]
    });

    // Data dari Laravel
    var odcs = @json($odc);
    var odps = @json($odp);
    var onts = @json($ont);

    var odcMarkers = {};
    var odpMarkers = {};

    // 🔹 Marker ODC
    odcs.forEach(function(item) {
        var kode = (item.kode ?? '').replace(/'/g, "\\'");
        var nama = (item.nama ?? '').replace(/'/g, "\\'");
        var info = (item.info ?? '').replace(/'/g, "\\'");

        var marker = L.marker([item.lat, item.lng], { icon: odcIcon })
            .addTo(map)
            .bindPopup(`
                <div class="custom-popup">
                    <h5>${kode}</h5>
                    <small>Optical Distribution Cabinet</small>
                    <div class="info"><i class="fa-solid fa-location-dot"></i> ${item.lat}, ${item.lng}</div>
                    <div class="info"><i class="fa-solid fa-database"></i> ${nama}</div>
                    <div class="info"><b>Deskripsi:</b> ${info ?? '-'}</div>
                    <div>
                        <button class="btn btn-sm btn-edit" onclick="editLokasi('ODC', ${item.id}, '${kode}', '${nama}', '${item.lat}', '${item.lng}', '${info ?? ''}')">
                            <i class="fa-solid fa-pen"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-hapus"><i class="fa-solid fa-trash"></i> Hapus</button>
                    </div>
                </div>
            `);
        odcMarkers[item.id] = marker;
    });

    // 🔹 Pane garis
    map.createPane("linesPane");
    map.getPane("linesPane").style.zIndex = 400;

    // 🔹 Marker ODP
    odps.forEach(function(item) {
        var kode = (item.kode ?? '').replace(/'/g, "\\'");
        var nama = (item.nama ?? '').replace(/'/g, "\\'");
        var info = (item.info ?? '').replace(/'/g, "\\'");

        var marker = L.marker([item.lat, item.lng], { icon: odpIcon })
            .addTo(map)
            .bindPopup(`
                <div class="custom-popup">
                    <h5>${kode}</h5>
                    <small>Optical Distribution Point</small>
                    <div class="info"><i class="fa-solid fa-location-dot"></i> ${item.lat}, ${item.lng}</div>
                    <div class="info"><i class="fa-solid fa-server"></i> ${nama}</div>
                    <div class="info"><b>Deskripsi:</b> ${info ?? '-'}</div>
                    <div>
                        <button class="btn btn-sm btn-edit" onclick="editLokasi('ODP', ${item.id}, '${kode}', '${nama}', '${item.lat}', '${item.lng}', '${info ?? ''}', ${item.odc_id ?? null})">
                            <i class="fa-solid fa-pen"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-hapus"><i class="fa-solid fa-trash"></i> Hapus</button>
                    </div>
                </div>
            `);
        odpMarkers[item.id] = marker;

        // Hubungkan ODP ke ODC
        if(item.odc_id && odcMarkers[item.odc_id]) {
            var odcLatLng = odcMarkers[item.odc_id].getLatLng();
            var odpLatLng = marker.getLatLng();

            var adjustedOdpLatLng = L.latLng(
                odpLatLng.lat + 0.00005,
                odpLatLng.lng + 0.00005
            );

            L.polyline([odcLatLng, adjustedOdpLatLng], {
                color: "orange",
                weight: 3,
                opacity: 0.9,
                dashArray: "6,6",
                pane: "linesPane",
                interactive: false
            }).addTo(map);
        }
    });

    // 🔹 Marker ONT
    onts.forEach(function(item) {
        var namaClient = (item.nama_client ?? '').replace(/'/g, "\\'");
        var info = (item.info ?? '').replace(/'/g, "\\'");

        var marker = L.marker([item.lat, item.lng], { icon: ontIcon })
            .addTo(map)
            .bindPopup(`
                <div class="custom-popup">
                    <h5>${namaClient}</h5>
                    <small>ONT</small>
                    <div class="info"><i class="fa-solid fa-location-dot"></i> ${item.lat}, ${item.lng}</div>
                    <div class="info"><i class="fa-solid fa-server"></i> ODP: ${item.odp_id ?? '-'}</div>
                    <div class="info"><b>Deskripsi:</b> ${info ?? '-'}</div>
                    <div>
                        <button class="btn btn-sm btn-edit" onclick="editLokasi('ONT', ${item.id}, '${item.kode ?? ''}', '${item.nama ?? ''}', '${item.lat}', '${item.lng}', '${info}', ${item.odp_id ?? null})">
                            <i class="fa-solid fa-pen"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-hapus"><i class="fa-solid fa-trash"></i> Hapus</button>
                    </div>
                </div>
            `);

        // Hubungkan ONT ke ODP
        if(item.odp_id && odpMarkers[item.odp_id]) {
            var odpLatLng = odpMarkers[item.odp_id].getLatLng();
            var ontLatLng = marker.getLatLng();

            var adjustedOntLatLng = L.latLng(
                ontLatLng.lat + 0.00005,
                ontLatLng.lng + 0.00005
            );

            L.polyline([odpLatLng, adjustedOntLatLng], {
                color: "blue",
                weight: 3,
                opacity: 0.9,
                dashArray: "6,6",
                pane: "linesPane",
                interactive: false
            }).addTo(map);
        }
    });

    // 🔹 Toggle Select sesuai tipe
    document.getElementById("tipe").addEventListener("change", function() {
        let tipe = this.value;
        document.getElementById("odcSelect").style.display = (tipe === "ODP") ? "block" : "none";
        document.getElementById("odpSelect").style.display = (tipe === "ONT") ? "block" : "none";
        document.getElementById("ontFields").style.display = (tipe === "ONT") ? "block" : "none";
    });

    // 🔹 Fungsi Pencarian
    function cariLokasi() {
        var keyword = document.getElementById("searchInput").value.toLowerCase();
        var found = false;

        // ODC
        Object.values(odcMarkers).forEach(marker => {
            var popupContent = marker.getPopup().getContent().toLowerCase();
            if(popupContent.includes(keyword)) {
                map.setView(marker.getLatLng(), 17);
                marker.openPopup();
                found = true;
            }
        });

        // ODP & ONT
        if(!found) {
            map.eachLayer(layer => {
                if(layer instanceof L.Marker && layer.getPopup) {
                    var popupContent = layer.getPopup().getContent().toLowerCase();
                    if(popupContent.includes(keyword)) {
                        map.setView(layer.getLatLng(), 17);
                        layer.openPopup();
                        found = true;
                    }
                }
            });
        }

        if(!found) alert("Lokasi tidak ditemukan!");
    }

    document.getElementById("btnSearch").addEventListener("click", cariLokasi);
    document.getElementById("searchInput").addEventListener("keyup", function(e) {
        if(e.key === "Enter") cariLokasi();
    });

    // 🔹 Tombol Refresh
    document.getElementById("btnRefresh").addEventListener("click", function() {
        location.reload();
    });
    document.getElementById("btnRefreshTop").addEventListener("click", function() {
        location.reload();
    });

    // 🔹 Animasi stat-card
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('click', () => {
            card.classList.add('clicked');
            setTimeout(() => card.classList.remove('clicked'), 300);
        });
    });
});

// 🔹 Fungsi Modal
function openModal(id) { document.getElementById(id).style.display = "flex"; }
function closeModal(id) { document.getElementById(id).style.display = "none"; }

// 🔹 Fungsi Tambah / Edit
function tambahLokasi() {
    document.getElementById("formTitle").innerText = "Tambah Lokasi Baru";
    document.getElementById("form_id").value = "";
    document.getElementById("lokasiForm").reset();
    document.getElementById("odcSelect").style.display = "none";
    document.getElementById("odpSelect").style.display = "none";
    document.getElementById("ontFields").style.display = "none";
    document.getElementById("lokasiForm").action = "{{ route('lokasi.store') }}";
    document.getElementById("lokasiForm").method = "POST";
    let oldMethod = document.querySelector("#lokasiForm input[name='_method']");
    if(oldMethod) oldMethod.remove();
    openModal('formLokasiModal');
}

function editLokasi(type, id, kode, nama, lat, lng, info, parent_id = null) {
    document.getElementById("formTitle").innerText = "Edit Lokasi";
    document.getElementById("form_id").value = id;
    document.getElementById("tipe").value = type;
    document.getElementById("kode").value = kode;
    document.getElementById("nama").value = nama;
    document.getElementById("lat").value = lat;
    document.getElementById("lng").value = lng;
    document.getElementById("info").value = info ?? '';

    document.getElementById("odcSelect").style.display = (type === "ODP") ? "block" : "none";
    document.getElementById("odpSelect").style.display = (type === "ONT") ? "block" : "none";
    document.getElementById("ontFields").style.display = (type === "ONT") ? "block" : "none";

    if(type === "ODP") document.getElementById("odc_id").value = parent_id ?? '';
    if(type === "ONT") document.getElementById("odp_id").value = parent_id ?? '';

    let form = document.getElementById("lokasiForm");
    form.action = "{{ url('lokasi') }}/" + id;
    form.method = "POST";

    let oldMethod = document.querySelector("#lokasiForm input[name='_method']");
    if(oldMethod) oldMethod.remove();

    let methodInput = document.createElement("input");
    methodInput.type = "hidden";
    methodInput.name = "_method";
    methodInput.value = "PUT";
    form.appendChild(methodInput);

    openModal('formLokasiModal');
}

</script>
@endpush

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 space-y-4">
    <!-- Header -->
    <div class="bg-white p-4 rounded-xl shadow">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard.index') }}"
                   class="bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white font-semibold px-4 py-2 rounded shadow flex items-center gap-2 text-sm">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-people-fill text-blue-600 text-lg"></i>
                        <span class="text-blue-600 text-lg font-semibold">Daftar Pelanggan</span>
                    </div>
                    <span class="mt-1 bg-purple-600 text-white text-xs font-medium px-2 py-0.5 rounded-full flex items-center gap-1 w-max">
                        <i class="bi bi-wifi"></i> DHS
                    </span>
                </div>
            </div>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('customers.index') }}" class="flex flex-wrap items-center gap-2">
            <input id="searchInput" name="search" type="text" value="{{ request('search') }}"
                   placeholder="Cari nama, nomor telepon, area, atau ID Pelanggan..."
                   class="flex-grow px-4 py-2 rounded-l border shadow-sm focus:outline-none focus:ring focus:ring-purple-300 
                          text-gray-800 caret-gray-800 bg-white" />
            <button type="submit" 
                class="bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white px-4 py-2 rounded-r shadow text-sm flex items-center gap-1">
                <i class="bi bi-search"></i> CARI
            </button>
            <a href="{{ route('customers.index', ['reset' => true]) }}" 
               class="text-gray-600 hover:text-red-500 px-3 py-2 text-sm flex items-center gap-1 border rounded shadow-sm">
                <i class="bi bi-x-circle"></i> RESET
            </a>
        </form>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Error ekspor data:</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.remove();">
            &times;
        </span>
    </div>
    @endif

    <!-- Tombol Aksi -->
    <div class="bg-white p-3 rounded-xl shadow flex flex-wrap items-center justify-between gap-2">

        <div class="flex gap-2">
            <a href="{{ route('customers.export') }}" 
               class="bg-cyan-600 hover:bg-cyan-700 text-white px-3 py-1.5 rounded shadow text-xs inline-flex items-center gap-1">
                <i class="bi bi-download"></i> EKSPOR
            </a>
            <!-- Tombol IMPOR -->
            <button type="button" onclick="openImportAlert()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded shadow text-xs inline-flex items-center gap-1">
                <i class="bi bi-upload"></i> IMPOR
            </button>
            <a href="{{ route('customers.create') }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded shadow text-xs inline-flex items-center gap-1">
                <i class="bi bi-person-plus-fill"></i> TAMBAH
            </a>
        </div>
    </div>

    <!-- Statistik Ringkas -->
   <!-- Tombol Aksi -->
<div class="grid grid-cols-2 md:grid-cols-6 gap-4">
    <div class="bg-white shadow rounded-xl px-4 py-3 text-center">
        <div class="text-blue-600 text-sm">Total Pelanggan</div>
        <div class="text-2xl font-bold text-blue-700">{{ $totalPelanggan ?? 0 }}</div>
    </div>
    <div class="bg-white shadow rounded-xl px-4 py-3 text-center">
        <div class="text-green-600 text-sm">Aktif</div>
        <div class="text-2xl font-bold text-green-700">{{ $aktif ?? 0 }}</div>
    </div>
    <div class="bg-white shadow rounded-xl px-4 py-3 text-center">
        <div class="text-yellow-600 text-sm">Isolir</div>
        <div class="text-2xl font-bold text-yellow-700">{{ $isolir ?? 0 }}</div>
    </div>
    <div class="bg-white shadow rounded-xl px-4 py-3 text-center">
        <div class="text-emerald-600 text-sm">Lunas</div>
        <div class="text-2xl font-bold text-emerald-700">{{ $lunas ?? 0 }}</div>
    </div>
    <div class="bg-white shadow rounded-xl px-4 py-3 text-center">
        <div class="text-orange-600 text-sm">Tunggakan 1 Bln</div>
        <div class="text-2xl font-bold text-orange-700">{{ $tunggakan1 ?? 0 }}</div>
    </div>
    <div class="bg-white shadow rounded-xl px-4 py-3 text-center">
        <div class="text-red-600 text-sm">Tunggakan 2+ Bln</div>
        <div class="text-2xl font-bold text-red-700">{{ $tunggakan2 ?? 0 }}</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white shadow rounded-xl px-4 py-3 text-center">
        <div class="text-cyan-600 text-sm">Belum Lunas</div>
        <div class="text-2xl font-bold text-cyan-700">{{ $bayarParsial ?? 0 }}</div>
    </div>
    <div class="bg-white shadow rounded-xl px-4 py-3 text-center">
        <div class="text-sky-600 text-sm">Pelanggan Baru</div>
        <div class="text-2xl font-bold text-sky-700">{{ $pelangganBaru ?? 0 }}</div>
    </div>
</div>


    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-2">
         <button onclick="openAreaFilter()" class="flex items-center gap-1 bg-white hover:bg-gray-100 border px-3 py-1 rounded-md text-gray-700 shadow-sm text-xs">
        <i class="bi bi-geo-alt text-sm"></i> Filter Area
    </button>
         <button onclick="openPackageFilter()" class="flex items-center gap-1 bg-white hover:bg-gray-100 border px-3 py-1 rounded-md text-gray-700 shadow-sm text-xs">
        <i class="bi bi-box text-sm"></i> Filter Paket
    </button>
        <button onclick="openStatusFilter()" class="flex items-center gap-1 bg-white hover:bg-gray-100 border px-3 py-1 rounded-md text-gray-700 shadow-sm text-xs">
        <i class="bi bi-check-circle text-sm"></i> Filter Status Bayar
    </button>
        <!-- Tombol Filter Tanggal -->
        <button type="button" onclick="openDateFilter()" 
            class="flex items-center gap-1 bg-white hover:bg-gray-100 border px-3 py-1 rounded-md text-gray-700 shadow-sm text-xs">
            <i class="bi bi-calendar text-sm"></i> Filter Tanggal Tagihan
        </button>
    </div>

    <!-- Data Table -->
    <div class="bg-white shadow-md rounded-xl overflow-x-auto">
        <div class="bg-gradient-to-r from-purple-700 to-indigo-700 text-white px-4 py-3 rounded-t-xl font-semibold flex items-center gap-2">
            <i class="bi bi-people-fill"></i> Data Pelanggan
        </div>
        <table class="min-w-full text-sm text-left table-auto">
           <thead class="bg-gray-200 text-gray-800 font-semibold">
    <tr>
        <th class="px-4 py-2">No</th>
        <th class="px-4 py-2">Nama Pelanggan</th>
        <th class="px-4 py-2">Koneksi</th>
        <th class="px-4 py-2">Paket</th>
        <th class="px-4 py-2">Area</th>
        <th class="px-4 py-2">Biaya</th>
        <th class="px-4 py-2">Status Pembayaran</th>
        <th class="px-4 py-2">Aksi</th>
    </tr>
</thead>

            <tbody class="divide-y">
    @forelse($customers as $index => $customer)
        <tr class="bg-white hover:bg-gray-50">
            <td class="px-4 py-2 text-gray-800">{{ $customer->id }}</td>
            <td class="px-4 py-2 text-gray-800">{{ $customer->nama }}</td>
            <td class="px-4 py-2 text-gray-800">{{ $customer->koneksi }}</td>
            <td class="px-4 py-2 text-gray-800">{{ $customer->package->nama_paket ?? '-' }}</td>
            <td class="px-4 py-2 text-gray-800">{{ $customer->area->nama_area ?? '-' }}</td>
            <td class="px-4 py-2 text-gray-800">Rp {{ number_format($customer->biaya, 0, ',', '.') }}</td>
            <td class="px-4 py-2 text-gray-800">{{ $customer->status_pembayaran }}</td>
<td class="px-4 py-2 text-center">
    <div class="flex justify-center items-center gap-2">
        <!-- Tombol Edit -->
        <button class="text-blue-600 hover:text-blue-800"
            data-id="{{ $customer->id }}"
            data-nama="{{ $customer->nama }}"
            data-telepon="{{ $customer->nomor_telepon }}"
            data-email="{{ $customer->email }}"
            data-alamat="{{ $customer->alamat_lengkap }}"
            data-koneksi="{{ $customer->koneksi }}"
            data-package_id="{{ $customer->package_id }}"
            data-area_id="{{ $customer->area_id }}"
            data-status="{{ $customer->status_pembayaran }}"
            data-tanggal_instalasi="{{ $customer->tanggal_instalasi ? \Carbon\Carbon::parse($customer->tanggal_instalasi)->format('Y-m-d') : '' }}"
            data-tanggal_tagihan="{{ $customer->tanggal_tagihan ? \Carbon\Carbon::parse($customer->tanggal_tagihan)->format('Y-m-d') : '' }}"
            data-catatan="{{ $customer->catatan ?? '' }}">
            <i class="fas fa-edit"></i>
        </button>

        <!-- Tombol Hapus -->
        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    </div>
</td>

        </tr>
    @empty
        <tr>
            <td colspan="10" class="text-center px-4 py-6 text-gray-500">
                <div class="flex flex-col items-center">
                    <i class="bi bi-card-list text-4xl text-gray-400 mb-2"></i>
                    Belum ada data pelanggan
                    <a href="{{ route('customers.create') }}" class="mt-3 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">Tambah Pelanggan Baru</a>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>

   <!-- ==================== MODAL EDIT LENGKAP ==================== -->
<div id="editModal" class="fixed inset-0 bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl p-6 overflow-y-auto max-h-[90vh] relative">
        <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-500">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="text-lg font-bold text-blue-600 mb-4">Edit Data Pelanggan</h2>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Nama Lengkap *</label>
                    <input type="text" name="nama" id="editNama" required
                           class="mt-1 p-2 w-full border rounded bg-white text-gray-800">
                </div>

                <!-- Telepon -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Nomor Telepon *</label>
                    <input type="text" name="nomor_telepon" id="editTelepon" required
                           class="mt-1 p-2 w-full border rounded bg-white text-gray-800">
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="editEmail"
                           class="mt-1 p-2 w-full border rounded bg-white text-gray-800">
                </div>

                <!-- Koneksi -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Tipe Koneksi *</label>
                    <select name="koneksi" id="editKoneksi" required
                            class="mt-1 p-2 w-full border rounded bg-white text-gray-800">
                        <option value="PPPoE">PPPoE</option>
                        <option value="Hotspot">Hotspot</option>
                        <option value="Static IP">Static IP</option>
                    </select>
                </div>

                <!-- Paket -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Paket Internet *</label>
                    <select name="package_id" id="editPackage" required
                            class="mt-1 p-2 w-full border rounded bg-white text-gray-800">
                        <option value="">-- Pilih Paket --</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->nama_paket }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Area -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Area Layanan *</label>
                    <select name="area_id" id="editArea" required
                            class="mt-1 p-2 w-full border rounded bg-white text-gray-800">
                        <option value="">-- Pilih Area --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->nama_area }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Alamat Lengkap *</label>
                    <textarea name="alamat_lengkap" id="editAlamat" rows="2" required
                              class="mt-1 p-2 w-full border rounded bg-white text-gray-800"></textarea>
                </div>

                <!-- Tanggal Instalasi -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Tanggal Instalasi</label>
                    <input type="date" name="tanggal_instalasi" id="editTanggalInstalasi"
                           class="mt-1 p-2 w-full border rounded bg-white text-gray-800">
                </div>

                <!-- Tanggal Tagihan -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Tanggal Tagihan</label>
                    <input type="date" name="tanggal_tagihan" id="editTanggalTagihan"
                           class="mt-1 p-2 w-full border rounded bg-white text-gray-800">
                </div>

                <!-- Status Pembayaran -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Status Pembayaran</label>
                    <select name="status_pembayaran" id="editStatus"
                            class="mt-1 p-2 w-full border rounded bg-white text-gray-800">
                        <option value="Belum Lunas">Belum Lunas</option>
                        <option value="Lunas">Lunas</option>
                        <option value="Parsial">Parsial</option>
                    </select>
                </div>

                <!-- Catatan -->
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="catatan" id="editCatatan" rows="2"
                              class="mt-1 p-2 w-full border rounded bg-white text-gray-800"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function openImportAlert() {
    Swal.fire({
        title: 'Impor Data Pelanggan',
        html: `
            <form id="importForm" action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file" accept=".csv" 
                    class="form-control mb-3" required>
                <small class="text-muted">
                    Unggah file CSV dengan format yang sesuai.<br>
                    Area names akan dinormalisasi otomatis.<br>
                    Payment status akan dihitung.
                </small>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-cloud-arrow-up"></i> Impor Data',
        cancelButtonText: 'Batal',
        focusConfirm: false,
        showCloseButton: true, // tombol "X"
        preConfirm: () => {
            document.getElementById('importForm').submit();
        }
    });
}

function openDateFilter() {
    Swal.fire({
        title: 'Filter Tanggal Tagihan',
        html: `
            <form id="dateFilterForm" action="{{ route('customers.index') }}" method="GET" class="text-left">
                <div class="mb-3">
                    <label for="start_date" class="block text-sm font-medium">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date"
                        class="form-control w-full px-3 py-2 border rounded mt-1" />
                </div>
                <div class="mb-3">
                    <label for="end_date" class="block text-sm font-medium">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date"
                        class="form-control w-full px-3 py-2 border rounded mt-1" />
                </div>
                <small class="text-gray-500">Filter pelanggan berdasarkan rentang tanggal tagihan. Kosongkan untuk semua tanggal.</small>
            </form>
        `,
        showCancelButton: true,
        cancelButtonText: 'Batal',
        showDenyButton: true,
        denyButtonText: 'Hapus Filter',
        confirmButtonText: 'Terapkan Filter',
        focusConfirm: false,
        showCloseButton: true, // tombol "X"
        customClass: {
            confirmButton: 'bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700',
            denyButton: 'bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500',
            cancelButton: 'bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400',
        },
        preConfirm: () => {
            document.getElementById('dateFilterForm').submit();
        }
    }).then((result) => {
        if (result.isDenied) {
            window.location.href = "{{ route('customers.index', ['reset_date' => true]) }}";
        }
    });
}

// ✅ Notifikasi pencarian kosong
const isSearch = JSON.parse(`{!! json_encode(request('search') ? true : false) !!}`);
const isEmpty = JSON.parse(`{!! json_encode($customers->isEmpty()) !!}`);

if (isSearch && isEmpty) {
    Swal.fire({
        icon: 'warning',
        title: 'Tidak ditemukan',
        text: 'Hasil pencarian tidak ditemukan!',
        timer: 2500,
        showConfirmButton: false,
        showCloseButton: true // tombol "X"
    });
}

function openAreaFilter() {
    Swal.fire({
        title: 'Filter Berdasarkan Area',
        html: `
            <form id="areaFilterForm" action="{{ route('customers.index') }}" method="GET" class="text-left">
                <div class="mb-3">
                    <label for="area_id" class="block text-sm font-medium">Pilih Area</label>
                    <select id="area_id" name="area_id" class="form-control w-full px-3 py-2 border rounded mt-1">
                        <option value="">-- Semua Area --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
    {{ $area->nama_area }} ({{ $area->customers_count }} pelanggan)
</option>

                        @endforeach
                    </select>
                </div>
                <small class="text-gray-500">
                    Filter pelanggan berdasarkan area. Pilih "-- Semua Area --" untuk menampilkan semua.
                </small>
            </form>
        `,
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonText: 'Terapkan Filter',
        focusConfirm: false,
        showCloseButton: true, // tombol "X" di pojok kanan atas
        customClass: {
            confirmButton: 'bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700',
            cancelButton: 'bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400',
        },
        preConfirm: () => {
            document.getElementById('areaFilterForm').submit();
        }
    });
}


function openPackageFilter() {
    Swal.fire({
        title: 'Filter Berdasarkan Paket',
        html: `
            <form id="packageFilterForm" action="{{ route('customers.index') }}" method="GET" class="text-left">
                <div class="mb-3">
                    <label for="package_id" class="block text-sm font-medium">Pilih Paket</label>
                    <select id="package_id" name="package_id" class="form-control w-full px-3 py-2 border rounded mt-1">
                        <option value="">-- Semua Paket --</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->nama_paket }} ({{ $package->customers_count }} pelanggan)
                            </option>
                        @endforeach
                    </select>
                </div>
                <small class="text-gray-500">
                    Filter pelanggan berdasarkan paket langganan. Pilih "-- Semua Paket --" untuk menampilkan semua.
                </small>
            </form>
        `,
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonText: 'Terapkan Filter',
        focusConfirm: false,
        showCloseButton: true, // tombol "X" di pojok kanan atas
        customClass: {
            confirmButton: 'bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700',
            cancelButton: 'bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400',
        },
        preConfirm: () => {
            document.getElementById('packageFilterForm').submit();
        }
    });
}

function openStatusFilter() {
    Swal.fire({
        title: 'Filter Status Pembayaran',
        html: `
            <form id="statusFilterForm" action="{{ route('customers.index') }}" method="GET" class="text-left">
                <div class="mb-3">
                    <label for="status_pembayaran" class="block text-sm font-medium">Pilih Status</label>
                    <select id="status_pembayaran" name="status_pembayaran" class="form-control w-full px-3 py-2 border rounded mt-1">
                        <option value="">-- Semua Status --</option>
                        <option value="Lunas" {{ request('status_pembayaran') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="Belum Lunas" {{ request('status_pembayaran') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="Parsial" {{ request('status_pembayaran') == 'Parsial' ? 'selected' : '' }}>Parsial</option>
                    </select>
                </div>
                <small class="text-gray-500">
                    Filter pelanggan berdasarkan status pembayaran.
                </small>
            </form>
        `,
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonText: 'Terapkan Filter',
        focusConfirm: false,
        showCloseButton: true, // tombol "X"
        customClass: {
            confirmButton: 'bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700',
            cancelButton: 'bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400',
        },
        preConfirm: () => {
            document.getElementById('statusFilterForm').submit();
        }
    });
}
const editModal = document.getElementById('editModal');
const editForm = document.getElementById('editForm');

document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;

        document.getElementById('editNama').value = this.dataset.nama;
        document.getElementById('editTelepon').value = this.dataset.telepon;
        document.getElementById('editEmail').value = this.dataset.email;
        document.getElementById('editAlamat').value = this.dataset.alamat;
        document.getElementById('editKoneksi').value = this.dataset.koneksi;
        document.getElementById('editPackage').value = this.dataset.package_id;
        document.getElementById('editArea').value = this.dataset.area_id;
        document.getElementById('editStatus').value = this.dataset.status;

        // 🟢 PENTING: input[type=date] harus format 'YYYY-MM-DD'
document.getElementById('editTanggalInstalasi').value = this.dataset.tanggal_instalasi || '';
document.getElementById('editTanggalTagihan').value = this.dataset.tanggal_tagihan || '';


        document.getElementById('editCatatan').value = this.dataset.catatan || '';

        editForm.action = `/customers/${id}`;
        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
    });
});


function closeModal() {
    editModal.classList.remove('flex');
    editModal.classList.add('hidden');
}
</script>

@endsection
    
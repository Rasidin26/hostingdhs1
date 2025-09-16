@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    
    <!-- 🔹 Header -->
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

            <!-- Kiri: Tombol Kembali + Judul -->
            <div class="flex items-start gap-4">
                <!-- Tombol Kembali -->
                <a href="{{ url()->previous() }}"
                   class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                <!-- Judul + Subjudul -->
                <div class="flex flex-col">
                    <h2 class="text-xl font-bold text-purple-600 flex items-center gap-2">
                        <i class="fas fa-plug"></i>
                        Data Pasang Baru
                    </h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Daftar pelanggan pasang baru listrik / pdam / internet
                    </p>
                </div>
            </div>

            <!-- Kanan: Waktu -->
            <div class="flex items-center">
                <div class="flex items-center bg-cyan-100 text-cyan-600 px-3 py-1 rounded-full text-sm font-medium">
                    <i class="fas fa-clock mr-2"></i>
                    <span id="realTimeClock">{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔹 Filter Periode -->
    <div class="bg-white shadow rounded-xl p-6 mb-6 flex flex-wrap items-center justify-between gap-3">
        <!-- Label Periode -->
        <div>
            <span class="text-gray-600">Filter Periode:</span>
            <span class="ml-2 font-bold text-blue-600">
                {{ $bulan ?? \Carbon\Carbon::now()->translatedFormat('F Y') }}
            </span>
        </div>

        <!-- Aksi -->
        <div class="flex gap-2">
            <!-- Tombol Bulan Ini -->
            <!-- <button class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg flex items-center gap-2">
                <i class="fas fa-home text-xs"></i> Bulan Ini
            </button> -->

            <!-- Tombol Export -->
<a href="{{ route('pasangbaru.export') }}" 
   class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg flex items-center gap-2">
   <i class="fas fa-file-export text-xs"></i> Export
</a>

        </div>
    </div>
<!-- 🔹 Card Ringkasan -->
<div class="bg-white shadow rounded-xl p-6 mb-6 relative">
    <!-- Total di pojok kanan atas -->
<span class="absolute top-3 right-3 bg-red-500 text-white px-4 py-1.5 rounded-full font-semibold flex items-center gap-2 text-sm shadow">
    <i class="fas fa-coins text-yellow-300 text-xs"></i>
    Total: Rp {{ number_format($totalBiaya, 0, ',', '.') }}
</span>



    <!-- Tombol Aksi -->
    <div class="flex gap-2">
        <!-- Tombol Buka Modal -->
        <button onclick="openModal()" 
                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Rincian
        </button>

    <a href="{{ url()->previous() }}"
           class="px-4 py-2 border border-blue-500 text-blue-500 rounded-lg hover:bg-blue-50 flex items-center gap-2">
            <i class="fas fa-list"></i> Lihat Semua Kategori
        </a>
    </div>
</div>

<!-- 🔹 Modal Tambah Rincian -->
<div id="modalRincian" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 relative">
        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="text-lg font-bold text-green-600 flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Tambah Pasang Baru
            </h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('pasangbaru.store') }}" method="POST">
            @csrf
            <!-- Nama & Alamat -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Nama Pelanggan *</label>
                    <input type="text" name="nama_pelanggan" 
                           placeholder="Nama lengkap pelanggan"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700 placeholder-gray-400" 
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Alamat *</label>
                    <input type="text" name="alamat" 
                           placeholder="Alamat pelanggan"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700 placeholder-gray-400" 
                           required>
                </div>
            </div>

            <!-- Jenis & Biaya -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Jenis Pasang *</label>
<select id="editJenis" name="jenis_pasang" class="w-full ...">
    <option value="">-- Pilih Jenis --</option>
    <option value="Listrik">PPPoE</option>
    <option value="PDAM">Hotspot</option>
    <option value="Internet">Static IP</option>
</select>

                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Biaya *</label>
                    <input type="number" name="biaya" 
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" 
                           value="0" required>
                </div>
            </div>

            <!-- Tanggal -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600">Tanggal *</label>
                <input type="date" name="tanggal" 
                       value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                       class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" 
                       required>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center gap-2">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>


<!-- 🔹 Modal Edit Pasang Baru -->
<div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 relative">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="text-lg font-bold text-yellow-600">Edit Pasang Baru</h2>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="formEditPasang" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Nama Pelanggan *</label>
                    <input type="text" id="editNama" name="nama_pelanggan" 
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Alamat *</label>
                    <input type="text" id="editAlamat" name="alamat" 
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Jenis Pasang *</label>
                    <select id="editJenisPasang" name="jenis_pasang" 
                            class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="PPPoE">PPPoE</option>
                        <option value="Hotspot">Hotspot</option>
                        <option value="Static IP">Static IP</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Biaya *</label>
                    <input type="number" id="editBiaya" name="biaya" 
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600">Tanggal *</label>
                <input type="date" id="editTanggal" name="tanggal" 
                       class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" required>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>




   <!-- 🔹 Tabel Data -->
<div class="bg-white shadow rounded-xl p-6">
    <h3 class="text-lg font-bold text-blue-600 flex items-center gap-2 mb-4">
        <i class="fas fa-table"></i> Daftar Pasang Baru
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 text-sm text-gray-700">
            <thead>
                <tr class="bg-gray-100 text-gray-800">
                    <th class="px-4 py-2 text-left">Nama Pelanggan</th>
                    <th class="px-4 py-2 text-left">Alamat</th>
                    <th class="px-4 py-2 text-left">Jenis</th>
                    <th class="px-4 py-2 text-right">Biaya</th>
                    <th class="px-4 py-2 text-center">Tanggal</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $row)
                <tr class="hover:bg-gray-50 transition">
                    <td class="border px-4 py-2">{{ $row->nama_pelanggan }}</td>
                    <td class="border px-4 py-2">{{ $row->alamat }}</td>
                    <td class="border px-4 py-2">{{ $row->jenis_pasang }}</td>
                    <td class="border px-4 py-2 text-right">Rp {{ number_format($row->biaya, 0, ',', '.') }}</td>
                    <td class="border px-4 py-2 text-center">{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                  <td class="border px-4 py-2 text-center">
    <!-- Tombol Edit -->
    <button 
        onclick='openEditModal(@json($row))' 
        class="text-blue-500 hover:underline font-medium">
        Edit
    </button>
    
    <span class="mx-1 text-gray-400">|</span> <!-- separator -->

    <!-- Tombol Hapus -->
    <form action="{{ route('pasangbaru.destroy', $row->id) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-500 hover:underline font-medium" 
                onclick="return confirm('Yakin hapus data ini?')">
            Hapus
        </button>
    </form>
</td>


                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>

<script>
    function updateClock() {
        const now = new Date();
        const formatted = now.toLocaleDateString('id-ID') + ' ' + 
                          now.toLocaleTimeString('id-ID');
        document.getElementById('realTimeClock').textContent = formatted;
    }
    setInterval(updateClock, 1000);
    updateClock();
        function openModal() {
        document.getElementById('modalRincian').classList.remove('hidden');
        document.getElementById('modalRincian').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('modalRincian').classList.add('hidden');
        document.getElementById('modalRincian').classList.remove('flex');
    }

 function openEditModal(row) {
    document.getElementById('modalEdit').classList.remove('hidden');
    document.getElementById('modalEdit').classList.add('flex');

    document.getElementById('editNama').value = row.nama_pelanggan;
    document.getElementById('editAlamat').value = row.alamat;
    document.getElementById('editJenisPasang').value = row.jenis_pasang;
    document.getElementById('editBiaya').value = row.biaya;
    document.getElementById('editTanggal').value = row.tanggal;

    document.getElementById('formEditPasang').action = '/pasangbaru/' + row.id;
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
    document.getElementById('modalEdit').classList.remove('flex');
}
</script>
@endsection

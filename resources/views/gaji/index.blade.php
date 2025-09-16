@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
<!-- 🔹 Header -->
<div class="bg-white shadow rounded-xl p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

        <!-- Kiri: Button + Judul -->
        <div class="flex items-start gap-4">
            <!-- Tombol Kembali -->
            <a href="{{ url()->previous() }}"
               class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali 
            </a>

            <!-- Judul + Subjudul -->
            <div class="flex flex-col">
                <h2 class="text-xl font-bold text-yellow-500 flex items-center gap-2">
                    <i class="fas fa-users"></i>
                    Rincian Gaji Karyawan
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    Detail pengeluaran untuk September 2025
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
<div class="bg-white shadow rounded-xl p-6 mb-6">
    <div class="flex flex-wrap gap-3 items-center justify-between">
        
        <!-- Label Periode -->
        <div>
            <span class="text-gray-600">Filter Periode:</span>
            <span class="ml-2 font-bold text-blue-600">
                {{ $bulan ?? \Carbon\Carbon::now()->translatedFormat('F Y') }}
            </span>
        </div>

        <!-- Aksi -->
        <div class="flex gap-2">
            <!-- Tombol Bulan Ini
            <button class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg flex items-center gap-2">
                <i class="fas fa-home text-xs"></i> Bulan Ini
            </button> -->

            <!-- Tombol Export -->
<a href="{{ route('gaji.export') }}" 
   class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg flex items-center gap-2">
   <i class="fas fa-file-export text-xs"></i> Export
</a>


        </div>
    </div>
</div>
<!-- 🔹 Card Ringkasan -->
<div class="bg-white shaadow rounded-xl p-6 mb-6 relative">
    <!-- Total di pojok kanan atas -->
<span class="absolute top-3 right-3 bg-red-500 text-white px-4 py-1.5 rounded-full font-semibold flex items-center gap-2 text-sm shadow">
    <i class="fas fa-coins text-yellow-300 text-xs"></i>
    Total: Rp {{ number_format($pengeluaran, 0, ',', '.') }}
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

<!-- 🔹 Modal -->
<div id="modalRincian" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 relative">
        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="text-lg font-bold text-green-600 flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Tambah Rincian Gaji
            </h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('gaji.store') }}" method="POST">
            @csrf
            <!-- Nama & Jabatan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Nama *</label>
                    <input type="text" name="nama_karyawan" 
                           placeholder="Nama karyawan"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700 placeholder-gray-400" 
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Jabatan *</label>
                    <input type="text" name="jabatan" 
                           placeholder="Contoh: Teknisi, Admin"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700 placeholder-gray-400" 
                           required>
                </div>
            </div>

            <!-- Gaji Pokok & Tunjangan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Gaji Pokok *</label>
                    <input type="number" name="gaji_pokok" id="gajiPokok"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" 
                           value="0" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Tunjangan</label>
                    <input type="number" name="tunjangan" id="tunjangan"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" 
                           value="0">
                </div>
            </div>

            <!-- Potongan & Total Gaji -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Potongan</label>
                    <input type="number" name="potongan" id="potongan"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" 
                           value="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Total Gaji</label>
                    <input type="text" id="totalGaji"
                           class="w-full border rounded-lg px-3 py-2 mt-1 bg-gray-100 text-gray-700 font-bold" 
                           readonly>
                </div>
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

<!-- 🔹 Modal Edit Rincian Gaji -->
<div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 relative">
        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="text-lg font-bold text-yellow-600 flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit Rincian Gaji
            </h2>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Form Edit -->
        <form id="formEditGaji" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama & Jabatan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Nama *</label>
                    <input type="text" name="nama_karyawan" id="editNama"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700"
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Jabatan *</label>
                    <input type="text" name="jabatan" id="editJabatan"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700"
                           required>
                </div>
            </div>

            <!-- Gaji Pokok & Tunjangan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Gaji Pokok *</label>
                    <input type="number" name="gaji_pokok" id="editGajiPokok"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700"
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Tunjangan</label>
                    <input type="number" name="tunjangan" id="editTunjangan"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700">
                </div>
            </div>

            <!-- Potongan & Total Gaji -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Potongan</label>
                    <input type="number" name="potongan" id="editPotongan"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Total Gaji</label>
                    <input type="text" id="editTotalGaji"
                           class="w-full border rounded-lg px-3 py-2 mt-1 bg-gray-100 text-gray-700 font-bold"
                           readonly>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center gap-2">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>


   <!-- 🔹 Tabel Data -->
<div class="bg-white shadow rounded-xl p-6">
    <!-- Judul -->
    <h3 class="text-lg font-bold text-blue-600 flex items-center gap-2 mb-4">
        <i class="fas fa-table"></i> Daftar Rincian Pengeluaran
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 text-sm text-gray-700">
            <thead>
                <tr class="bg-gray-100 text-gray-800">
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Jabatan</th>
                    <th class="px-4 py-2 text-right">Gaji Pokok</th>
                    <th class="px-4 py-2 text-right">Tunjangan</th>
                    <th class="px-4 py-2 text-right">Potongan</th>
                    <th class="px-4 py-2 text-right">Total Gaji</th>
                    <th class="px-4 py-2 text-center">Aksi</th>

                </tr>
            </thead>
<tbody>
    @foreach ($gajis as $gaji)
        <tr class="hover:bg-gray-50 transition">
            <td class="border px-4 py-2">{{ $gaji->nama_karyawan }}</td>
            <td class="border px-4 py-2">{{ $gaji->jabatan }}</td>
            <td class="border px-4 py-2 text-right">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
            <td class="border px-4 py-2 text-right">Rp {{ number_format($gaji->tunjangan, 0, ',', '.') }}</td>
            <td class="border px-4 py-2 text-right">Rp {{ number_format($gaji->potongan, 0, ',', '.') }}</td>
            <td class="border px-4 py-2 font-bold text-right text-green-600">
                Rp {{ number_format($gaji->total_gaji, 0, ',', '.') }}
            </td>
           <td class="border px-4 py-2 text-center">
    <button onclick='openEditModal(@json($gaji))' 
            class="text-blue-500 hover:underline">Edit</button> | 
    <form action="{{ route('gaji.destroy', $gaji->id) }}" method="POST" class="inline">
        @csrf @method('DELETE')
        <button class="text-red-500 hover:underline" onclick="return confirm('Yakin?')">Hapus</button>
    </form>
</td>

        </tr>
    @endforeach
</tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $gajis->links() }}
        </div>
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

    function updateClock() {
        const now = new Date();
        const formatted = now.toLocaleDateString('id-ID') + ' ' + 
                          now.toLocaleTimeString('id-ID');
        document.getElementById('realTimeClock').textContent = formatted;
    }

    setInterval(updateClock, 1000);
    updateClock();

    function hitungTotal() {
        let pokok = parseInt(document.getElementById('gajiPokok').value) || 0;
        let tunjangan = parseInt(document.getElementById('tunjangan').value) || 0;
        let potongan = parseInt(document.getElementById('potongan').value) || 0;

        let total = pokok + tunjangan - potongan;
        document.getElementById('totalGaji').value = "Rp " + total.toLocaleString('id-ID');
    }

    document.getElementById('gajiPokok').addEventListener('input', hitungTotal);
    document.getElementById('tunjangan').addEventListener('input', hitungTotal);
    document.getElementById('potongan').addEventListener('input', hitungTotal);
     function openEditModal(gaji) {
        document.getElementById('modalEdit').classList.remove('hidden');
        document.getElementById('modalEdit').classList.add('flex');

        // Isi field dengan data
        document.getElementById('editNama').value = gaji.nama_karyawan;
        document.getElementById('editJabatan').value = gaji.jabatan;
        document.getElementById('editGajiPokok').value = gaji.gaji_pokok;
        document.getElementById('editTunjangan').value = gaji.tunjangan;
        document.getElementById('editPotongan').value = gaji.potongan;

        // Hitung total
        hitungTotalEdit();
        // Set action form ke route update
        document.getElementById('formEditGaji').action = '/gaji/' + gaji.id;
    }

    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
        document.getElementById('modalEdit').classList.remove('flex');
    }

    function hitungTotalEdit() {
        let pokok = parseInt(document.getElementById('editGajiPokok').value) || 0;
        let tunjangan = parseInt(document.getElementById('editTunjangan').value) || 0;
        let potongan = parseInt(document.getElementById('editPotongan').value) || 0;
        let total = pokok + tunjangan - potongan;
        document.getElementById('editTotalGaji').value = "Rp " + total.toLocaleString('id-ID');
    }

    document.getElementById('editGajiPokok').addEventListener('input', hitungTotalEdit);
    document.getElementById('editTunjangan').addEventListener('input', hitungTotalEdit);
    document.getElementById('editPotongan').addEventListener('input', hitungTotalEdit);
</script>

@endsection

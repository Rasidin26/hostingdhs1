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
                     <!-- Judul -->
            <h2 class="text-2xl font-bold text-blue-400 flex items-center gap-2">
                <i class="fas fa-bolt"></i>
                Rincian Listrik / PDAM / Pulsa
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
            <!-- Tombol Bulan Ini -->
            <!-- <button class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg flex items-center gap-2">
                <i class="fas fa-home text-xs"></i> Bulan Ini
            </button> -->

            <!-- Tombol Export -->
<a href="{{ route('listrik-pdam-pulsa.export') }}" 
   class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg flex items-center gap-2">
   <i class="fas fa-file-export text-xs"></i> Export
</a>

        </div>
    </div>
</div>
<!-- 🔹 Card Ringkasan -->
<div class="bg-white shadow rounded-xl p-6 mb-6 relative">
    <!-- Total di pojok kanan atas -->
<span class="absolute top-3 right-3 bg-red-500 text-white px-4 py-1.5 rounded-full font-semibold flex items-center gap-2 text-sm shadow">
    <i class="fas fa-coins text-yellow-300 text-xs"></i>
    Total: Rp {{ number_format($totalBayar, 0, ',', '.') }}
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
                <i class="fas fa-plus-circle"></i> Tambah Rincian Pembayaran
            </h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('listrik-pdam-pulsa.store') }}" method="POST">
            @csrf
            <!-- Jenis & Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Jenis *</label>
                    <select name="jenis" 
                            class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700"
                            required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Listrik">Listrik</option>
                        <option value="PDAM">PDAM</option>
                        <option value="Pulsa">Pulsa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Tanggal *</label>
                    <input type="date" name="tanggal"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" 
                           required>
                </div>
            </div>

            <!-- Jumlah -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600">Jumlah (Rp) *</label>
                <input type="number" name="jumlah" 
                       placeholder="Contoh: 150000"
                       class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" 
                       required>
            </div>

            <!-- Keterangan -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600">Keterangan</label>
                <textarea name="keterangan" rows="3" 
                          placeholder="Opsional..."
                          class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700"></textarea>
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


<!-- 🔹 Modal Edit Rincian -->
<div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 relative">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="text-lg font-bold text-yellow-600 flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit Rincian Pembayaran
            </h2>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="formEditBayar" method="POST">
            @csrf
            @method('PUT')
            <!-- Jenis & Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Jenis *</label>
                    <select id="editJenis" name="jenis"
                            class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Listrik">Listrik</option>
                        <option value="PDAM">PDAM</option>
                        <option value="Pulsa">Pulsa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Tanggal *</label>
                    <input type="date" id="editTanggal" name="tanggal"
                           class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" required>
                </div>
            </div>

            <!-- Jumlah -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600">Jumlah (Rp) *</label>
                <input type="number" id="editJumlah" name="jumlah" 
                       class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700" required>
            </div>

            <!-- Keterangan -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600">Keterangan</label>
                <textarea id="editKeterangan" name="keterangan" rows="3" 
                          class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-200 text-gray-700"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Batal
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
        <i class="fas fa-table"></i> Daftar Pembayaran
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 text-sm text-gray-700">
            <thead>
                <tr class="bg-gray-100 text-gray-800">
                    <th class="px-4 py-2 text-left">Jenis</th>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-right">Jumlah</th>
                    <th class="px-4 py-2 text-left">Keterangan</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bayars as $bayar)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="border px-4 py-2">{{ $bayar->jenis }}</td>
                        <td class="border px-4 py-2">
                            {{ \Carbon\Carbon::parse($bayar->tanggal)->format('d/m/Y') }}
                        </td>
                        <td class="border px-4 py-2 text-right">
                            Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="border px-4 py-2">{{ $bayar->keterangan ?? '-' }}</td>
                      <td class="border px-4 py-2 text-center">
    <a href="javascript:void(0)" onclick='openEditModal(@json($bayar))' 
       class="text-blue-500 hover:underline">
       Edit
    </a> | 
    <form action="{{ route('listrik-pdam-pulsa.destroy', $bayar->id) }}" 
          method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-500 hover:underline" 
                onclick="return confirm('Yakin hapus data ini?')">
            Hapus
        </button>
    </form>
</td>
</div>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $bayars->links() }}
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
    
    function openEditModal(bayar) {
    document.getElementById('modalEdit').classList.remove('hidden');
    document.getElementById('modalEdit').classList.add('flex');

    // Isi field dengan data dari row
    document.getElementById('editJenis').value = bayar.jenis;
    document.getElementById('editTanggal').value = bayar.tanggal;
    document.getElementById('editJumlah').value = bayar.jumlah;
    document.getElementById('editKeterangan').value = bayar.keterangan ?? '';

    // Set action form ke route update
    document.getElementById('formEditBayar').action = '/listrik-pdam-pulsa/' + bayar.id;
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
    document.getElementById('modalEdit').classList.remove('flex');
}

</script>

@endsection

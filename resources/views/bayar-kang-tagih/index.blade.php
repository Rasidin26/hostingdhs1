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
            <h2 class="text-2xl font-bold text-green-500 flex items-center gap-2">
                <i class="fas fa-motorcycle"></i>
                Rincian Bayar Kang Tagih
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
<a href="{{ route('bayar-kang-tagih.export') }}" 
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
<div id="modalTambah" 
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative">

        <!-- Tombol Close -->
        <button onclick="closeModal()" 
                class="absolute top-3 right-3 text-gray-500 hover:text-red-500">
            <i class="fas fa-times text-lg"></i>
        </button>

        <!-- Judul -->
        <h3 class="text-xl font-bold text-blue-600 flex items-center gap-2 mb-6">
            <i class="fas fa-pen-to-square"></i> Tambah Rincian Pembayaran
        </h3>

        <!-- Form -->
        <form action="{{ route('bayar-kang-tagih.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Nama Petugas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <i class="fas fa-user text-gray-400"></i> Nama Petugas
                </label>
                <input type="text" name="nama_petugas" required
                       class="w-full border rounded-lg px-3 py-2 text-gray-800 
                              focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <!-- Tanggal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-gray-400"></i> Tanggal
                </label>
                <input type="date" name="tanggal" required
                       class="w-full border rounded-lg px-3 py-2 text-gray-800 
                              focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <!-- Jumlah -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <i class="fas fa-coins text-yellow-500"></i> Jumlah (Rp)
                </label>
                <input type="number" name="jumlah" required
                       class="w-full border rounded-lg px-3 py-2 text-gray-800 text-right
                              focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <i class="fas fa-info-circle text-gray-400"></i> Keterangan
                </label>
                <textarea name="keterangan" rows="2"
                          class="w-full border rounded-lg px-3 py-2 text-gray-800 
                                 focus:ring-2 focus:ring-blue-400 focus:outline-none"></textarea>
            </div>

            <!-- Tombol Submit -->
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 🔹 Modal Edit Rincian Bayar -->
<div id="modalEditBayar" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative">

        <!-- Tombol Close -->
        <button onclick="closeEditModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-500">
            <i class="fas fa-times text-lg"></i>
        </button>

        <!-- Judul -->
        <h3 class="text-xl font-bold text-yellow-600 flex items-center gap-2 mb-6">
            <i class="fas fa-pen-to-square"></i> Edit Rincian Pembayaran
        </h3>

        <!-- Form Edit -->
        <form id="formEditBayar" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nama Petugas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <i class="fas fa-user text-gray-400"></i> Nama Petugas
                </label>
                <input type="text" name="nama_petugas" id="editNamaPetugas" required
                       class="w-full border rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <!-- Tanggal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-gray-400"></i> Tanggal
                </label>
                <input type="date" name="tanggal" id="editTanggal" required
                       class="w-full border rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <!-- Jumlah -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <i class="fas fa-coins text-yellow-500"></i> Jumlah (Rp)
                </label>
                <input type="number" name="jumlah" id="editJumlah" required
                       class="w-full border rounded-lg px-3 py-2 text-gray-800 text-right focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <i class="fas fa-info-circle text-gray-400"></i> Keterangan
                </label>
                <textarea name="keterangan" id="editKeterangan" rows="2"
                          class="w-full border rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-blue-400 focus:outline-none"></textarea>
            </div>

            <!-- Tombol Submit -->
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
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


<div class="bg-white shadow rounded-xl p-6">
    <!-- Judul -->
    <h3 class="text-lg font-bold text-blue-600 flex items-center gap-2 mb-4">
        <i class="fas fa-table"></i> Daftar Pembayaran Kang Tagih
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 text-sm text-gray-700">
            <thead>
                <tr class="bg-gray-100 text-gray-800">
                    <th class="px-4 py-2 text-left">Nama Petugas</th>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-right">Jumlah</th>
                    <th class="px-4 py-2 text-left">Keterangan</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bayars as $bayar)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="border px-4 py-2">{{ $bayar->nama_petugas }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($bayar->tanggal)->format('d/m/Y') }}</td>
                        <td class="border px-4 py-2 text-right">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                        <td class="border px-4 py-2">{{ $bayar->keterangan ?? '-' }}</td>
<td class="border px-4 py-2 text-center">
    <button onclick='openEditBayar(@json($bayar))' 
            class="text-blue-500 hover:underline">
        Edit
    </button> | 
    <form action="{{ route('bayar-kang-tagih.destroy', $bayar->id) }}" 
          method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button class="text-red-500 hover:underline" onclick="return confirm('Yakin?')">
            Hapus
        </button>
    </form>
</td>

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
        document.getElementById('modalTambah').classList.remove('hidden');
        document.getElementById('modalTambah').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('modalTambah').classList.add('hidden');
        document.getElementById('modalTambah').classList.remove('flex');
    }

    function openEditBayar(bayar) {
    document.getElementById('modalEditBayar').classList.remove('hidden');
    document.getElementById('modalEditBayar').classList.add('flex');

    // Isi field dengan data dari row
    document.getElementById('editNamaPetugas').value = bayar.nama_petugas;
    document.getElementById('editTanggal').value = bayar.tanggal;
    document.getElementById('editJumlah').value = bayar.jumlah;
    document.getElementById('editKeterangan').value = bayar.keterangan ?? '';

    // Set action form ke route update
    document.getElementById('formEditBayar').action = '/bayar-kang-tagih/' + bayar.id;
}

function closeEditModal() {
    document.getElementById('modalEditBayar').classList.add('hidden');
    document.getElementById('modalEditBayar').classList.remove('flex');
}

</script>

@endsection

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

    <!-- Header Atas -->
    <div class="bg-white rounded-xl shadow flex flex-col md:flex-row md:items-center justify-between px-6 py-4 mb-6 gap-3">
        <!-- Kiri -->
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 text-white text-sm font-medium px-5 py-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 shadow hover:from-blue-600 hover:to-purple-600 transition-all">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
            
            <!-- Info Judul -->
            <div class="flex flex-col">
                <div class="flex items-center gap-2 text-blue-600 font-semibold">
                    <i class="bi bi-box-seam text-blue-500"></i>
                    Manajemen Paket
                </div>
                <div class="flex items-center gap-1 text-gray-600 text-sm mt-1">
                    <i class="bi bi-wifi text-gray-500"></i> DHS
                </div>
            </div>
        </div>

        <!-- Kanan -->
        <div>
            <button type="button"
                class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-full shadow-md text-sm font-medium"
                data-bs-toggle="modal" data-bs-target="#tambahPaketModal">
                <i class="bi bi-plus-circle-fill"></i> Tambah Paket
            </button>
        </div>
    </div>

    <!-- Card Tabel Paket -->
    <div class="bg-white shadow rounded-2xl overflow-hidden border border-gray-100">
        <div class="flex items-center justify-between bg-white px-5 py-4 border-b border-gray-200">
            <div class="flex items-center gap-2 text-gray-800 font-semibold text-base">
                <i class="bi bi-list"></i>
                Daftar Paket Internet
                <span class="ml-2 bg-blue-500 text-white px-2 rounded-full text-xs font-semibold">
                    {{ $packages->count() }}
                </span>
            </div>
        </div>

        <table class="w-full table-auto text-sm text-gray-700">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Paket</th>
                    <th class="px-4 py-3 text-left font-semibold">Harga</th>
                    <th class="px-4 py-3 text-left font-semibold">Kecepatan</th>
                    <th class="px-4 py-3 text-left font-semibold">Deskripsi</th>
                    <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($packages as $index => $package)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $package->nama_paket }}</td>
                    <td class="px-4 py-3 text-green-600 font-semibold">
                        Rp {{ number_format($package->harga, 0, ',', '.') }} / bulan
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-cyan-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            {{ str_contains(strtolower($package->kecepatan), 'mbps') ? $package->kecepatan : $package->kecepatan . ' Mbps' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $package->deskripsi ?? '-' }}</td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="{{ route('packages.edit', $package) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow flex items-center gap-1 text-xs">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Hapus paket ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow flex items-center gap-1 text-xs">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if($packages->isEmpty())
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">
                        <i class="bi bi-box-seam text-3xl mb-2 block"></i>
                        Belum ada paket internet
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Paket -->
<div class="modal fade" id="tambahPaketModal" tabindex="-1" aria-labelledby="tambahPaketModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3 shadow-lg border-0">
      <div class="modal-header bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
        <h5 class="modal-title fw-bold" id="tambahPaketModalLabel">Tambah Paket Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('packages.store') }}" method="POST">
        @csrf
        <div class="modal-body px-4 py-3">
          <div class="mb-3">
            <label class="form-label fw-bold text-dark">Nama Paket *</label>
            <input type="text" name="nama_paket" class="form-control rounded-2 fw-semibold"
                   placeholder="Contoh: Paket Home 10MB" value="{{ old('nama_paket') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold text-dark">Harga Bulanan *</label>
            <div class="input-group">
              <span class="input-group-text bg-light fw-semibold">Rp</span>
              <input type="number" name="harga" class="form-control rounded-end fw-semibold"
                     placeholder="150000" value="{{ old('harga') }}" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold text-dark">Kecepatan (Mbps)</label>
            <input type="number" name="kecepatan" class="form-control rounded-2 fw-semibold"
                   placeholder="10" value="{{ old('kecepatan') }}">
            <small class="text-muted">Isi angka saja, sistem akan menambahkan "Mbps" otomatis</small>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold text-dark">Deskripsi</label>
            <textarea name="deskripsi" class="form-control rounded-2 fw-semibold"
                      placeholder="Deskripsi singkat..." rows="2">{{ old('deskripsi') }}</textarea>
          </div>
        </div>
        <div class="modal-footer border-0 px-4 pb-4">
          <button type="button" class="btn btn-light text-secondary px-3 rounded-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success px-3 rounded-3 text-white">
            <i class="bi bi-save"></i> Simpan Paket
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@if ($errors->any())
<script>
document.addEventListener("DOMContentLoaded", function() {
    var myModal = new bootstrap.Modal(document.getElementById('tambahPaketModal'));
    myModal.show();
});
</script>
@endif

@endsection

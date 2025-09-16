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
                    <i class="bi bi-geo-alt-fill text-blue-500"></i>
                    Manajemen Area
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
                    data-bs-toggle="modal" data-bs-target="#tambahAreaModal">
                <i class="bi bi-plus-circle-fill"></i> Tambah Area
            </button>
        </div>
    </div>

    <!-- Card Tabel Area -->
    <div class="bg-white shadow rounded-2xl overflow-hidden border border-gray-100">
        <!-- Header Card -->
        <div class="flex items-center justify-between bg-white px-5 py-4 border-b border-gray-200">
            <div class="flex items-center gap-2 text-gray-800 font-semibold text-base">
                <i class="bi bi-list"></i>
                Daftar Area
                <span class="ml-2 bg-blue-500 text-white px-2 rounded-full text-xs font-semibold">
                    {{ $areas->count() }}
                </span>
            </div>
        </div>

        @if (session('success'))
            <div class="mx-5 mt-4 mb-2 p-3 rounded bg-green-50 text-green-700 border border-green-200">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mx-5 mt-4 mb-2 p-3 rounded bg-red-50 text-red-700 border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table -->
        <table class="w-full table-auto text-sm text-gray-700">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Area</th>
                    <th class="px-4 py-3 text-left font-semibold">Deskripsi</th>
                    <th class="px-4 py-3 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($areas as $index => $area)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $area->nama_area }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $area->Deskripsi ?: '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('areas.edit', $area) }}"
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow flex items-center gap-1 text-xs">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('areas.destroy', $area) }}" method="POST"
                                      onsubmit="return confirm('Hapus area ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow flex items-center gap-1 text-xs">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">
                            <i class="bi bi-geo-alt text-3xl mb-2 block"></i>
                            Belum ada data area
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Area -->
<div class="modal fade" id="tambahAreaModal" tabindex="-1" aria-labelledby="tambahAreaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3 shadow-lg border-0">
      <div class="modal-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
        <h5 class="modal-title fw-bold" id="tambahAreaLabel">Tambah Area Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('areas.store') }}" method="POST">
        @csrf
        <div class="modal-body px-4 py-3">
          <div class="mb-3">
            <label class="form-label fw-bold text-dark">Nama Area *</label>
            <input type="text" name="nama_area" value="{{ old('nama_area') }}"
                   class="form-control rounded-2 fw-semibold" placeholder="Contoh: RW.03 atau Perum BCP" required>
            @error('nama_area')
              <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
          </div>

          <div class="mb-0">
            <label class="form-label fw-bold text-dark">Cakupan Area</label>
            <textarea name="cakupan_area" rows="2"
                      class="form-control rounded-2 fw-semibold"
                      placeholder="Contoh: RW.03, RW.04, Blok A–C">{{ old('cakupan_area') }}</textarea>
            @error('cakupan_area')
              <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
            <small class="text-muted d-block mt-1">Opsional</small>
          </div>
        </div>

        <div class="modal-footer border-0 px-4 pb-4">
          <button type="button" class="btn btn-light text-secondary px-3 rounded-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success px-3 rounded-3 text-white">
            <i class="bi bi-save"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modalEl = document.getElementById('tambahAreaModal');
  if (modalEl && typeof bootstrap !== 'undefined') {
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
  }
});
</script>
@endif
@endpush

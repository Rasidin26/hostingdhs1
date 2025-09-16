@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

    <!-- Header -->
    <div class="bg-white rounded-xl shadow flex flex-col md:flex-row md:items-center justify-between px-6 py-4 mb-6 gap-3">
        <div class="flex items-center gap-4">
            <a href="{{ route('packages.index') }}"
               class="flex items-center gap-2 text-white text-sm font-medium px-5 py-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 shadow hover:from-blue-600 hover:to-purple-600 transition-all">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Paket
            </a>
            <div class="flex flex-col">
                <div class="flex items-center gap-2 text-blue-600 font-semibold">
                    <i class="bi bi-pencil-square text-blue-500"></i>
                    Edit Paket Internet
                </div>
                <div class="flex items-center gap-1 text-gray-600 text-sm mt-1">
                    <i class="bi bi-wifi text-gray-500"></i> {{ $package->nama_paket }}
                </div>
            </div>
        </div>
    </div>

    <!-- Card Form Edit Paket -->
    <div class="bg-white shadow rounded-2xl p-6 border border-gray-100">
        <form action="{{ route('packages.update', $package) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama Paket --}}
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Nama Paket *</label>
                <input type="text" name="nama_paket"
                       value="{{ old('nama_paket', $package->nama_paket) }}"
                       class="form-control rounded-2 fw-semibold"
                       placeholder="Contoh: Paket Home 10MB" required>
                @error('nama_paket')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Harga Bulanan --}}
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Harga Bulanan *</label>
                <div class="input-group">
                    <span class="input-group-text bg-light fw-semibold">Rp</span>
                    <input type="text" name="harga"
                           value="{{ old('harga', $package->harga) }}"
                           class="form-control rounded-end fw-semibold"
                           placeholder="150000" inputmode="numeric" autocomplete="off" required>
                </div>
                <small class="text-muted">Boleh ketik 150000 atau 150.000 — sistem akan otomatis membersihkan.</small>
                @error('harga')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kecepatan --}}
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Kecepatan (Mbps)</label>
                <input type="text" name="kecepatan"
                       value="{{ old('kecepatan', $package->kecepatan ? $package->kecepatan . ' Mbps' : '') }}"
                       class="form-control rounded-2 fw-semibold"
                       placeholder="10 atau 10 Mbps">
                <small class="text-muted">Opsional — bisa isi angka saja atau angka + 'Mbps'</small>
                @error('kecepatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi"
                          class="form-control rounded-2 fw-semibold"
                          placeholder="Deskripsi singkat tentang paket ini..."
                          rows="2">{{ old('deskripsi', $package->deskripsi) }}</textarea>
                <small class="text-muted">Opsional</small>
                @error('deskripsi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-2">
                <a href="{{ route('packages.index') }}" 
                   class="btn btn-light text-secondary px-4 rounded-3">Batal</a>
                <button type="submit" class="btn btn-success px-4 rounded-3 text-white">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

    <!-- Header -->
    <div class="bg-white rounded-xl shadow flex flex-col md:flex-row md:items-center justify-between px-6 py-4 mb-6 gap-3">
        <div class="flex items-center gap-4">
            <a href="{{ route('areas.index') }}"
               class="flex items-center gap-2 text-white text-sm font-medium px-5 py-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 shadow hover:from-blue-600 hover:to-purple-600 transition-all">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Area
            </a>
            <div class="flex flex-col">
                <div class="flex items-center gap-2 text-blue-600 font-semibold">
                    <i class="bi bi-pencil-square text-blue-500"></i>
                    Edit Area Jangkauan
                </div>
                <div class="flex items-center gap-1 text-gray-600 text-sm mt-1">
                    <i class="bi bi-geo-alt text-gray-500"></i> {{ $area->nama_area }}
                </div>
            </div>
        </div>
    </div>

    <!-- Card Form Edit Area -->
    <div class="bg-white shadow rounded-2xl p-6 border border-gray-100">
        <form action="{{ route('areas.update', $area) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama Area --}}
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Nama Area *</label>
                <input type="text" name="nama_area"
                       value="{{ old('nama_area', $area->nama_area) }}"
                       class="form-control rounded-2 fw-semibold"
                       placeholder="Contoh: Area RW 05 atau Komplek ABC" required>
                @error('nama_area')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cakupan Area --}}
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Deskripsi</label>
                <input type="text" name="Deskripsi"
                       value="{{ old('Deskripsi', $area->Deskripsi) }}"
                       class="form-control rounded-2 fw-semibold"
                       placeholder="Contoh: RW.03, RW.04, Perum XYZ Blok A-B">
                <small class="text-muted">Opsional</small>
                @error('Deskripsi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-2 mt-4">
                <a href="{{ route('areas.index') }}" 
                   class="btn btn-light text-secondary px-4 rounded-3">Batal</a>
                <button type="submit" class="btn btn-success px-4 rounded-3 text-white">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8 px-4">
    <div class="max-w-5xl mx-auto">
        <!-- Judul Halaman -->
        <h1 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-2">Edit Profil Hotspot</h1>

        <!-- Panduan Format -->
        <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-800 p-4 rounded mb-6">
            <h2 class="font-semibold mb-1">Panduan Format:</h2>
            <ul class="list-disc list-inside text-sm leading-relaxed">
                <li><strong>Nama Profil:</strong> Contoh: <code>paket_silver</code></li>
                <li><strong>Batas Kecepatan:</strong> Format: <code>2M/2M</code> (Download/Upload)</li>
                <li><strong>Masa Berlaku:</strong> Format: <code>1d</code> (1 hari), <code>2h</code> (2 jam)</li>
                <li><strong>Harga:</strong> Angka tanpa titik/koma, contoh: <code>2000</code></li>
                <li><strong>Shared Users:</strong> Jumlah perangkat yang diizinkan (misal: <code>1</code>)</li>
            </ul>
        </div>

        <!-- Form Edit -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('hotspot.update', $profile->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Profil -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Profil</label>
                        <input type="text" name="nama_profil" placeholder="contoh: paket_silver"
                               value="{{ old('nama_profil', $profile->nama_profil) }}"
                               class="w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300 focus:outline-none p-2 text-sm" required>
                    </div>

                    <!-- Batas Kecepatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Batas Kecepatan</label>
                        <input type="text" name="batas_kecepatan" placeholder="contoh: 2M/2M"
                               value="{{ old('batas_kecepatan', $profile->batas_kecepatan) }}"
                               class="w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300 focus:outline-none p-2 text-sm">
                    </div>

                    <!-- Masa Berlaku -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Masa Berlaku</label>
                        <input type="text" name="masa_berlaku" placeholder="contoh: 1d"
                               value="{{ old('masa_berlaku', $profile->masa_berlaku) }}"
                               class="w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300 focus:outline-none p-2 text-sm">
                    </div>

                    <!-- Parent Queue -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parent Queue</label>
                        <select name="parent_queue"
                                class="w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300 focus:outline-none p-2 text-sm">
                            <option value="">-- Pilih Queue --</option>
                            @foreach ($queues as $queue)
                                <option value="{{ $queue->name }}" {{ $profile->parent_queue == $queue->name ? 'selected' : '' }}>
                                    {{ $queue->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Shared Users -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shared Users</label>
                        <input type="number" name="shared_users" placeholder="contoh: 1"
                               value="{{ old('shared_users', $profile->shared_users) }}"
                               class="w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300 focus:outline-none p-2 text-sm" required>
                    </div>

                    <!-- Harga Modal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Modal</label>
                        <input type="number" name="harga_modal" placeholder="contoh: 2000"
                               value="{{ old('harga_modal', $profile->harga_modal) }}"
                               class="w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300 focus:outline-none p-2 text-sm" required>
                    </div>

                    <!-- Harga Jual -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual</label>
                        <input type="number" name="harga_jual" placeholder="contoh: 5000"
                               value="{{ old('harga_jual', $profile->harga_jual) }}"
                               class="w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300 focus:outline-none p-2 text-sm" required>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                                class="w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300 focus:outline-none p-2 text-sm" required>
                            <option value="aktif" {{ $profile->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $profile->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-8 flex justify-between">
                    <a href="{{ route('hotspot.index') }}"
                       class="px-5 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition text-sm">Batal</a>
                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition text-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

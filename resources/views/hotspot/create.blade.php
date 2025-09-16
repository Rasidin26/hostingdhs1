@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 text-white">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-6">
            <a href="{{ route('hotspot.index') }}"
                class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold px-5 py-2.5 rounded shadow inline-flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali</span>
            </a>
            <h2 class="text-xl font-semibold">Tambah Profil Hotspot</h2>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form -->
        <form action="{{ route('hotspot.store') }}" method="POST"
            class="md:col-span-2 bg-gray-800 shadow-lg rounded-lg p-6 space-y-4 text-sm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Profil -->
                <div>
                    <label for="nama_profil" class="block font-semibold mb-1">Nama Profil</label>
                    <input type="text" name="nama_profil" id="nama_profil"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded px-3 py-2" required>
                </div>

                <!-- Session Timeout -->
                <div>
                    <label for="session_timeout" class="block font-semibold mb-1">Session Timeout</label>
                    <input type="text" name="session_timeout" id="session_timeout" placeholder="contoh: 1h"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded px-3 py-2">
                </div>

                <!-- Idle Timeout -->
                <div>
                    <label for="idle_timeout" class="block font-semibold mb-1">Idle Timeout</label>
             <input type="text" name="idle_timeout" id="idle_timeout" placeholder="contoh: 5m atau none">
<small class="text-gray-400">Gunakan <b>none</b> jika tidak ingin timeout saat idle.</small>

                </div>

                <!-- Rate Limit -->
                <div>
                    <label for="rate_limit" class="block font-semibold mb-1">Rate Limit</label>
                    <input type="text" name="rate_limit" id="rate_limit" placeholder="contoh: 2M/2M"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded px-3 py-2">
                </div>

                <!-- Shared Users -->
                <div>
                    <label for="shared_users" class="block font-semibold mb-1">Shared Users</label>
                    <input type="number" name="shared_users" id="shared_users" value="1"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded px-3 py-2">
                </div>

                <!-- Harga Modal -->
                <div>
                    <label for="harga_modal" class="block font-semibold mb-1">Harga Modal</label>
                    <div class="flex items-center">
                        <span class="px-2 bg-gray-600 text-white border border-r-0 border-gray-600 rounded-l">Rp</span>
                        <input type="number" name="harga_modal" id="harga_modal"
                            class="w-full bg-gray-700 border-gray-600 text-white rounded-r px-3 py-2">
                    </div>
                </div>

                <!-- Harga Jual -->
                <div>
                    <label for="harga_jual" class="block font-semibold mb-1">Harga Jual</label>
                    <div class="flex items-center">
                        <span class="px-2 bg-gray-600 text-white border border-r-0 border-gray-600 rounded-l">Rp</span>
                        <input type="number" name="harga_jual" id="harga_jual"
                            class="w-full bg-gray-700 border-gray-600 text-white rounded-r px-3 py-2">
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block font-semibold mb-1">Status</label>
                    <select name="status" id="status"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded px-3 py-2">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded shadow">
                    Simpan Profil
                </button>
            </div>
        </form>

        <!-- Format Guide -->
        <div class="bg-blue-800 bg-opacity-20 border-l-4 border-blue-400 p-4 rounded text-blue-200 text-sm h-fit">
            <p class="font-semibold mb-2">📘 Format Guide</p>
            <ul class="list-disc pl-5 space-y-1">
                <li><strong>Session Timeout:</strong> contoh: 1h (1 jam)</li>
                <li><strong>Idle Timeout:</strong> contoh: 5m (5 menit)</li>
                <li><strong>Rate Limit:</strong> contoh: 2M/2M (upload/download)</li>
                <li><strong>Shared Users:</strong> jumlah perangkat yang boleh login</li>
            </ul>
        </div>
    </div>
</div>
@endsection

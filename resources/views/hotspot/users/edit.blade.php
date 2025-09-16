@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-purple-500 to-indigo-500 px-6 py-4">
            <h2 class="text-white text-lg font-semibold flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536M9 11l-3 3a2 2 0 102.828 2.828l3-3m2-2l6-6m-6 6L9 11" />
                </svg>
                Edit User Hotspot
            </h2>
        </div>

        <form action="{{ route('hotspot.users.update', $user->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kolom Kiri --}}
                <div class="space-y-4">
                    {{-- Username --}}
                    <div>
                        <label class="block font-medium text-gray-900">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username"
                               value="{{ old('username', $user->code) }}"
                               class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900" required>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block font-medium text-gray-900">Password</label>
                        <input type="text" name="password"
                               value="{{ old('password', $user->code) }}"
                               class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900">
                        <small class="text-gray-500">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    {{-- Profile --}}
                    <div>
                        <label class="block font-medium text-gray-900">Profile <span class="text-red-500">*</span></label>
                        <select name="profile_id"
                                class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900" required>
                            <option value="">-- Pilih Profile --</option>
                            @foreach ($profiles as $profile)
                                <option value="{{ $profile->id }}" {{ $profile->id == $user->profile_id ? 'selected' : '' }}>
                                    {{ $profile->nama_profil }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center">
                        <input type="checkbox" name="status" value="disable"
                               class="mr-2"
                               {{ $user->status === 'disable' ? 'checked' : '' }}>
                        <label class="text-sm text-gray-800">Disable User</label>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-4">
                    {{-- Time Limit --}}
                    <div>
                        <label class="block font-medium text-gray-900">Time Limit</label>
                        <input type="text" name="limit_uptime"
                               value="{{ old('limit_uptime', $user->batas_waktu) }}"
                               class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900"
                               placeholder="e.g., 1d, 2h, 30m">
                        <small class="text-gray-500">Biarkan kosong untuk tanpa batas waktu</small>
                    </div>

                    {{-- Byte Limit --}}
                    <div>
                        <label class="block font-medium text-gray-900">Byte Limit</label>
                        <input type="text" name="limit_bytes_total"
                               value="{{ old('limit_bytes_total', $user->batas_kuota) }}"
                               class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900"
                               placeholder="e.g., 1G, 500M, 10G">
                        <small class="text-gray-500">Format: 500M, 1G, 10G</small>
                    </div>

                    {{-- Comment --}}
                    <div>
                        <label class="block font-medium text-gray-900">Comment</label>
                        <textarea name="comment"
                                  class="w-full border border-gray-300 rounded-md p-2 text-sm text-gray-900"
                                  rows="3"
                                  placeholder="Tambahkan catatan...">{{ old('comment', $user->comment) }}</textarea>
                        <small class="text-gray-500">Opsional: catatan tambahan</small>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('hotspot.users.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Cancel</a>
                <button type="submit"
                        class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-4 py-2 rounded-md hover:opacity-90">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

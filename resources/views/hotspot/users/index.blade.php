@extends('layouts.app')

@section('content')
<style>
    /* Sembunyikan teks bawaan Laravel di samping pagination */
    .hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > p {
        display: none !important;
    }
</style>

<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-6">
            <a href="{{ route('dashboard.index') }}"
               class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold px-5 py-2.5 rounded shadow inline-flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali ke Dashboard</span>
            </a>
            <div class="flex flex-col leading-tight">
                <span class="text-white text-base font-semibold text-left">Daftar User</span>
                <span class="bg-gray-200 text-gray-800 text-xs px-2 py-0.5 rounded-full font-medium w-fit mt-0.5">Device Management</span>
            </div>
        </div>

        <!-- Tombol Delete & Print -->
        <div class="flex space-x-2">
            <!-- Delete -->
            <form id="form-delete" method="POST" action="{{ route('hotspot.users.mass-destroy') }}" class="inline-block">
                @csrf
                @method('DELETE')
                <input type="hidden" name="ids" id="delete-ids">
                <button type="button" id="btn-delete"
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-sm flex items-center space-x-1 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-1 1v1H5a1 1 0 000 2h10a1 1 0 100-2h-3V3a1 1 0 00-1-1H9zm-3 7a1 1 0 011 1v6a1 1 0 11-2 0v-6a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0v-6a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 11-2 0v-6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Delete</span>
                </button>
            </form>

            <!-- Print -->
            <form id="form-print" method="POST" action="{{ route('hotspot.users.print') }}" target="_blank" class="inline-block">
                @csrf
                <input type="hidden" name="ids" id="selected-ids">
<button type="button" id="btn-print"
    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-sm flex items-center space-x-1 disabled:opacity-50 disabled:cursor-not-allowed"
    disabled>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
        <path d="M6 2a1 1 0 00-1 1v3h10V3a1 1 0 00-1-1H6z" />
        <path fill-rule="evenodd"
            d="M4 8a2 2 0 012-2h8a2 2 0 012 2v4H4V8zm2 5a1 1 0 00-1 1v2a1 1 0 001 1h8a1 1 0 001-1v-2a1 1 0 00-1-1H6z"
            clip-rule="evenodd" />
    </svg>
    <span>Print</span>
</button>
            </form>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <form method="GET">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" placeholder="username, password..." value="{{ request('search') }}"
                        class="w-full mt-1 px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:ring-blue-200 text-sm bg-white text-gray-800">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Profile</label>
                    <select name="profile" class="w-full mt-1 px-3 py-[9px] border rounded shadow-sm text-sm bg-white text-gray-800">
                        <option value="">All Profiles</option>
                        @foreach ($profiles as $profile)
                            <option value="{{ $profile->id }}" {{ request('profile') == $profile->id ? 'selected' : '' }}>
                                {{ $profile->nama_profil }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Comment</label>
                    <select name="comment" class="w-full mt-1 px-3 py-[9px] border rounded shadow-sm text-sm bg-white text-gray-800">
                        <option value="">All Comment</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Per Page</label>
                    <select name="per_page" class="w-full mt-1 px-3 py-[9px] border rounded shadow-sm text-sm bg-white text-gray-800">
                        <option value="0" {{ request('per_page') == '0' ? 'selected' : '' }}>0</option>
                        <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        <option value="200" {{ request('per_page') == '200' ? 'selected' : '' }}>200</option>
                        <option value="500" {{ request('per_page') == '500' ? 'selected' : '' }}>500</option>
                    </select>
                </div>
                <div class="flex flex-col justify-end space-y-2">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded shadow flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </button>
                    <a href="{{ route('hotspot.users.index') }}"
                       class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-medium px-3 py-1.5 rounded shadow text-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Info Pagination + Pagination Links -->
    <div class="flex items-center justify-between mb-2 text-sm text-indigo-200 font-medium">
        <span>
            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
        </span>
        <div>
{{ $users->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>

   <div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="table-auto w-full text-[13px] text-gray-800 whitespace-nowrap">
        <thead class="bg-gray-100 text-xs uppercase text-gray-700">
            <tr>
                <th class="px-2 py-2 text-center">
                    <input type="checkbox" id="select-all" class="h-4 w-4 text-blue-600 border-gray-300 rounded cursor-pointer">
                </th>
                <th class="px-2 py-2 text-left w-8">No</th>
                <th class="px-2 py-2 text-left w-32">Username</th>
                <th class="px-2 py-2 text-left w-32">Password</th>
                <th class="px-2 py-2 text-left w-28">Profile</th>
                <th class="px-2 py-2 text-left w-20">Upload</th>
                <th class="px-2 py-2 text-left w-20">Download</th>
                <th class="px-2 py-2 text-left w-20">Status</th>
                <th class="px-2 py-2 text-left w-28">Expires</th>
                <th class="px-2 py-2 text-left w-40 truncate">Comment</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-2 py-2 text-center">
                        <input type="checkbox" name="ids[]" value="{{ $user->id }}" class="row-checkbox form-checkbox cursor-pointer">
                    </td>
                    <td class="px-2 py-2">{{ $users->firstItem() + $index }}</td>
                    <td class="px-2 py-2">{{ $user->code }}</td>
                    <td class="px-2 py-2">{{ $user->code }}</td>
                    <td class="px-2 py-2">{{ $user->profile->nama_profil ?? '-' }}</td>
                    <td class="px-2 py-2">{{ $user->upload }}</td>
                    <td class="px-2 py-2">{{ $user->download }}</td>
            <td>
    <span class="badge bg-secondary">Offline</span>
</td>

                    <td class="px-2 py-2">{{ $user->expires }}</td>
                    <td class="px-2 py-2 truncate max-w-[160px]">{{ $user->comment }}</td>
                   
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="p-3 text-center text-gray-500">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const btnDelete = document.getElementById('btn-delete');
    const btnPrint = document.getElementById('btn-print');
    const deleteIdsInput = document.getElementById('delete-ids');
    const formDelete = document.getElementById('form-delete');

    function updateButtons() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        btnDelete.disabled = checked === 0;
        btnPrint.disabled = checked === 0;
    }

    selectAll.addEventListener('change', () => {
        rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
        updateButtons();
    });

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateButtons);
    });

    btnDelete.addEventListener('click', () => {
        const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
        if (ids.length === 0) return;
        if (confirm('Yakin ingin menghapus user yang dipilih?')) {
            deleteIdsInput.value = ids.join(',');
            formDelete.submit();
        }
    });

btnPrint.addEventListener('click', function () {
    const ids = Array
        .from(document.querySelectorAll('.row-checkbox:checked'))
        .map(cb => cb.value);

    if (ids.length === 0) {
        alert('Pilih minimal 1 voucher untuk dicetak!');
        return;
    }
    
fetch('/hotspot/users/print-selected', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ ids })
})
.then(res => res.text())
.then(html => {
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);
    iframe.contentDocument.write(html);
iframe.contentDocument.close();

const images = iframe.contentDocument.images;
let loaded = 0;
if(images.length === 0) {
    iframe.contentWindow.print();
} else {
    for(let img of images) {
        img.onload = img.onerror = () => {
            loaded++;
            if(loaded === images.length) {
                iframe.contentWindow.print();
            }
        };
    }
}

});

});


});
</script>
@endsection

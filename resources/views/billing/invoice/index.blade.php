@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

    <!-- Header -->
    <div class="bg-white rounded-xl shadow flex flex-col md:flex-row md:items-center justify-between px-6 py-4 mb-6 gap-3">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 text-white text-sm font-medium px-5 py-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 shadow hover:from-blue-600 hover:to-purple-600 transition-all">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>

            <div class="flex flex-col">
                <div class="flex items-center gap-2 text-blue-600 font-semibold">
                    <i class="bi bi-receipt-cutoff text-blue-500"></i>
                    Faktur Pengguna
                </div>
                <div class="flex items-center gap-1 text-gray-600 text-sm mt-1">
                    <i class="bi bi-wifi text-gray-500"></i> DHS
                </div>
            </div>
        </div>
    </div>

    <!-- Header + Tombol Export -->
<div class="bg-white rounded-xl shadow flex flex-col md:flex-row md:items-center justify-between px-6 py-4 mb-6">
    <!-- Subjudul -->
    <div class="flex items-center gap-2 text-blue-600 font-semibold text-lg">
        <i class="bi bi-list"></i>
        Manajemen Faktur Voucher
    </div>

<!-- Tombol Export -->
<!-- <div class="flex gap-3 mt-3 md:mt-0">
    <a href="{{ route('invoice.export', 'excel') }}" 
       class="bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 text-sm flex items-center gap-1">
        <i class="bi bi-file-earmark-excel"></i> Excel
    </a>
    <a href="{{ route('invoice.export', 'pdf') }}" 
       class="bg-purple-500 text-white px-4 py-2 rounded-lg shadow hover:bg-purple-600 text-sm flex items-center gap-1">
        <i class="bi bi-file-earmark-pdf"></i> PDF
    </a>
</div> -->


<!-- Notifikasi -->
<div id="notif" class="hidden fixed top-5 right-5 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50"></div>

</div>


    <!-- Filter -->
<div class="bg-white rounded-xl shadow px-6 py-4 mb-6 flex flex-wrap items-center gap-3">
    <span class="text-gray-700 font-medium">Filter berdasarkan Status:</span>

    <a href="{{ route('invoice.index', ['status' => 'all']) }}"
       class="px-4 py-2 rounded-lg shadow text-sm font-medium 
       {{ $status === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
        Semua
    </a>

    <a href="{{ route('invoice.index', ['status' => 'online']) }}"
       class="px-4 py-2 rounded-lg shadow text-sm font-medium 
       {{ $status === 'online' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
        Online
    </a>

    <a href="{{ route('invoice.index', ['status' => 'offline']) }}"
       class="px-4 py-2 rounded-lg shadow text-sm font-medium 
       {{ $status === 'offline' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
        Offline
    </a>
</div>



    <!-- Data Faktur -->
     <!-- Tabel Voucher -->
    <div class="bg-white shadow rounded-2xl overflow-hidden border border-gray-100">
        <div class="flex items-center justify-between bg-white px-5 py-4 border-b border-gray-200">
            <div class="flex items-center gap-2 text-gray-800 font-semibold text-base">
                <i class="bi bi-ticket-perforated"></i>
                Daftar Voucher
                <span class="ml-2 bg-blue-500 text-white px-2 rounded-full text-xs font-semibold">
                    {{ $vouchers->count() }}
                </span>
            </div>
        </div>

        @if($vouchers->isEmpty())
            <div class="text-center py-10 text-gray-500">
                <i class="bi bi-ticket text-4xl mb-3 block"></i>
                Tidak ada data voucher ditemukan
            </div>
        @else
            <div class="overflow-x-auto">
            <table class="w-full table-auto text-sm text-gray-700">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Kode</th>
                        <th class="px-4 py-2">Harga</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Expired</th>
                        <th class="px-4 py-2">User</th>
                        <th class="px-4 py-2">Sales</th>
                        <th class="px-4 py-2">Profile</th>
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Tipe Pengguna</th>
                        <th class="px-4 py-2">Awalan Username</th>
                        <th class="px-4 py-2">Tipe Karakter</th>
                        <th class="px-4 py-2">Batas Waktu</th>
                        <th class="px-4 py-2">Batas Kuota</th>
                        <th class="px-4 py-2">Dibuat</th>
                        <th class="px-4 py-2">Diupdate</th>
                        <th class="px-4 py-2">Comment</th>
                        <th class="px-4 py-2">Tipe</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($vouchers as $voucher)
                    <tr class="hover:bg-gray-50">
<td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $voucher->code }}</td>
                        <td class="px-4 py-2">Rp {{ number_format($voucher->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">{{ $voucher->status }}</td>
                        <td class="px-4 py-2">{{ $voucher->expired_at }}</td>
                        <td class="px-4 py-2">{{ $voucher->user_id ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $voucher->sales_id }}</td>
                        <td class="px-4 py-2">{{ $voucher->profile_id }}</td>
                        <td class="px-4 py-2">{{ $voucher->jumlah }}</td>
                        <td class="px-4 py-2">{{ $voucher->tipe_pengguna }}</td>
                        <td class="px-4 py-2">{{ $voucher->awalan_username }}</td>
                        <td class="px-4 py-2">{{ $voucher->tipe_karakter }}</td>
                        <td class="px-4 py-2">{{ $voucher->batas_waktu }}</td>
                        <td class="px-4 py-2">{{ $voucher->batas_kuota ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $voucher->created_at }}</td>
                        <td class="px-4 py-2">{{ $voucher->updated_at }}</td>
                        <td class="px-4 py-2">{{ $voucher->comment ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $voucher->tipe }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endif
    </div>

</div>

<script>
function exportInvoice(type) {
    fetch(`/invoices/export/${type}`)
        .then(res => res.json())
        .then(data => {
            showNotif(data.message, data.status);
        })
        .catch(err => {
            showNotif('Terjadi kesalahan!', 'error');
        });
}

function showNotif(message, status) {
    const notif = document.getElementById('notif');
    notif.textContent = message;
    notif.className = `fixed top-5 right-5 px-4 py-3 rounded-lg shadow-lg z-50 ${
        status === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;

    notif.classList.remove('hidden');

    setTimeout(() => {
        notif.classList.add('hidden');
    }, 3000);
}
</script>

@endsection

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

    <!-- Header -->
    <div class="bg-white rounded-xl shadow flex flex-col md:flex-row md:items-center justify-between px-6 py-4 mb-6 gap-3">
        <div class="flex items-center gap-4">
            <!-- Tombol Back -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 text-white text-sm font-medium px-5 py-2 rounded-full 
                      bg-gradient-to-r from-blue-500 to-purple-500 shadow hover:from-blue-600 hover:to-purple-600 transition-all">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>

            <!-- Info Judul -->
            <div>
                <div class="flex items-center gap-2 text-blue-600 font-semibold">
                    <i class="bi bi-credit-card-2-front text-blue-500"></i>
                    Pembayaran Pelanggan
                </div>
                <div class="flex items-center gap-1 text-gray-600 text-sm mt-1">
                    <i class="bi bi-cash-coin text-gray-500"></i> DHS
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
   <div class="bg-white rounded-xl shadow px-6 py-4 mb-6 flex flex-wrap items-center gap-3">
    <span class="text-gray-700 font-medium">Filter berdasarkan Status:</span>

    <!-- Semua -->
    <a href="{{ route('billing.pembayaran', ['status' => 'all']) }}"
       class="px-4 py-2 rounded-lg shadow text-sm font-medium 
              {{ $status == 'all' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
        <i class="bi bi-list"></i> Semua
    </a>

    <!-- Belum Lunas = Tertunda -->
    <a href="{{ route('billing.pembayaran', ['status' => 'Belum Lunas']) }}"
       class="px-4 py-2 rounded-lg shadow text-sm font-medium 
              {{ $status == 'Belum Lunas' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
        <i class="bi bi-clock-history"></i> Tertunda
    </a>

    <!-- Lunas = Berhasil -->
    <a href="{{ route('billing.pembayaran', ['status' => 'Lunas']) }}"
       class="px-4 py-2 rounded-lg shadow text-sm font-medium 
              {{ $status == 'Lunas' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
        <i class="bi bi-check-circle"></i> Berhasil
    </a>
</div>


    <!-- Data Pembayaran -->
    <div class="bg-white shadow rounded-2xl overflow-hidden border border-gray-100">
        <!-- Header Tabel -->
        <div class="flex items-center justify-between bg-white px-5 py-4 border-b border-gray-200">
            <div class="flex items-center gap-2 text-gray-800 font-semibold text-base">
                <i class="bi bi-wallet2"></i>
                Data Pembayaran Pelanggan
                <span class="ml-2 bg-blue-500 text-white px-2 rounded-full text-xs font-semibold">
                    {{ $customers->count() }}
                </span>
            </div>
        </div>

        <!-- Isi -->
        @if($customers->isEmpty())
            <div class="text-center py-10 text-gray-500">
                <i class="bi bi-receipt text-4xl mb-3 block"></i>
                Tidak ada pelanggan ditemukan <br>
                <span class="text-sm">Belum ada pembayaran yang tercatat</span>
            </div>
        @else
            <table class="w-full table-auto text-sm text-gray-700">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Pelanggan</th>
                        <th class="px-4 py-3 text-left font-semibold">Jumlah</th>
                        <th class="px-4 py-3 text-left font-semibold">Paket</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($customers as $index => $customer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ $customer->nama }}</td>
                            <td class="px-4 py-3 text-green-600 font-semibold">
                                Rp {{ number_format($customer->biaya ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">{{ ucfirst($customer->payment_method ?? 'Tidak Ada') }}</td>
<<!-- Kolom Status -->
<td class="px-4 py-3">
    @if($customer->status_pembayaran == 'Lunas')
        <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">Berhasil</span>
    @elseif($customer->status_pembayaran == 'Belum Lunas')
        <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs">Tertunda</span>
    @endif
</td>

<!-- Kolom Tanggal -->
<td class="px-4 py-3">
    @if($customer->status_pembayaran == 'Lunas')
        {{ $customer->updated_at->format('d M Y') }}
    @else
        -
    @endif
</td>

                            <td class="px-4 py-3 flex gap-2">
                                <a href="#"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded shadow flex items-center gap-1 text-xs">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

    <!-- Header -->
    <div class="bg-white rounded-xl shadow flex flex-col md:flex-row md:items-center justify-between px-6 py-4 mb-6 gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.index') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 text-white text-sm font-medium shadow">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
            <div>
                <h1 class="text-xl font-bold text-blue-600">Pembukuan Keuangan</h1>
                <p class="text-gray-500 text-sm">
                    Laporan Lengkap - {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex items-center bg-cyan-100 text-cyan-600 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-clock mr-2"></i> 
                <span id="realTimeClock">{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</span>
            </div>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="mb-6">
        <div class="bg-blue-50 rounded-xl shadow px-6 py-4 w-full flex items-center justify-between">

            <!-- Bagian Kiri: Filter Periode -->
            <div class="flex flex-col gap-2">
                <span class="text-xs font-semibold text-blue-600 flex items-center gap-1">
                    <i class="fas fa-filter"></i> Filter Periode
                </span>

                <div class="flex items-center gap-3">
                    <!-- Dropdown Bulan -->
                    <form method="GET" action="{{ route('billing.pembukuan') }}">
                        <select name="periode" onchange="this.form.submit()" 
                            class="w-40 px-2 py-1 rounded-lg bg-white border border-gray-300 shadow-sm text-xs text-gray-700 focus:ring-2 focus:ring-purple-500">
                            @for ($i = 0; $i < 12; $i++)
                                @php
                                    $date = \Carbon\Carbon::now()->subMonths($i);
                                    $value = $date->format('Y-m');
                                    $label = $date->translatedFormat('F Y');
                                @endphp
                                <option value="{{ $value }}" 
                                    {{ request('periode', \Carbon\Carbon::now()->format('Y-m')) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endfor
                        </select>
                    </form>

                    <!-- Label periode aktif -->
                    @php
                        $periodeAktif = request('periode') 
                            ? \Carbon\Carbon::parse(request('periode').'-01')->translatedFormat('F Y') 
                            : \Carbon\Carbon::now()->translatedFormat('F Y');
                    @endphp
                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-gradient-to-r from-purple-500 to-purple-600 text-white text-[10px] font-medium shadow">
                        <i class="fas fa-calendar-alt mr-1 text-[10px]"></i> {{ $periodeAktif }}
                    </span>
                </div>
            </div>

            <!-- Bagian Kanan: Tombol -->
            <div class="flex items-center gap-3">
                <!-- Tombol Bulan Ini -->
                <!-- <form method="GET" action="{{ route('billing.pembukuan') }}">
                    <input type="hidden" name="periode" value="{{ \Carbon\Carbon::now()->format('Y-m') }}">
                    <button type="submit"
                        class="px-3 py-1.5 rounded-lg border border-blue-500 text-blue-600 hover:bg-blue-100 text-sm font-medium flex items-center gap-2">
                        <i class="fas fa-home"></i> Bulan Ini
                    </button>
                </form> -->

                <!-- Tombol Export -->
   <form action="{{ route('pembukuan.export') }}" method="GET">
<input type="hidden" name="periode" value="{{ \Carbon\Carbon::parse($periode)->format('Y-m') }}">
    <button type="submit" 
        class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium flex items-center gap-2">
        <i class="fas fa-download"></i> Export
    </button>
</form>

        </div>
    </div>

    <!-- ==================== MENU AKSI CEPAT ==================== -->
<div class="bg-white rounded-2xl shadow-lg px-8 py-6 mt-10">
    <h2 class="text-blue-600 font-bold text-xl flex items-center gap-2 mb-2">
        <i class="fas fa-clock"></i> Menu Aksi Cepat
    </h2>
    <p class="text-gray-500 text-sm mb-6">Kelola pemasukkan dan pengeluaran untuk August 2025</p>

    <div class="flex flex-wrap gap-4">
       <button onclick="openModal('modalPemasukkan')" 
    class="px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow flex items-center gap-2">
    <i class="fas fa-plus"></i> Tambah Pemasukkan
</button>
<!-- ==================== MODAL TAMBAH PEMASUKKAN ==================== -->
<div id="modalPemasukkan" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative">
    
    <!-- Tombol Close -->
    <button onclick="closeModal('modalPemasukkan')" 
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
        <i class="fas fa-times text-lg"></i>
    </button>

    <!-- Judul -->
    <h2 class="text-lg font-bold text-green-600 mb-4 flex items-center gap-2">
      <i class="fas fa-plus-circle"></i> Tambah Pemasukkan
    </h2>

    <!-- Form -->
    <form id="formPemasukkan">
      <!-- Jenis Pemasukkan -->
      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pemasukkan</label>
        <select name="jenis" required
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
          <option value="">Pilih Jenis Pemasukkan</option>
          <option value="voucher">Voucher</option>
          <option value="billing">Billing</option>
          <option value="lain">Lain-lain</option>
        </select>
      </div>

      <!-- Jumlah -->
      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
        <input type="number" name="jumlah" placeholder="Masukkan jumlah"
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500" required>
      </div>

      <!-- Tanggal -->
      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
        <input type="date" name="tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500" required>
      </div>

      <!-- Sumber -->
      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Sumber</label>
        <select name="sumber"
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
          <option value="manual">Manual Entry</option>
          <option value="online">System Sync</option>
          <option value="online">Admin Correction</option>
          <option value="online">Data Migration</option>

        </select>
      </div>

      <!-- Deskripsi -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Keterangan</label>
        <textarea name="deskripsi" rows="2"
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
          placeholder="Tambahkan keterangan pemasukkan (opsional)"></textarea>
      </div>

      <!-- Tombol Aksi -->
      <div class="flex justify-end gap-3">
        <button type="button" onclick="closeModal('modalPemasukkan')"
          class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
          Batal
        </button>
        <button type="submit"
          class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
          Tambah Pemasukkan
        </button>
      </div>
    </form>
  </div>
</div>


        <button onclick="openModal('pengeluaranModal')" 
            class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow flex items-center gap-2">
            <i class="fas fa-minus"></i> Tambah Pengeluaran
        </button>

        <!-- ==================== MODAL TAMBAH PENGELUARAN ==================== -->
<div id="pengeluaranModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 relative">
    
    <!-- Tombol Close -->
    <button onclick="closeModal('pengeluaranModal')" 
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
        <i class="fas fa-times text-lg"></i>
    </button>

    <!-- Judul -->
    <h2 class="text-lg font-bold text-red-600 mb-4 flex items-center gap-2">
      <i class="fas fa-minus-circle"></i> Tambah Pengeluaran
    </h2>

    <!-- Form -->
    <form id="formPengeluaran" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      
      <!-- Kategori Pengeluaran -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Pengeluaran</label>
        <select name="kategori" required
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500">
          <option value="">Pilih Kategori</option>
          <option value="gaji">Gaji Karyawan</option>
          <option value="operasional">Operasional</option>
          <option value="peralatan">Peralatan</option>
          <option value="lain">Lain-lain</option>
        </select>
      </div>

      <!-- Jumlah -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
        <input type="number" name="jumlah" placeholder="Masukkan jumlah"
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500" required>
      </div>

      <!-- Dibayar Kepada -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Dibayar Kepada</label>
        <input type="text" name="dibayar" placeholder="Nama penerima/vendor"
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500" required>
      </div>

      <!-- Tanggal Pengeluaran -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengeluaran</label>
        <input type="date" name="tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500" required>
      </div>

      <!-- Deskripsi / Keterangan -->
      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi / Keterangan</label>
        <textarea name="deskripsi" rows="3"
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500"
          placeholder="Tambahkan keterangan pengeluaran (opsional)"></textarea>
        <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
      </div>

      <!-- Tombol Aksi -->
      <div class="md:col-span-2 flex justify-end gap-3 mt-2">
        <button type="button" onclick="closeModal('pengeluaranModal')"
          class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
          Batal
        </button>
        <button type="submit"
          class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
          Tambah Pengeluaran
        </button>
      </div>
    </form>
  </div>
</div>

        <button onclick="openModal('offlineModal')" 
            class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg shadow flex items-center gap-2">
            <i class="fas fa-edit"></i> Update Offline
        </button>
    </div>
    <!-- ==================== MODAL UPDATE OFFLINE ==================== -->
<div id="offlineModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 relative">
    
    <!-- Tombol Close -->
    <button onclick="closeModal('offlineModal')" 
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
        <i class="fas fa-times text-lg"></i>
    </button>

    <!-- Judul -->
    <h2 class="text-lg font-bold text-cyan-600 mb-4 flex items-center gap-2">
      <i class="fas fa-edit"></i> Update Data Offline
    </h2>

    <!-- Form -->
    <form id="formOffline" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      
      <!-- Bulan -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
        <input type="month" name="bulan" value="{{ \Carbon\Carbon::now()->format('Y-m') }}"
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500" required>
      </div>

      <!-- Jumlah -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
        <input type="number" name="jumlah" placeholder="Masukkan jumlah"
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500" required>
      </div>

      <!-- Keterangan -->
      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
        <textarea name="keterangan" rows="3"
          class="w-full bg-white text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500"
          placeholder="Keterangan update data offline"></textarea>
      </div>

      <!-- Info Warning -->
      <div class="md:col-span-2 bg-orange-50 border border-orange-200 text-orange-700 text-sm rounded-lg p-3 flex items-start gap-2">
        <i class="fas fa-exclamation-triangle mt-1"></i>
        <p>
          Fitur ini untuk update atau koreksi data penjualan offline yang sudah ada. 
          Jika data untuk bulan tersebut sudah ada, akan diupdate. Jika belum ada, akan ditambahkan baru.
        </p>
      </div>

      <!-- Tombol Aksi -->
      <div class="md:col-span-2 flex justify-end gap-3 mt-2">
        <button type="button" onclick="closeModal('offlineModal')"
          class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
          Batal
        </button>
        <button type="submit"
          class="px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600">
          Update Data
        </button>
      </div>
    </form>
  </div>
</div>

    
</div>


<!-- ==================== CARD UTAMA ==================== -->
<div class="bg-white rounded-2xl shadow-lg px-8 py-6 mt-10">
    <h2 class="text-blue-600 font-bold text-xl flex items-center gap-2 mb-6">
        <i class="fas fa-th-large"></i> Menu Pembukuan Lengkap
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Uang di Admin -->
        <a href="{{ url('/admin/finance') }}" class="menu-card border-t-4 border-yellow-400">
            <div class="text-yellow-500 text-3xl mb-2">
                <i class="fas fa-coins"></i>
            </div>
            <h3 class="font-bold text-blue-600 text-sm">Uang Di Admin</h3>
            <p class="text-gray-500 text-xs mt-1">Lihat sisa uang di setiap admin/perangkat.</p>
        </a>

        <!-- Semua Pembukuan -->
        <a href="{{ url('/admin/finance/semua') }}" class="menu-card border-t-4 border-blue-500">
            <div class="text-blue-500 text-3xl mb-2">
                <i class="fas fa-list-alt"></i>
            </div>
            <h3 class="font-bold text-blue-600 text-sm">Semua Pembukuan</h3>
            <p class="text-gray-500 text-xs mt-1">Lihat riwayat lengkap semua transaksi keuangan.</p>
        </a>

        <!-- Pembayaran Manual -->
        <a href="{{ url('/admin/finance/pembayaran') }}" class="menu-card border-t-4 border-green-500">
            <div class="text-green-500 text-3xl mb-2">
                <i class="fas fa-user-tie"></i>
            </div>
            <h3 class="font-bold text-blue-600 text-sm">Pembayaran Manual</h3>
            <p class="text-gray-500 text-xs mt-1">Kelola pembayaran manual yang dilakukan oleh admin.</p>
        </a>

        <!-- Rangkuman Keuangan -->
        <a href="{{ url('/admin/finance/summary') }}" class="menu-card border-t-4 border-cyan-500">
            <div class="text-cyan-500 text-3xl mb-2">
                <i class="fas fa-chart-pie"></i>
            </div>
            <h3 class="font-bold text-blue-600 text-sm">Rangkuman Keuangan</h3>
            <p class="text-gray-500 text-xs mt-1">Summary lengkap dan analisis laporan keuangan.</p>
        </a>
    </div>
</div>

<!-- 🔹 Style -->
<style>
    .menu-card {
        @apply bg-white rounded-xl shadow transition p-6 flex flex-col items-center text-center cursor-pointer;
    }
    .menu-card:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    }
    .menu-card:active {
        transform: scale(0.95);
    }
</style>


<!-- ==================== BAGIAN PENGELUARAN ==================== -->
<div class="bg-white rounded-2xl shadow-lg p-6 mt-10">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-blue-600">
            ↓ Pengeluaran - {{ \Carbon\Carbon::parse($periode)->translatedFormat('F Y') }}
        </h2>
        <span class="bg-red-500 text-white px-4 py-2 rounded-full font-bold">
            Total: Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}

        </span>
    </div>


    <!-- 🔹 Instruksi -->
    <p class="text-gray-500 text-sm mb-4">
        Klik salah satu kategori di bawah untuk melihat detail pengeluaran.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <!-- Gaji Karyawan -->
        <a href="/gaji" 
           class="pengeluaran-item border-t-4 border-yellow-500 text-yellow-500 hover:bg-yellow-50"
           data-color="yellow">
            <i class="fas fa-users text-3xl mb-2"></i>
            <span class="flex items-center gap-1">
                Gaji Karyawan <i class="fas fa-chevron-right text-sm"></i>
            </span>
            <p class="text-xs text-gray-400 mt-1">Lihat daftar gaji bulan ini</p>
        </a>

        <!-- Bayar Kang Tagih -->
        <a href="{{ route('bayar-kang-tagih.index') }}" 
           class="pengeluaran-item border-t-4 border-green-500 text-green-500 hover:bg-green-50"
           data-color="green">
            <i class="fas fa-motorcycle text-3xl mb-2"></i>
            <span class="flex items-center gap-1">
                Bayar Kang Tagih <i class="fas fa-chevron-right text-sm"></i>
            </span>
            <p class="text-xs text-gray-400 mt-1">Detail pembayaran kurir/tagihan</p>
        </a>

        <!-- Listrik/PDAM/Pulsa -->
        <a href="{{ route('listrik-pdam-pulsa.index') }}" 
           class="pengeluaran-item border-t-4 border-blue-500 text-blue-500 hover:bg-blue-50"
           data-color="blue">
            <i class="fas fa-bolt text-3xl mb-2"></i>
            <span class="flex items-center gap-1">
                Listrik/PDAM/Pulsa <i class="fas fa-chevron-right text-sm"></i>
            </span>
            <p class="text-xs text-gray-400 mt-1">Tagihan listrik, PDAM, & pulsa</p>
        </a>

        <!-- Pasang Baru -->
        <a href="{{ route('pasangbaru.index') }}" 
           class="pengeluaran-item border-t-4 border-purple-500 text-purple-500 hover:bg-purple-50"
           data-color="purple">
            <i class="fas fa-user-plus text-3xl mb-2"></i>
            <span class="flex items-center gap-1">
                Pasang Baru <i class="fas fa-chevron-right text-sm"></i>
            </span>
            <p class="text-xs text-gray-400 mt-1">Biaya pemasangan pelanggan baru</p>
        </a>

        <!-- Perbaikan Alat -->
        <a href="{{ route('perbaikanalat.index') }}" 
           class="pengeluaran-item border-t-4 border-orange-500 text-orange-500 hover:bg-orange-50"
           data-color="orange">
            <i class="fas fa-wrench text-3xl mb-2"></i>
            <span class="flex items-center gap-1">
                Perbaikan Alat <i class="fas fa-chevron-right text-sm"></i>
            </span>
            <p class="text-xs text-gray-400 mt-1">Catatan servis & perbaikan alat</p>
        </a>    
    </div>
</div>

<!-- 🔹 Style -->
<style>
    .pengeluaran-item {
        @apply flex flex-col items-center justify-center bg-white shadow rounded-xl p-6 font-semibold transition transform cursor-pointer;
    }
    .pengeluaran-item:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    }
    .pengeluaran-item:active {
        transform: scale(0.95);
    }
</style>
</div>

<!-- ==================== BAGIAN PEMASUKKAN DETAIL ==================== -->
<div class="bg-white rounded-2xl shadow-lg p-6 mt-10 space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b pb-4 gap-3">
        <h2 class="text-xl font-bold text-gray-800">
            Pemasukkan Detail - {{ \Carbon\Carbon::parse($periode)->translatedFormat('F Y') }}
        </h2>
        <div class="flex flex-col sm:flex-row gap-2">
            <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-lg text-sm font-medium">
                Bulan Ini: Rp {{ number_format($totalPendapatanMonth, 0, ',', '.') }}

            </span>
           
        </div>
    </div>

    <!-- Grid 2 Kolom -->
    <div class="grid md:grid-cols-2 gap-6">

        <!-- Pendapatan Voucher -->
        <div class="bg-gray-50 rounded-xl p-6 shadow-sm space-y-4">
            <h3 class="text-lg font-semibold text-green-700">Pendapatan Voucher</h3>
            
            <div class="space-y-1 text-gray-700">
                <p>Voucher Online (Unused Akumulasi): 
                    <span class="font-medium">Rp {{ number_format($totalVoucherOnline, 0, ',', '.') }}</span>
                </p>
                <p>Voucher Offline (Unused Akumulasi): 
                    <span class="font-medium">Rp {{ number_format($totalVoucherOffline, 0, ',', '.') }}</span>
                </p>
            </div>

            <div class="mt-4 bg-green-100 border border-green-300 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-green-700 mb-1">
                    Pendapatan Voucher {{ \Carbon\Carbon::parse($periode)->translatedFormat('F Y') }} (Unused)
                </h4>
                <p class="text-lg font-bold text-gray-800 mb-1">Rp {{ number_format($voucherMonth, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-600">
                    Online: Rp {{ number_format($voucherOnlineMonth, 0, ',', '.') }} | 
                    Offline: Rp {{ number_format($voucherOfflineMonth, 0, ',', '.') }}
                </p>
            </div>
        </div>

<!-- Pembayaran Tagihan -->
<div class="bg-gray-50 rounded-xl p-6 shadow-sm space-y-4">
    <h3 class="text-lg font-semibold text-blue-700">Pembayaran Tagihan</h3>

    <!-- Akumulasi -->
    <div class="space-y-1 text-gray-700">
        <p>Total Billing Lunas (Akumulasi): 
            <span class="font-medium text-green-700">
                Rp {{ number_format($totalBillingLunasAll, 0, ',', '.') }}
            </span>
        </p>
        <p>Total Billing Belum Lunas (Akumulasi): 
            <span class="font-medium text-red-600">
                Rp {{ number_format($totalBillingBelumAll, 0, ',', '.') }}
            </span>
        </p>
    </div>

    <!-- Detail per Bulan -->
    <div class="mt-4 bg-blue-100 border border-blue-300 rounded-lg p-4">
        <h4 class="text-sm font-semibold text-blue-700 mb-1">
            Pembayaran Tagihan {{ \Carbon\Carbon::parse($periode)->translatedFormat('F Y') }}
        </h4>
        <!-- Total Keseluruhan -->
        <p class="text-lg font-bold text-gray-800 mb-1">
            Rp {{ number_format($totalBillingAll, 0, ',', '.') }}
        </p>
        <!-- Breakdown -->
        <p class="text-xs text-gray-600">
            Lunas: Rp {{ number_format($totalBillingLunasAll, 0, ',', '.') }} | 
            Belum Lunas: Rp {{ number_format($billingMonthBelum, 0, ',', '.') }}
        </p>
    </div>
</div>


    </div>
</div>


<!-- Aktivitas -->
<div class="bg-gray-50 rounded-xl p-6 shadow-sm mt-8">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">
        Aktivitas Pemasukkan {{ \Carbon\Carbon::parse($periode)->translatedFormat('F Y') }}
    </h3>

    <div class="flex items-center justify-between border rounded-lg p-4 
                {{ $perbandingan >= 0 ? 'bg-green-100 border-green-300' : 'bg-red-100 border-red-300' }}">
        
        <!-- Info Bulan Sebelumnya -->
        <div class="space-y-1">
            <p class="text-sm text-gray-600">Perbandingan dengan Bulan Sebelumnya</p>
            <p class="text-sm text-gray-500">
                Bulan sebelumnya: Rp {{ number_format($totalPrev, 0, ',', '.') }}
            </p>
        </div>

        <!-- Persentase dan Selisih -->
        <div class="text-right space-y-1">
            <p class="text-2xl font-bold {{ $perbandingan >= 0 ? 'text-green-700' : 'text-red-700' }}">
                {{ $perbandingan }}%
            </p>
            <p class="text-sm text-gray-600">
                Rp {{ number_format($totalPendapatanMonth - $totalPrev, 0, ',', '.') }}
            </p>
        </div>
    </div>
</div>



<div class="bg-white rounded-xl shadow-md p-6 mt-6">
    <!-- Header -->
    <h2 class="text-lg font-bold flex items-center gap-2 mb-6 text-gray-700">
        <i class="fas fa-chart-line text-blue-500"></i>
        Ringkasan Keuangan Lengkap - {{ $periode }}
    </h2>

    <!-- Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        
        <!-- Voucher Bulan Ini -->
        <div class="bg-white border rounded-lg shadow p-4 text-center">
            <p class="text-green-700 font-semibold flex items-center justify-center gap-2 uppercase">
                <i class="fas fa-ticket-alt"></i> Voucher Bulan Ini
            </p>
            <p class="text-2xl font-bold mt-2 text-gray-800">
                Rp {{ number_format($voucherMonth,0,',','.') }}
            </p>
            <p class="text-xs text-gray-600 mt-1">Online: Rp {{ number_format($voucherOnlineMonth,0,',','.') }}</p>
            <p class="text-xs text-gray-600">Offline: Rp {{ number_format($voucherOfflineMonth,0,',','.') }}</p>
        </div>

        <!-- Billing Bulan Ini -->
        <div class="bg-white border rounded-lg shadow p-4 text-center">
            <p class="text-cyan-600 font-semibold flex items-center justify-center gap-2 uppercase">
                <i class="fas fa-file-invoice"></i> Billing Bulan Ini
            </p>
            <p class="text-2xl font-bold mt-2 text-gray-800">
                Rp {{ number_format($billingMonth,0,',','.') }}
            </p>
            <p class="text-xs text-gray-600 mt-1">Lunas: Rp {{ number_format($totalBillingLunasAll, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-600">Belum Lunas: Rp {{ number_format($totalBillingBelumAll, 0, ',', '.') }}</p>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="bg-white border rounded-lg shadow p-4 text-center">
            <p class="text-red-600 font-semibold flex items-center justify-center gap-2 uppercase">
                <i class="fas fa-minus-circle"></i> Pengeluaran Bulan Ini
            </p>
            <p class="text-2xl font-bold mt-2 text-gray-800">
                Rp {{ number_format($pengeluaranMonth,0,',','.') }}
            </p>
            <p class="text-xs text-gray-600 mt-1">Semua kategori biaya</p>
        </div>

        <!-- Laba/Rugi Bulan Ini -->
        <div class="bg-white border rounded-lg shadow p-4 text-center">
            <p class="text-yellow-600 font-semibold flex items-center justify-center gap-2 uppercase">
                <i class="fas fa-coins"></i> Laba / Rugi Bulan Ini
            </p>
            <p class="text-2xl font-bold mt-2 text-gray-800">
                Rp {{ number_format($labaRugi,0,',','.') }}
            </p>
            <p class="text-xs mt-1 flex items-center justify-center gap-1 {{ $labaRugi >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <i class="fas {{ $labaRugi >= 0 ? 'fa-thumbs-up' : 'fa-thumbs-down' }}"></i>
                {{ $labaRugi >= 0 ? 'Keuntungan' : 'Kerugian' }}
            </p>
        </div>

        <!-- Total Akumulasi Saldo -->
        <div class="bg-white border rounded-lg shadow p-4 text-center">
            <p class="text-cyan-500 font-semibold flex items-center justify-center gap-2 uppercase">
                <i class="fas fa-piggy-bank"></i> Total Akumulasi Saldo
            </p>
            <p class="text-2xl font-bold mt-2 text-cyan-600">
                Rp {{ number_format($saldoAkumulasi,0,',','.') }}
            </p>
            <p class="text-xs text-gray-600 mt-1">Saldo keseluruhan dari semua sumber</p>
        </div>

        <!-- Efisiensi Bulan Ini -->
        <div class="bg-white border rounded-lg shadow p-4 text-center">
            <p class="text-orange-500 font-semibold flex items-center justify-center gap-2 uppercase">
                <i class="fas fa-percent"></i> Efisiensi Bulan Ini
            </p>
            <p class="text-2xl font-bold mt-2 text-gray-800">
                {{ $efisiensi }}%
            </p>
            <p class="text-xs mt-1 {{ $efisiensi >= 50 ? 'text-green-600' : 'text-red-500' }}">
                {{ $efisiensi >= 50 ? 'Efisien' : 'Perlu Perhatian' }}
            </p>
        </div>
    </div>
 
   <!-- Analisis Keuangan -->
<div class="mt-6 bg-white shadow-md rounded-xl p-6">
    <h3 class="font-semibold text-gray-700 flex items-center gap-2 mb-4">
        <i class="fas fa-chart-pie text-blue-500"></i>
        Analisis Keuangan - {{ $periode }}
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Rasio Pendapatan Online vs Offline -->
        <div>
            <p class="font-medium text-gray-600 flex items-center gap-2">
                <i class="fas fa-globe text-green-500"></i> Pendapatan Online vs Offline
            </p>
            <div class="w-full h-3 rounded-full overflow-hidden mt-2 flex">
                <!-- Online -->
                <div class="bg-green-500 h-3" style="width: {{ $rasioPendapatanOnline ?? 0 }}%"></div>
                <!-- Offline -->
                <div class="bg-red-500 h-3" style="width: {{ 100 - ($rasioPendapatanOnline ?? 0) }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">
                <span class="text-green-600 font-semibold">{{ number_format($rasioPendapatanOnline ?? 0, 1) }}% Online</span> | 
                <span class="text-red-600 font-semibold">{{ number_format(100 - ($rasioPendapatanOnline ?? 0), 1) }}% Offline</span>
            </p>
        </div>

        <!-- Rasio Voucher vs Billing -->
        <div>
            <p class="font-medium text-gray-600 flex items-center gap-2">
                <i class="fas fa-receipt text-blue-500"></i> Voucher vs Billing
            </p>
            <div class="w-full h-3 rounded-full overflow-hidden mt-2 flex">
                <!-- Voucher -->
                <div class="bg-blue-500 h-3" style="width: {{ $rasioVoucher ?? 0 }}%"></div>
                <!-- Billing -->
                <div class="bg-orange-400 h-3" style="width: {{ 100 - ($rasioVoucher ?? 0) }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">
                <span class="text-blue-600 font-semibold">{{ number_format($rasioVoucher ?? 0, 1) }}% Voucher</span> | 
                <span class="text-orange-500 font-semibold">{{ number_format(100 - ($rasioVoucher ?? 0), 1) }}% Billing</span>
            </p>
        </div>

    </div>
</div>



<script>
function openModal(jenis) {
    document.getElementById('modalPembukuan').classList.remove('hidden');
    document.getElementById('jenisInput').value = jenis;
    document.getElementById('modalTitle').innerText = 
        jenis === 'pemasukkan' ? 'Tambah Pemasukkan' : 'Tambah Pengeluaran';
}
function closeModal() {
    document.getElementById('modalPembukuan').classList.add('hidden');
}

// Script real-time clock
function updateClock() {
    const now = new Date();
    const day   = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const year  = now.getFullYear();
    const hours = String(now.getHours()).padStart(2, '0');
    const mins  = String(now.getMinutes()).padStart(2, '0');
    const secs  = String(now.getSeconds()).padStart(2, '0');

    document.getElementById('realTimeClock').textContent = `${day}/${month}/${year} ${hours}:${mins}:${secs}`;
}

setInterval(updateClock, 1000);
updateClock();

 function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
  }

  // Tutup Modal
  function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
  }

  // Submit Form
  document.getElementById('formPemasukkan').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Data pemasukkan berhasil ditambahkan!');
    closeModal('modalPemasukkan');
    // di sini bisa ditambah AJAX fetch() ke backend kalau mau simpan ke DB
  });

      // Fungsi buka modal
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove("hidden");   // tampilkan
        }
    }

    // Fungsi tutup modal
    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add("hidden");   // sembunyikan
        }
    }

    // Tutup modal kalau klik di luar konten
    window.addEventListener("click", function(e) {
        const modals = document.querySelectorAll(".modal-overlay");
        modals.forEach(modal => {
            if (e.target === modal) {
                modal.classList.add("hidden");
            }
        });
    });

        // Efek klik dengan flash warna
    document.querySelectorAll('.pengeluaran-item').forEach(item => {
        item.addEventListener('click', function() {
            this.classList.add('animate-pulse');
            setTimeout(() => this.classList.remove('animate-pulse'), 500);
        });
    });

      document.querySelectorAll('.menu-card').forEach(card => {
        card.addEventListener('click', function() {
            this.classList.add('animate-pulse');
            setTimeout(() => this.classList.remove('animate-pulse'), 400);
        });
    });
    </script>

@endsection

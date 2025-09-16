<?php

namespace App\Exports;

use App\Models\Pembukuan;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PembukuanExport implements FromView
{
    protected Carbon $periode;

    public function __construct(Carbon $periode)
    {
        $this->periode = $periode;
    }

    public function view(): View
    {
        $tahun = $this->periode->year;
        $bulan = $this->periode->month;

        // Data transaksi bulan terpilih
        $data = Pembukuan::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('created_at', 'desc')
            ->get();

        // Total pemasukkan (dari tabel pembukuan)
        $totalPemasukkan = Pembukuan::where('jenis', 'pemasukkan')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->sum('nominal');

        // Total pengeluaran (dari tabel pembukuan)
        $totalPengeluaran = Pembukuan::where('jenis', 'pengeluaran')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->sum('nominal');

        // Voucher bulan ini (gunakan kolom price — ganti ke nominal jika DB kamu pakai itu)
        $voucherMonth = Voucher::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->sum('price');

        $voucherOnlineMonth = Voucher::where('status', 'online')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->sum('price');

        $voucherOfflineMonth = Voucher::where('status', 'offline')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->sum('price');

        // Akumulasi semua waktu
        $totalVoucherAll = Voucher::sum('price');
        $totalVoucherOnline = Voucher::where('status', 'online')->sum('price');
        $totalVoucherOffline = Voucher::where('status', 'offline')->sum('price');

        // Perbandingan dengan bulan sebelumnya (opsional)
        $prev = $this->periode->copy()->subMonth();
        $totalPrev = Voucher::whereYear('created_at', $prev->year)
            ->whereMonth('created_at', $prev->month)
            ->sum('price');
        $perbandingan = $totalPrev > 0
            ? round((($voucherMonth - $totalPrev) / $totalPrev) * 100, 1)
            : 0;

        // === PENTING: hitung laba/rugi bulan ini ===
        // Pilihan: hitung berdasarkan totalPemasukkan (dari Pembukuan) atau berdasarkan voucherMonth.
        // Aku set default: labaRugi dari pembukuan (totalPemasukkan - totalPengeluaran)
        $labaRugi = $totalPemasukkan - $totalPengeluaran;

        // Saldo akumulasi: seluruh pemasukan (voucher semua waktu) - seluruh pengeluaran (semua waktu)
        $saldoAkumulasi = Voucher::sum('price') - Pembukuan::where('jenis', 'pengeluaran')->sum('nominal');

        return view('billing.exports.pembukuan', [
            'periode'              => $this->periode,
            'data'                 => $data,
            'totalPemasukkan'      => $totalPemasukkan,
            'totalPengeluaran'     => $totalPengeluaran,
            'voucherMonth'         => $voucherMonth,
            'voucherOnlineMonth'   => $voucherOnlineMonth,
            'voucherOfflineMonth'  => $voucherOfflineMonth,
            'totalVoucherAll'      => $totalVoucherAll,
            'totalVoucherOnline'   => $totalVoucherOnline,
            'totalVoucherOffline'  => $totalVoucherOffline,
            'totalPrev'            => $totalPrev,
            'perbandingan'         => $perbandingan,
            'labaRugi'             => $labaRugi,        // <--- DITAMBAHKAN
            'saldoAkumulasi'       => $saldoAkumulasi,  // opsional tapi berguna
        ]);
    }
}

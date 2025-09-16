<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Voucher;
use App\Models\Pembukuan;
use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Billing;
use App\Models\Customer;


class BillingController extends Controller
{

public function pembayaran(Request $request)
{
    $status = $request->get('status', 'all');

    $query = Customer::query();

    // jika filter status dipilih (mis. 'lunas' atau 'belum lunas')
    if ($status !== 'all') {
        // dukung kemungkinan dua nama kolom: 'status' atau 'status_pembayaran'
        $query->where(function ($q) use ($status) {
            $q->where('status', $status)
              ->orWhere('status_pembayaran', $status);
        });
    }

    // ambil data (urut terbaru dulu)
    $customers = $query->orderBy('created_at', 'desc')->get();

    // normalisasi / tambah atribut bantu agar Blade lebih konsisten
    $customers = $customers->map(function ($c) {
        // jumlah/tagihan (fallback ke beberapa nama kolom umum)
        $c->amount = $c->biaya ?? $c->billing_amount ?? $c->amount ?? 0;

        // metode pembayaran (fallback)
        $c->payment_method = $c->koneksi ?? $c->payment_method ?? 'manual';

        // status label (pastikan berisi 'lunas' atau 'belum lunas' sesuai DB)
        $c->status_label = $c->status_pembayaran ?? $c->status ?? 'belum lunas';

        return $c;
    });

    return view('billing.pembayaran', compact('customers', 'status'));
}
    
public function pembukuan(Request $request)
{
    $periode = $request->input('periode', Carbon::now()->format('Y-m'));

    $start = Carbon::parse($periode . '-01')->startOfMonth();
    $end   = Carbon::parse($periode . '-01')->endOfMonth();

    // === VOUCHER ===
    $voucherMonth = Voucher::whereIn('tipe', ['online', 'offline'])
                           ->whereBetween('created_at', [$start, $end])
                           ->sum('price');

    $voucherOnlineMonth = Voucher::where('tipe', 'online')
                                 ->whereBetween('created_at', [$start, $end])
                                 ->sum('price');

    $voucherOfflineMonth = Voucher::where('tipe', 'offline')
                                  ->whereBetween('created_at', [$start, $end])
                                  ->sum('price');

    $totalVoucherOnline  = Voucher::where('tipe', 'online')->sum('price');
    $totalVoucherOffline = Voucher::where('tipe', 'offline')->sum('price');
    $totalVoucherAll     = $totalVoucherOnline + $totalVoucherOffline;

    // === BILLING (ambil dari tabel customers) ===
    // Sudah Lunas
    $billingMonthLunas = Customer::where('status_pembayaran', 'Lunas')
        ->whereBetween('tanggal_tagihan', [$start, $end])
        ->sum('biaya');

    $totalBillingLunasAll = Customer::where('status_pembayaran', 'Lunas')
        ->sum('biaya');

    // Belum Lunas
    $billingMonthBelum = Customer::where('status_pembayaran', 'Belum Lunas')
        ->whereBetween('tanggal_tagihan', [$start, $end])
        ->sum('biaya');

    $totalBillingBelumAll = Customer::where('status_pembayaran', 'Belum Lunas')
        ->sum('biaya');

    // Kombinasi
    $billingMonth = $billingMonthLunas + $billingMonthBelum;
    $totalBillingAll = $totalBillingLunasAll + $totalBillingBelumAll;

    // Pisah Online vs Manual (hanya yang sudah Lunas)
    $billingOnlineMonth = Customer::where('status_pembayaran', 'Lunas')
        ->whereBetween('tanggal_tagihan', [$start, $end])
        ->where('koneksi', 'Hotspot')
        ->sum('biaya');

    $billingManualMonth = Customer::where('status_pembayaran', 'Lunas')
        ->whereBetween('tanggal_tagihan', [$start, $end])
        ->where('koneksi', '!=', 'Hotspot')
        ->sum('biaya');

    // === PENGELUARAN ===
$totalPengeluaran = Expense::whereBetween('created_at', [$start, $end])
                           ->sum('jumlah');

$pengeluaranMonth = $totalPengeluaran;

// === LABA/RUGI ===
$labaRugi = ($voucherMonth + $billingMonth) - $pengeluaranMonth;

// === SALDO AKUMULASI ===
$saldoAkumulasi = ($totalVoucherAll + $totalBillingAll) - Expense::sum('jumlah');

// === EFISIENSI ===
$efisiensi = $billingMonth > 0
    ? round(($labaRugi / $billingMonth) * 100, 2)
    : 0;

// === DATA BULAN LALU (UNTUK PERBANDINGAN & PERTUMBUHAN) ===
$prevStart = $start->copy()->subMonth()->startOfMonth();
$prevEnd   = $end->copy()->subMonth()->endOfMonth();

$voucherPrev = Voucher::whereIn('tipe', ['online', 'offline'])
                      ->whereBetween('created_at', [$prevStart, $prevEnd])
                      ->sum('price');

$billingPrev = Customer::where('status_pembayaran', 'Lunas')
                       ->whereBetween('tanggal_tagihan', [$prevStart, $prevEnd])
                       ->sum('biaya');

$totalPrev = $voucherPrev + $billingPrev;

// === PERTUMBUHAN ===
if ($totalPrev > 0) {
    $pertumbuhan = round((($voucherMonth + $billingMonth - $totalPrev) / $totalPrev) * 100, 2);
} else {
    $pertumbuhan = ($voucherMonth + $billingMonth) > 0 ? 100 : 0;
}

// === RASIO PENDAPATAN ONLINE vs OFFLINE ===
$totalPendapatanMonth = $voucherMonth + $billingMonth;
$rasioPendapatanOnline = $totalPendapatanMonth > 0
    ? round(($voucherOnlineMonth + $billingOnlineMonth) / $totalPendapatanMonth * 100, 1)
    : 0;

// === RASIO VOUCHER vs BILLING ===
$rasioVoucher = $totalPendapatanMonth > 0
    ? round(($voucherMonth / $totalPendapatanMonth) * 100, 1)
    : 0;

// === PERBANDINGAN BULAN INI vs BULAN LALU ===
if ($totalPrev > 0) {
    $perbandingan = round((($voucherMonth + $billingMonth - $totalPrev) / $totalPrev) * 100, 2);
} else {
    $perbandingan = ($voucherMonth + $billingMonth) > 0 ? 100 : 0;
}

return view('billing.pembukuan', compact(
    'periode',
    'voucherMonth',
    'voucherOnlineMonth',
    'voucherOfflineMonth',
    'totalVoucherOnline',
    'totalVoucherOffline',
    'totalVoucherAll',
    'billingMonth',
    'billingMonthLunas',
    'totalBillingLunasAll',
    'billingMonthBelum',
    'totalBillingBelumAll',
    'billingOnlineMonth',
    'billingManualMonth',
    'totalBillingAll',
    'totalPrev',
    'perbandingan',
    'pengeluaranMonth',
    'labaRugi',
    'saldoAkumulasi',
    'efisiensi',
    'pertumbuhan',
    'rasioPendapatanOnline',
    'rasioVoucher',
    'totalPengeluaran',
        'totalPendapatanMonth' // ✅ jangan lupa ini

));

}


}
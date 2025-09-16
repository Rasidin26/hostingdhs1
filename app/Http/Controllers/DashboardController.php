<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Voucher;
use App\Models\Device;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now   = Carbon::now('Asia/Jakarta');
        $start = $now->copy()->startOfMonth();
        $end   = $now->copy()->endOfMonth();

        // ===== Informasi Keuangan =====
        $saldoAkun = Sales::where('type', 'topup')->sum('price');

        $voucherOnlineMonth  = Voucher::where('tipe', 'online')
                                      ->whereBetween('created_at', [$start, $end])
                                      ->sum('price');

        $voucherOfflineMonth = Voucher::where('tipe', 'offline')
                                      ->whereBetween('created_at', [$start, $end])
                                      ->sum('price');

        $pendapatanVoucher = $voucherOnlineMonth + $voucherOfflineMonth;

        $pembayaranBilling = Customer::where('status_pembayaran', 'Lunas')
                                     ->whereBetween('tanggal_tagihan', [$start, $end])
                                     ->sum('biaya');

        $billingOnlineMonth = Customer::where('status_pembayaran', 'Lunas')
                                      ->whereBetween('tanggal_tagihan', [$start, $end])
                                      ->where('koneksi', 'Hotspot')
                                      ->sum('biaya');

        $billingManualMonth = Customer::where('status_pembayaran', 'Lunas')
                                      ->whereBetween('tanggal_tagihan', [$start, $end])
                                      ->where('koneksi', '!=', 'Hotspot')
                                      ->sum('biaya');

        // ===== Perangkat milik user login =====
        $devices = Device::where('user_id', Auth::id())->get();

        // ===== Ringkasan Tagihan Outstanding =====
        $totalTagihan   = Customer::where('status_pembayaran', 'Belum Lunas')->sum('biaya');
        $totalCustomer  = Customer::count();

        $belumBayar = Customer::where('status_pembayaran', 'Belum Lunas')
                              ->whereDate('tanggal_tagihan', '>=', $now->toDateString())
                              ->count();

        $overdue = Customer::where('status_pembayaran', 'Belum Lunas')
                           ->whereDate('tanggal_tagihan', '<', $now->toDateString())
                           ->whereDate('tanggal_tagihan', '>=', $now->copy()->subMonths(3)->toDateString())
                           ->count();

        $kritis = Customer::where('status_pembayaran', 'Belum Lunas')
                          ->whereDate('tanggal_tagihan', '<', $now->copy()->subMonths(3)->toDateString())
                          ->count();

        $customerOverdue = $overdue;
        $customerKritis  = $kritis;

        $rataRataTunggakan = (float) (Customer::where('status_pembayaran', 'Belum Lunas')
            ->selectRaw('AVG(TIMESTAMPDIFF(MONTH, tanggal_tagihan, NOW())) as avg_months')
            ->value('avg_months') ?? 0);

        // ===== Statistik Customer =====
        $statistik = [
            'pendaftaran_online' => 0,
            'customer_baru'      => Customer::whereMonth('created_at', $now->month)
                                            ->whereYear('created_at', $now->year)
                                            ->count(),
            'total_customer'     => $totalCustomer,
            'belum_bayar'        => $belumBayar,
            'overdue'            => $overdue,
            'parsial'            => 0,
            'isolir'             => Customer::where('status', 'isolir')->count(),
            'aktif'              => Customer::where('status', 'aktif')->count(),
            'bulan_ini_lunas'    => Customer::where('status_pembayaran', 'Lunas')
                                            ->whereMonth('tanggal_tagihan', $now->month)
                                            ->whereYear('tanggal_tagihan', $now->year)
                                            ->count(),
            'kritis'             => $kritis,
        ];

        // ===== Laporan Penjualan (sesuai pembukuan) =====
        $voucherToday = Voucher::whereDate('created_at', $now->toDateString())->sum('price');
        $billingToday = Customer::where('status_pembayaran', 'Lunas')
                                ->whereDate('tanggal_tagihan', $now->toDateString())
                                ->sum('biaya');

        $salesTodayAmount = $voucherToday + $billingToday;
        $salesTodayCount  = ($voucherToday > 0 ? 1 : 0) + ($billingToday > 0 ? 1 : 0);

        $voucherMonth = Voucher::whereBetween('created_at', [$start, $end])->sum('price');
        $billingMonth = Customer::where('status_pembayaran', 'Lunas')
                                ->whereBetween('tanggal_tagihan', [$start, $end])
                                ->sum('biaya');

        $salesMonthAmount = $voucherMonth + $billingMonth;
        $salesMonthCount  = ($voucherMonth > 0 ? 1 : 0) + ($billingMonth > 0 ? 1 : 0);

        // ===== Target & Info Tampilan =====
        $todayTarget   = 500000;     // contoh target harian
        $monthTarget   = 15000000;   // contoh target bulanan
        $lastUpdate    = $now->format('H:i');
        $currentPeriod = $now->translatedFormat('F Y');

        // ===== Return View =====
        return view('dashboard.index', compact(
            'saldoAkun',
            'devices',
            'pendapatanVoucher',
            'voucherOnlineMonth',
            'voucherOfflineMonth',
            'pembayaranBilling',
            'billingOnlineMonth',
            'billingManualMonth',
            'totalTagihan',
            'customerOverdue',
            'totalCustomer',
            'customerKritis',
            'rataRataTunggakan',
            'statistik',
            'salesTodayAmount',
            'salesTodayCount',
            'salesMonthAmount',
            'salesMonthCount',
            'todayTarget',
            'monthTarget',
            'lastUpdate',
            'currentPeriod'
        ));
    }
}

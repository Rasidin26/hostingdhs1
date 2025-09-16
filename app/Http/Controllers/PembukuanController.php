<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembukuan;
use App\Models\Voucher;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Customer;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PembukuanExport;

class PembukuanController extends Controller
{
    public function index(Request $request)
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

        // === BILLING ===
        $billingMonthLunas = Customer::where('status_pembayaran', 'Lunas')
            ->whereBetween('tanggal_tagihan', [$start, $end])
            ->sum('biaya');

        $totalBillingLunasAll = Customer::where('status_pembayaran', 'Lunas')->sum('biaya');

        $billingMonthBelum = Customer::where('status_pembayaran', 'Belum Lunas')
            ->whereBetween('tanggal_tagihan', [$start, $end])
            ->sum('biaya');

        $totalBillingBelumAll = Customer::where('status_pembayaran', 'Belum Lunas')->sum('biaya');

        $billingMonth = $billingMonthLunas + $billingMonthBelum;
        $totalBillingAll = $totalBillingLunasAll + $totalBillingBelumAll;

        $billingOnlineMonth = Customer::where('status_pembayaran', 'Lunas')
            ->whereBetween('tanggal_tagihan', [$start, $end])
            ->where('koneksi', 'Hotspot')
            ->sum('biaya');

        $billingManualMonth = Customer::where('status_pembayaran', 'Lunas')
            ->whereBetween('tanggal_tagihan', [$start, $end])
            ->where('koneksi', '!=', 'Hotspot')
            ->sum('biaya');

        // === PENGELUARAN ===
        $totalPengeluaran = Expense::whereBetween('created_at', [$start, $end])->sum('jumlah');
        $pengeluaranMonth = $totalPengeluaran;

        // === LABA RUGI ===
        $labaRugi = ($voucherMonth + $billingMonth) - $pengeluaranMonth;

        // === SALDO AKUMULASI ===
        $saldoAkumulasi = ($totalVoucherAll + $totalBillingAll) - Expense::sum('jumlah');

        // === EFISIENSI ===
        $efisiensi = $billingMonth > 0 ? round(($labaRugi / $billingMonth) * 100, 2) : 0;

        // === DATA BULAN LALU ===
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
        $pertumbuhan = $totalPrev > 0
            ? round((($voucherMonth + $billingMonth - $totalPrev) / $totalPrev) * 100, 2)
            : (($voucherMonth + $billingMonth) > 0 ? 100 : 0);

        // === RASIO ===
        $totalPendapatanMonth = $voucherMonth + $billingMonth;
        $rasioPendapatanOnline = $totalPendapatanMonth > 0
            ? round(($voucherOnlineMonth + $billingOnlineMonth) / $totalPendapatanMonth * 100, 1)
            : 0;

        $rasioVoucher = $totalPendapatanMonth > 0
            ? round(($voucherMonth / $totalPendapatanMonth) * 100, 1)
            : 0;

        // === PERBANDINGAN ===
        $perbandingan = $totalPrev > 0
            ? round((($voucherMonth + $billingMonth - $totalPrev) / $totalPrev) * 100, 2)
            : (($voucherMonth + $billingMonth) > 0 ? 100 : 0);

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
            'totalPendapatanMonth'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis'      => 'required|in:pemasukkan,pengeluaran',
            'keterangan' => 'nullable|string',
            'nominal'    => 'required|numeric',
            'metode'     => 'nullable|in:online,offline,manual',
        ]);

        Pembukuan::create([
            'jenis'      => $request->jenis,
            'keterangan' => $request->keterangan,
            'nominal'    => $request->nominal,
            'metode'     => $request->metode ?? 'offline',
            'periode'    => Carbon::now()->startOfMonth(),
        ]);

        return back()->with('success', 'Data berhasil disimpan!');
    }

    public function export(Request $request)
    {
        $periode = $request->get('periode') 
            ? Carbon::parse($request->get('periode') . '-01') 
            : Carbon::now();

        $bulanNama = $periode->translatedFormat('F Y');
        $namaFile  = "Laporan Pembukuan Lengkap {$bulanNama}.xlsx";

        return Excel::download(new PembukuanExport($periode), $namaFile);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Customer;
use App\Exports\FinanceExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel; // ✅ ini yang benar
use App\Exports\FinanceSummaryExport;
use Carbon\Carbon; // ✅ HARUSNYA begini
use App\Models\Billing;
use App\Models\Voucher;
use App\Models\Expense;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdminFinanceController extends Controller
{
   public function index(Request $request)
{
    $start = Carbon::now()->startOfMonth();
    $end   = Carbon::now()->endOfMonth();

    // === VOUCHER bulan ini ===
    $voucherMonth = Voucher::whereIn('tipe', ['online', 'offline'])
                           ->whereBetween('created_at', [$start, $end])
                           ->sum('price');

    // === BILLING bulan ini ===
    $billingMonth = Customer::where('status_pembayaran', 'Lunas')
                            ->whereBetween('tanggal_tagihan', [$start, $end])
                            ->sum('biaya');

    // === TOTAL PENDAPATAN bulan ini ===
    $totalPendapatan = $voucherMonth + $billingMonth;

    // === PENGELUARAN dari masing-masing tabel ===
    $gajiKaryawan   = \App\Models\Gaji::whereBetween('created_at', [$start, $end])->sum('total_gaji');
    $bayarKangTagih = \App\Models\BayarKangTagih::whereBetween('created_at', [$start, $end])->sum('jumlah');
    $ListrikPdamPulsa = \App\Models\ListrikPdamPulsa::whereBetween('created_at', [$start, $end])->sum('jumlah');
    $pasangBaru     = \App\Models\PasangBaru::whereBetween('created_at', [$start, $end])->sum('biaya');
    $perbaikanAlat  = \App\Models\PerbaikanAlat::whereBetween('created_at', [$start, $end])->sum('biaya');

    // === TOTAL PENGELUARAN bulan ini ===
    $totalPengeluaran = $gajiKaryawan + $bayarKangTagih + $ListrikPdamPulsa + $pasangBaru + $perbaikanAlat;

    // === TOTAL SISA ===
    $totalSisa = $totalPendapatan - $totalPengeluaran;

    // === TOTAL SETOR ===
    $totalSetor = 0;
    if (Schema::hasTable('setoran')) {
        $totalSetor = DB::table('setoran')->sum('jumlah');
    }

    // === REKAP per device ===
    $devices = Device::with('transactions')->get();
    $rekap = $devices->map(function ($dev) {
        $totalTransaksi   = $dev->transactions->sum('amount');
        $totalSetor       = $dev->transactions->sum('setor_amount');
        $totalPengeluaran = $dev->transactions->sum('pengeluaran_amount');
        $sisa             = $totalTransaksi - $totalSetor - $totalPengeluaran;

        return [
            'nama'              => $dev->name,
            'total_transaksi'   => $totalTransaksi,
            'total_setor'       => $totalSetor,
            'total_pengeluaran' => $totalPengeluaran,
            'sisa'              => $sisa,
            'persentase'        => $totalTransaksi > 0
                                    ? round(($sisa / $totalTransaksi) * 100, 2)
                                    : 0,
        ];
    })->values();

    // === SUMMARY ===
    $summary = [
        'total_pendapatan'  => $totalPendapatan,
        'total_setor'       => $totalSetor,
        'total_pengeluaran' => $totalPengeluaran,
        'total_sisa'        => $totalSisa,
        'total_pelanggan'   => Customer::count(),
    ];

    return view('admin.finance.index', compact(
        'summary',
        'rekap',
        'gajiKaryawan',
        'bayarKangTagih',
        'ListrikPdamPulsa',
        'pasangBaru',
        'perbaikanAlat',
        'totalPengeluaran'
    ));
}



    public function semua()
    {
        // Ambil semua devices + transactions
        $devices = Device::with('transactions')->get();

        // Rekap per device
        $rekap = $devices->map(function ($dev) {
            $totalTransaksi   = $dev->transactions->sum('amount');
            $totalSetor       = $dev->transactions->sum('setor_amount');
            $totalPengeluaran = $dev->transactions->sum('pengeluaran_amount');
            $sisa             = $totalTransaksi - $totalSetor - $totalPengeluaran;

            return [
                'nama'              => $dev->name,
                'total_transaksi'   => $totalTransaksi,
                'total_setor'       => $totalSetor,
                'total_pengeluaran' => $totalPengeluaran,
                'sisa'              => $sisa,
                'persentase'        => $totalTransaksi > 0
                                        ? round(($sisa / $totalTransaksi) * 100, 2)
                                        : 0,
            ];
        });

        // Ringkasan
        $summary = [
            'total_pendapatan'  => $rekap->sum('total_transaksi'),
            'total_setor'       => $rekap->sum('total_setor'),
            'total_pengeluaran' => $rekap->sum('total_pengeluaran'),
            'total_sisa'        => $rekap->sum('sisa'),
            'total_pelanggan'   => Customer::count(),
        ];

        // Buat riwayat transaksi sederhana (optional, kalau mau tampil di view)
        $riwayat = collect();
        foreach ($devices as $dev) {
            foreach ($dev->transactions as $t) {
                $riwayat->push([
                    'tanggal'   => optional($t->created_at)->format('Y-m-d'),
                    'jenis'     => $t->amount > 0 ? 'pemasukan' : 'pengeluaran',
                    'jumlah'    => $t->amount ?? 0,
                    'deskripsi' => $t->description ?? '-',
                    'sumber'    => $dev->name,
                ]);
            }
        }

        return view('admin.finance.semuapembukuan', compact('rekap', 'summary', 'riwayat'));
    }
     public function exportExcel()
    {
        return Excel::download(new FinanceExport, 'finance_rekap.xlsx');
    }

    public function exportPDF()
    {
        $rekap = Device::with('transactions')->get()->map(function ($dev) {
            $totalTransaksi   = $dev->transactions->sum('amount');
            $totalSetor       = $dev->transactions->sum('setor_amount');
            $totalPengeluaran = $dev->transactions->sum('pengeluaran_amount');
            $sisa             = $totalTransaksi - $totalSetor - $totalPengeluaran;

            return [
                'nama'              => $dev->name,
                'total_transaksi'   => $totalTransaksi,
                'total_setor'       => $totalSetor,
                'total_pengeluaran' => $totalPengeluaran,
                'sisa'              => $sisa,
                'persentase'        => $totalTransaksi > 0 
                                         ? round(($sisa / $totalTransaksi) * 100, 2) 
                                         : 0,
            ];
        });

        $pdf = Pdf::loadView('admin.finance.export_pdf', compact('rekap'));
        return $pdf->download('finance_rekap.pdf');
    }

   public function pembayaran(Request $request)
{
    // ambil filter opsional dari request (jika pakai filter)
    $bulan  = $request->input('bulan');   // format: 2025-09 (opsional)
    $device = $request->input('device');  // nama device (opsional)

    // Query devices + transactions dengan optional filter bulan
    $devicesQuery = Device::with(['transactions' => function ($q) use ($bulan) {
        if ($bulan) {
            $q->whereMonth('created_at', date('m', strtotime($bulan)))
              ->whereYear('created_at', date('Y', strtotime($bulan)));
        }
    }]);

    if ($device) {
        $devicesQuery->where('name', $device);
    }

    $devices = $devicesQuery->get();

    // Rekap per device (dipakai juga di ringkasan)
    $rekap = $devices->map(function ($dev) {
        $totalTransaksi   = $dev->transactions->sum('amount');
        $totalSetor       = $dev->transactions->sum('setor_amount');
        $totalPengeluaran = $dev->transactions->sum('pengeluaran_amount');
        $sisa             = $totalTransaksi - $totalSetor - $totalPengeluaran;

        return [
            'nama'              => $dev->name,
            'total_transaksi'   => $totalTransaksi,
            'total_setor'       => $totalSetor,
            'total_pengeluaran' => $totalPengeluaran,
            'sisa'              => $sisa,
            'persentase'        => $totalTransaksi > 0
                                    ? round(($sisa / $totalTransaksi) * 100, 2)
                                    : 0,
        ];
    });

    // Buat daftar pembayaran (ambil dari setor_amount)
    $pembayaran = collect();
    foreach ($devices as $dev) {
        foreach ($dev->transactions as $t) {
            $setor = $t->setor_amount ?? 0;
            if ($setor <= 0) continue; // hanya yang ada setor
            $pembayaran->push([
                'tanggal'      => optional($t->created_at)->format('Y-m-d H:i:s'),
                'customer'     => $t->customer?->name ?? ($t->customer_name ?? '-'),
                'device'       => $dev->name,
                'jumlah'       => $setor,
                'periode'      => $t->periode ?? '-',
                'status'       => $t->status ?? '-',
                'dicatat_oleh' => $t->created_by ?? 'Admin',
                'keterangan'   => $t->description ?? '-',
            ]);
        }
    }

    $total_pembayaran = $pembayaran->sum('jumlah');

    // Ringkasan (bisa dipakai di kartu-kartu)
    $summary = [
        'total_pendapatan'  => $rekap->sum('total_transaksi'),
        'total_setor'       => $rekap->sum('total_setor'),
        'total_pengeluaran' => $rekap->sum('total_pengeluaran'),
        'total_sisa'        => $rekap->sum('sisa'),
        'total_pelanggan'   => Customer::count(),
    ];

    // kirim semua variabel ke view supaya view yang mengandalkan $rekap tidak error
    return view('admin.finance.pembayaranByAdmin', compact(
        'rekap',
        'summary',
        'pembayaran',
        'total_pembayaran',
        'bulan',
        'device'
    ));

}
public function Rangkuman(Request $request)
{
    $year  = $request->input('year', date('Y'));
    $month = $request->input('month');

    // 🔹 Trend bulanan
    $trend = collect(range(1, 12))->map(function ($m) use ($year) {
        $pemasukan   = DB::table('transactions')
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $m)
                        ->where('type', 'pemasukan')
                        ->sum('amount');

        $pengeluaran = DB::table('transactions')
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $m)
                        ->where('type', 'pengeluaran')
                        ->sum('amount');

        return [
            'bulan'       => $m,
            'nama_bulan'  => date('F', mktime(0, 0, 0, $m, 1)),
            'pemasukan'   => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'profit'      => $pemasukan - $pengeluaran,
        ];
    });

    // 🔹 Ringkasan total
    $totalPemasukan = $trend->sum('pemasukan');
    $totalPengeluaran = $trend->sum('pengeluaran');
    $netProfit = $totalPemasukan - $totalPengeluaran;
    $profitMargin = $totalPemasukan > 0 ? round(($netProfit / $totalPemasukan) * 100, 2) : 0;

    // 🔹 Hitung total pelanggan aktif (distinct customer_id dari transaksi tahun ini)
    $totalPelangganAktif = DB::table('transactions')
                            ->whereYear('created_at', $year)
                            ->distinct('customer_id')
                            ->count('customer_id');

    $summary = [
        'total_pemasukan'   => $totalPemasukan,
        'total_pengeluaran' => $totalPengeluaran,
        'net_profit'        => $netProfit,
        'profit_margin'     => $profitMargin,
            'total_pelanggan'   => Customer::count(),
    ];

    // 🔹 Komposisi pemasukan (pie chart)
    $komposisi = [
        'voucher_online'  => DB::table('transactions')->where('type', 'voucher_online')->sum('amount'),
        'voucher_offline' => DB::table('transactions')->where('type', 'voucher_offline')->sum('amount'),
        'billing_online'  => DB::table('transactions')->where('type', 'billing_online')->sum('amount'),
        'billing_offline' => DB::table('transactions')->where('type', 'billing_offline')->sum('amount'),
    ];

    // 🔹 Top 5 pengeluaran
    $top_pengeluaran = DB::table('transactions')
                        ->select('keterangan', DB::raw('SUM(amount) as total'))
                        ->whereYear('created_at', $year)
                        ->where('type', 'pengeluaran')
                        ->groupBy('keterangan')
                        ->orderByDesc('total')
                        ->limit(5)
                        ->get();

    return view('admin.finance.summary', compact(
        'year', 'month', 'summary', 'trend', 'komposisi', 'top_pengeluaran'
    ));

}

    // ... method lainnya

    public function exportSummaryExcel(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // 🔹 Ringkasan total
        $trend = collect(range(1, 12))->map(function ($m) use ($year) {
            $pemasukan   = DB::table('transactions')
                            ->whereYear('created_at', $year)
                            ->whereMonth('created_at', $m)
                            ->where('type', 'pemasukan')
                            ->sum('amount');

            $pengeluaran = DB::table('transactions')
                            ->whereYear('created_at', $year)
                            ->whereMonth('created_at', $m)
                            ->where('type', 'pengeluaran')
                            ->sum('amount');

            return [
                'bulan'       => $m,
                'nama_bulan'  => date('F', mktime(0, 0, 0, $m, 1)),
                'pemasukan'   => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'profit'      => $pemasukan - $pengeluaran,
            ];
        });

        $totalPemasukan = $trend->sum('pemasukan');
        $totalPengeluaran = $trend->sum('pengeluaran');
        $netProfit = $totalPemasukan - $totalPengeluaran;
        $profitMargin = $totalPemasukan > 0 ? round(($netProfit / $totalPemasukan) * 100, 2) : 0;

        $summary = [
            'total_pemasukan'   => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'net_profit'        => $netProfit,
            'profit_margin'     => $profitMargin,
        ];

        // 🔹 Rincian pemasukan per tipe
        $rincian = [
            'voucher_online'  => DB::table('transactions')->where('type', 'voucher_online')->sum('amount'),
            'voucher_offline' => DB::table('transactions')->where('type', 'voucher_offline')->sum('amount'),
            'billing_online'  => DB::table('transactions')->where('type', 'billing_online')->sum('amount'),
            'billing_offline' => DB::table('transactions')->where('type', 'billing_offline')->sum('amount'),
        ];

        // 🔹 Top 5 pengeluaran
        $top_pengeluaran = DB::table('transactions')
                            ->select('keterangan', DB::raw('SUM(amount) as total'))
                            ->whereYear('created_at', $year)
                            ->where('type', 'pengeluaran')
                            ->groupBy('keterangan')
                            ->orderByDesc('total')
                            ->limit(5)
                            ->get();

        // Export via FinanceSummaryExport
        return Excel::download(
            new FinanceSummaryExport($summary, $rincian, $top_pengeluaran),
            "finance_summary_{$year}.xlsx"
        );
    }
}

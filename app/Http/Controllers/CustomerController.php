<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Area;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use App\Imports\CustomersImport;

class CustomerController extends Controller
{
public function index(Request $request)
{
    $areas = Area::all();
    $packages = Package::all();
    $statuses = ['Belum Lunas', 'Lunas']; // hanya dua status

    $query = Customer::with(['area', 'package']);

    if ($request->filled('area_id')) {
        $query->where('area_id', $request->area_id);
    }

    if ($request->filled('package_id')) {
        $query->where('package_id', $request->package_id);
    }

    if ($request->filled('status_pembayaran')) {
        $query->where('status_pembayaran', $request->status_pembayaran);
    }

    $customers = $query->orderBy('nama', 'asc')->paginate(10)->appends($request->all());

    // ================= Statistik =================
    $totalPelanggan = Customer::count();
    $aktif = Customer::where('status_pembayaran', 'Lunas')->count();
    $isolir = Customer::where('status_pembayaran', 'Belum Lunas')->count(); // contoh isolir = belum lunas
    $lunas = Customer::where('status_pembayaran', 'Lunas')->count();
    
    // Tunggakan 1 Bln / 2+ Bln bisa dihitung dari tanggal_tagihan
    $tunggakan1 = Customer::where('status_pembayaran', 'Belum Lunas')
                           ->where('tanggal_tagihan', '>=', now()->subMonth()->startOfMonth())
                           ->count();

    $tunggakan2 = Customer::where('status_pembayaran', 'Belum Lunas')
                           ->where('tanggal_tagihan', '<', now()->subMonth()->startOfMonth())
                           ->count();

    // Bayar Parsial bisa artinya belum lunas
    $bayarParsial = Customer::where('status_pembayaran', 'Belum Lunas')->count();

    // Pelanggan baru, misal instalasi < 30 hari
    $pelangganBaru = Customer::where('tanggal_instalasi', '>=', now()->subDays(30))->count();

    return view('customers.index', compact(
        'customers', 'areas', 'packages', 'statuses',
        'totalPelanggan', 'aktif', 'isolir', 'lunas',
        'tunggakan1', 'tunggakan2', 'bayarParsial', 'pelangganBaru'
    ));
}

    public function create()
    {
        $areas = Area::all();
        $packages = Package::all();
        $statuses = ['Belum Lunas', 'Lunas']; // hanya dua status
        return view('customers.create', compact('areas', 'packages', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'              => 'required|string|max:255',
            'nomor_telepon'     => 'required|string|max:20',
            'email'             => 'nullable|email|max:150',
            'alamat_lengkap'    => 'required|string',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
            'koneksi'           => 'required|string',
            'package_id'        => 'required|exists:packages,id',
            'area_id'           => 'required|exists:areas,id',
            'biaya'             => 'nullable|numeric',
            'status_pembayaran' => 'nullable|in:Belum Lunas,Lunas',
            'tanggal_instalasi' => 'nullable|date',
            'tanggal_tagihan'   => 'nullable|date',
            'catatan'           => 'nullable|string',
        ]);

        $package = Package::findOrFail($validated['package_id']);

        Customer::create(array_merge($validated, [
            'biaya'             => $validated['biaya'] ?? $package->harga,
            'status_pembayaran' => $validated['status_pembayaran'] ?? 'Belum Lunas',
        ]));

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        $areas = Area::all();
        $packages = Package::all();
        $statuses = ['Belum Lunas', 'Lunas']; // hanya dua status
        return view('customers.edit', compact('customer', 'areas', 'packages', 'statuses'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'nama'              => 'required|string|max:255',
            'nomor_telepon'     => 'required|string|max:20',
            'email'             => 'nullable|email|max:150',
            'alamat_lengkap'    => 'required|string',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
            'koneksi'           => 'required|string',
            'package_id'        => 'required|exists:packages,id',
            'area_id'           => 'required|exists:areas,id',
            'biaya'             => 'nullable|numeric',
            'status_pembayaran' => 'nullable|in:Belum Lunas,Lunas',
            'tanggal_instalasi' => 'nullable|date',
            'tanggal_tagihan'   => 'nullable|date',
            'catatan'           => 'nullable|string',
        ]);

        $package = Package::findOrFail($validated['package_id']);

        $customer->update(array_merge($validated, [
            'biaya'             => $validated['biaya'] ?? $package->harga,
            'status_pembayaran' => $validated['status_pembayaran'] ?? 'Belum Lunas',
        ]));

        return redirect()->route('customers.index')->with('success', 'Data pelanggan diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Pelanggan dihapus.');
    }

    public function export()
    {
        if (Customer::count() === 0) {
            return redirect()->back()->with('error', 'Data pelanggan tidak ditemukan atau kosong.');
        }

        $now = now()->translatedFormat('F Y');
        $fileName = "Data Pelanggan {$now}.xlsx";

        return Excel::download(new CustomersExport, $fileName);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xlsx',
        ]);

        try {
            Excel::import(new CustomersImport, $request->file('csv_file'));
            return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}

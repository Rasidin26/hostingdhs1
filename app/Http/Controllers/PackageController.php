<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
{
    $packages = Package::all(); // ambil semua data paket
    return view('packages.index', compact('packages')); // dikirim ke Blade
}


    public function store(Request $request)
    {
        // --- NORMALISASI INPUT ---
        // Harga: terima "50.000", "50,000", "Rp 50.000" → jadi "50000"
        $harga = preg_replace('/[^\d]/', '', (string) $request->input('harga'));

        // Kecepatan: terima "10" atau "10 Mbps" → simpan "10"
        $kecepatan = $request->input('kecepatan');
        if (!is_null($kecepatan)) {
            $kecepatan = preg_replace('/\s*mbps$/i', '', trim((string) $kecepatan));
        }

        // Merge hasil normalisasi agar tervalidasi sebagai integer
        $request->merge([
            'harga'     => $harga,
            'kecepatan' => $kecepatan,
        ]);

        // --- VALIDASI ---
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga'      => 'required|integer|min:0',
            'kecepatan'  => 'nullable|string|max:50',
            'deskripsi'  => 'nullable|string|max:500',
        ]);

        // --- SIMPAN ---
        Package::create($request->only(['nama_paket', 'harga', 'kecepatan', 'deskripsi']));

        return redirect()->route('packages.index')->with('success', 'Paket berhasil ditambahkan');
    }

    public function edit(Package $package)
    {
        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        // --- NORMALISASI INPUT ---
        $harga = preg_replace('/[^\d]/', '', (string) $request->input('harga'));

        $kecepatan = $request->input('kecepatan');
        if (!is_null($kecepatan)) {
            $kecepatan = preg_replace('/\s*mbps$/i', '', trim((string) $kecepatan));
        }

        $request->merge([
            'harga'     => $harga,
            'kecepatan' => $kecepatan,
        ]);

        // --- VALIDASI ---
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga'      => 'required|integer|min:0',
            'kecepatan'  => 'nullable|string|max:50',
            'deskripsi'  => 'nullable|string|max:500',
        ]);

        // --- UPDATE ---
        $package->update($request->only(['nama_paket', 'harga', 'kecepatan', 'deskripsi']));

        return redirect()->route('packages.index')->with('success', 'Paket berhasil diperbarui');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('packages.index')->with('success', 'Paket berhasil dihapus');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use Illuminate\Http\Request;
use App\Exports\GajiExport;
use Maatwebsite\Excel\Facades\Excel;

class GajiController extends Controller
{
public function index()
{
    $gajis = Gaji::latest()->paginate(10);

    // total semua gaji
    $pengeluaran = Gaji::sum('total_gaji');

    return view('gaji.index', compact('gajis', 'pengeluaran'));
}


    public function create()
    {
        return view('gaji.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_karyawan' => 'required',
            'jabatan' => 'required',
            'gaji_pokok' => 'required|numeric',
            'tunjangan' => 'nullable|numeric',
            'potongan' => 'nullable|numeric',
        ]);

        $total = $request->gaji_pokok + $request->tunjangan - $request->potongan;

        Gaji::create([
            'nama_karyawan' => $request->nama_karyawan,
            'jabatan' => $request->jabatan,
            'gaji_pokok' => $request->gaji_pokok,
            'tunjangan' => $request->tunjangan ?? 0,
            'potongan' => $request->potongan ?? 0,
            'total_gaji' => $total,
        ]);

        return redirect()->route('gaji.index')->with('success', 'Data gaji berhasil ditambahkan');
    }

    public function edit(Gaji $gaji)
    {
        return view('gaji.edit', compact('gaji'));
    }

    public function update(Request $request, Gaji $gaji)
    {
        $request->validate([
            'nama_karyawan' => 'required',
            'jabatan' => 'required',
            'gaji_pokok' => 'required|numeric',
            'tunjangan' => 'nullable|numeric',
            'potongan' => 'nullable|numeric',
        ]);

        $total = $request->gaji_pokok + $request->tunjangan - $request->potongan;

        $gaji->update([
            'nama_karyawan' => $request->nama_karyawan,
            'jabatan' => $request->jabatan,
            'gaji_pokok' => $request->gaji_pokok,
            'tunjangan' => $request->tunjangan ?? 0,
            'potongan' => $request->potongan ?? 0,
            'total_gaji' => $total,
        ]);

        return redirect()->route('gaji.index')->with('success', 'Data gaji berhasil diperbarui');
    }

    public function destroy(Gaji $gaji)
    {
        $gaji->delete();
        return redirect()->route('gaji.index')->with('success', 'Data gaji berhasil dihapus');
    }

public function export()
{
    return Excel::download(new GajiExport, 'data-gaji.xlsx');
}

    public function show(Gaji $gaji)
{
    // bisa dikosongkan jika tidak digunakan
    return redirect()->route('gaji.index');

}
}
<?php

namespace App\Http\Controllers;

use App\Models\PasangBaru;
use Illuminate\Http\Request;
use App\Exports\PasangBaruExport;
use Maatwebsite\Excel\Facades\Excel;


class PasangBaruController extends Controller
{
public function index()
{
    $data = PasangBaru::latest()->get();
    $totalBiaya = PasangBaru::sum('biaya'); // hitung total dari kolom biaya

    return view('pasangbaru.index', compact('data', 'totalBiaya'));
}


    public function create()
    {
        return view('pasangbaru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'alamat' => 'required',
            'jenis_pasang' => 'required',
            'biaya' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);

        PasangBaru::create($request->all());

        return redirect()->route('pasangbaru.index')->with('success', 'Data pasang baru berhasil ditambahkan');
    }

    public function export()
{
    return Excel::download(new PasangBaruExport, 'data-pasang-baru.xlsx');
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama_pelanggan' => 'required',
        'alamat' => 'required',
        'jenis_pasang' => 'required',
        'biaya' => 'required|numeric',
        'tanggal' => 'required|date',
    ]);

    $pasang = PasangBaru::findOrFail($id);
    $pasang->update($request->all());

    return redirect()->route('pasangbaru.index')->with('success', 'Data pasang baru berhasil diperbarui');
}
public function destroy($id)
{
    $pasang = PasangBaru::findOrFail($id);
    $pasang->delete();

    return redirect()->back()->with('success', 'Data berhasil dihapus.');
}

}

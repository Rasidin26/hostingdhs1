<?php

namespace App\Http\Controllers;

use App\Models\PerbaikanAlat;
use Illuminate\Http\Request;
use App\Exports\PerbaikanAlatExport;
use Maatwebsite\Excel\Facades\Excel;


class PerbaikanAlatController extends Controller
{
public function index()
{
    $data = PerbaikanAlat::latest()->get();
    $totalBiaya = PerbaikanAlat::sum('biaya'); // hitung total biaya perbaikan

    return view('perbaikanalat.index', compact('data', 'totalBiaya'));
}


    public function create()
    {
        return view('perbaikanalat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required',
            'kerusakan' => 'required',
            'biaya' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);

        PerbaikanAlat::create([
            'nama_alat' => $request->nama_alat,
            'kerusakan' => $request->kerusakan,
            'biaya' => $request->biaya,
            'tanggal' => $request->tanggal,
            'status' => 'Proses',
        ]);

        return redirect()->route('perbaikanalat.index')->with('success', 'Data perbaikan berhasil ditambahkan');
    }

    public function export()
{
    return Excel::download(new PerbaikanAlatExport, 'data-perbaikan-alat.xlsx');
}
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kerusakan' => 'required|string|max:255',
            'biaya'     => 'required|numeric',
            'tanggal'   => 'required|date',
            'status'    => 'required|string',
        ]);

        $perbaikan = PerbaikanAlat::findOrFail($id);
        $perbaikan->update([
            'nama_alat' => $request->nama_alat,
            'kerusakan' => $request->kerusakan,
            'biaya'     => $request->biaya,
            'tanggal'   => $request->tanggal,
            'status'    => $request->status,
        ]);

        return redirect()->route('perbaikanalat.index')
                         ->with('success', 'Data perbaikan alat berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $perbaikan = PerbaikanAlat::findOrFail($id);
        $perbaikan->delete();

        return redirect()->route('perbaikanalat.index')
                         ->with('success', 'Data perbaikan alat berhasil dihapus.');
    }

}

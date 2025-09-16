<?php

namespace App\Http\Controllers;

use App\Models\BayarKangTagih;
use Illuminate\Http\Request;
use App\Exports\BayarKangTagihExport;
use Maatwebsite\Excel\Facades\Excel;

class BayarKangTagihController extends Controller
{
    public function index()
    {
        $bayars = BayarKangTagih::latest()->paginate(10);
        $totalBayar = BayarKangTagih::sum('jumlah');

        return view('bayar-kang-tagih.index', compact('bayars', 'totalBayar'));
    }

    public function create()
    {
        return view('bayar-kang-tagih.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        BayarKangTagih::create($request->all());

        return redirect()->route('bayar-kang-tagih.index')->with('success', 'Pembayaran berhasil ditambahkan');
    }

    public function edit(BayarKangTagih $bayarKangTagih)
    {
        return view('bayar-kang-tagih.edit', compact('bayarKangTagih'));
    }

    public function update(Request $request, BayarKangTagih $bayarKangTagih)
    {
        $request->validate([
            'nama_petugas' => 'required',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        $bayarKangTagih->update($request->all());

        return redirect()->route('bayar-kang-tagih.index')->with('success', 'Pembayaran berhasil diperbarui');
    }

    public function destroy(BayarKangTagih $bayarKangTagih)
    {
        $bayarKangTagih->delete();
        return redirect()->route('bayar-kang-tagih.index')->with('success', 'Pembayaran berhasil dihapus');
    }

    public function export()
{
    return Excel::download(new BayarKangTagihExport, 'data-bayar-kang-tagih.xlsx');
}
}

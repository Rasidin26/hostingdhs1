<?php

namespace App\Http\Controllers;

use App\Models\ListrikPdamPulsa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\ListrikPdamPulsaExport;
use Maatwebsite\Excel\Facades\Excel;

class ListrikPdamPulsaController extends Controller
{
    public function index()
    {
        $bayars = ListrikPdamPulsa::latest()->paginate(10);
        $totalBayar = ListrikPdamPulsa::sum('jumlah');

        return view('listrik_pdam_pulsa.index', compact('bayars', 'totalBayar'));
    }

    public function create()
    {
        return view('listrik_pdam_pulsa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        ListrikPdamPulsa::create($request->all());

        return redirect()->route('listrik-pdam-pulsa.index')
                         ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $bayar = ListrikPdamPulsa::findOrFail($id);
        return view('listrik_pdam_pulsa.edit', compact('bayar'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $bayar = ListrikPdamPulsa::findOrFail($id);
        $bayar->update($request->all());

        return redirect()->route('listrik-pdam-pulsa.index')
                         ->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        ListrikPdamPulsa::findOrFail($id)->delete();

        return redirect()->route('listrik-pdam-pulsa.index')
                         ->with('success', 'Data berhasil dihapus');
    }

    public function export()
{
    return Excel::download(new ListrikPdamPulsaExport, 'data-listrik-pdam-pulsa.xlsx');
}
}

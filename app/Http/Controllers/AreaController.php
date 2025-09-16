<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Tampilkan daftar area.
     */
    public function index()
    {
        $areas = Area::orderBy('id', 'asc')->get();
        return view('areas.index', compact('areas'));
    }

    /**
     * Form tambah area.
     */
    public function create()
    {
        return view('areas.create');
    }

    /**
     * Simpan area baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_area'     => 'required|string|max:255',
            'Deskripsi'  => 'nullable|string|max:255',
        ]);

        Area::create([
            'nama_area'     => $request->nama_area,
            'Deskripsi'  => $request->cakupan_area,
        ]);

        return redirect()->route('areas.index')->with('success', 'Area berhasil ditambahkan');
    }

    /**
     * Form edit area.
     */
    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    /**
     * Update data area.
     */
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'nama_area'     => 'required|string|max:255',
            'Deskripsi'  => 'nullable|string|max:255',
        ]);

        $area->update([
            'nama_area'     => $request->nama_area,
            'Deskripsi'  => $request->cakupan_area,
        ]);

        return redirect()->route('areas.index')->with('success', 'Area berhasil diperbarui');
    }

    /**
     * Hapus area.
     */
    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Area berhasil dihapus');
    }
}

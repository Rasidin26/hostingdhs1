<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Odc;
use App\Models\Odp;
use App\Models\Ont;
use App\Models\Area;
use App\Models\Package;

class LocationController extends Controller
{
public function index()
{
    $odc      = Odc::all();
    $odp      = Odp::all();
    $ont      = Ont::all();  // ambil semua ONT
    $areas    = Area::all();
    $packages = Package::all();

    return view('peta', compact('odc', 'odp', 'ont', 'areas', 'packages'));
}

public function peta()
{
    $odc      = Odc::with('odps')->get();
    $odp      = Odp::all();
    $ont      = Ont::all(); // jangan lupa
    $areas    = Area::all();
    $packages = Package::all();

    return view('peta', compact('odc', 'odp', 'ont', 'areas', 'packages'));
}




public function api()
{
    return response()->json([
        'odc' => Odc::all(),
        'odp' => Odp::all(),
        'ont' => Ont::all(),
    ]);
}


public function create()
{
    $areas = \App\Models\Area::all();
    $packages = \App\Models\Package::all();

    return view('lokasi_tambah', compact('areas', 'packages'));
}



public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:ODC,ODP,ONT',
            'kode' => 'required_if:tipe,ODC,ODP',
            'nama' => 'required',
            'lat'  => 'required|numeric',
            'lng'  => 'required|numeric',
        ]);

        if ($request->tipe === 'ODC') {
            Odc::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'lat'  => $request->lat,
                'lng'  => $request->lng,
                'info' => $request->info,
            ]);
        } elseif ($request->tipe === 'ODP') {
            Odp::create([
                'kode'   => $request->kode,
                'nama'   => $request->nama,
                'lat'    => $request->lat,
                'lng'    => $request->lng,
                'info'   => $request->info,
                'odc_id' => $request->odc_id,
            ]);
        } elseif ($request->tipe === 'ONT') {
    Ont::create([
        'nama' => $request->kode, // tambahkan ini
        'nama_client'  => $request->nama_client,
        'id_pelanggan' => $request->id_pelanggan,
        'status'       => $request->status,
        'lat'          => $request->lat,
        'lng'          => $request->lng,
        'info'         => $request->info,
        'odp_id'       => $request->odp_id,
        'area_id'      => $request->area_id,
        'package_id'   => $request->package_id,
        'device'       => $request->device,
    ]);
}

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

public function sinkron()
{
    $data = []; // ganti dengan query data pelangganmu

    if (count($data) === 0) {
        return redirect()->back()->with('warning', 'Tidak ada data pelanggan untuk disinkronkan.');
    }

    // kalau ada data → lanjut sinkronisasi
    return redirect()->back()->with('success', 'Data pelanggan berhasil disinkronkan.');
}
// app/Http/Controllers/LokasiController.php
public function getData()
{
    return response()->json([
        'odc' => Odc::all(),
        'odp' => Odp::all(),
        'ont' => Ont::all(),
    ]);
}


public function edit($tipe, $id)
{
    if ($tipe === 'ODC') {
        $data = Odc::findOrFail($id);
    } elseif ($tipe === 'ODP') {
        $data = Odp::findOrFail($id);
    } elseif ($tipe === 'ONT') {
        $data = Ont::findOrFail($id);
    } else {
        return response()->json(['error' => 'Tipe tidak valid'], 400);
    }

    return response()->json($data);
}


public function update(Request $request, $tipe, $id)
    {
        $request->validate([
            'nama' => 'required',
            'lat'  => 'required|numeric',
            'lng'  => 'required|numeric',
        ]);

        if ($tipe === 'ODC') {
            $data = Odc::findOrFail($id);
            $data->update([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'lat'  => $request->lat,
                'lng'  => $request->lng,
                'info' => $request->info,
            ]);
        } elseif ($tipe === 'ODP') {
            $data = Odp::findOrFail($id);
            $data->update([
                'kode'   => $request->kode,
                'nama'   => $request->nama,
                'lat'    => $request->lat,
                'lng'    => $request->lng,
                'info'   => $request->info,
                'odc_id' => $request->odc_id,
            ]);
        } elseif ($tipe === 'ONT') {
            $data = Ont::findOrFail($id);
            $data->update([
                'nama' => $request->kode, // nama lokasi sama dengan kode
                'nama_client'  => $request->nama_client,
                'id_pelanggan' => $request->id_pelanggan,
                'status'       => $request->status,
                'lat'          => $request->lat,
                'lng'          => $request->lng,
                'info'         => $request->info,
                'odp_id'       => $request->odp_id,
                'area_id'      => $request->area_id,
                'package_id'   => $request->package_id,
                'device'       => $request->device,
            ]);
        }

        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

public function storeOnt(Request $request)
{
    $request->validate([
        'nama'         => 'nullable|string',
        'lat'          => 'required|numeric',
        'lng'          => 'required|numeric',
        'odp_id'       => 'nullable|exists:odps,id',
        'nama_client'  => 'nullable|string',
        'id_pelanggan' => 'nullable|string',
        'status'       => 'required|in:Aktif,Nonaktif',
        'area_id'      => 'nullable|exists:areas,id',
        'package_id'   => 'nullable|exists:packages,id',
        'device'       => 'nullable|string',
        'deskripsi'    => 'nullable|string',
    ]);

    Ont::create([
        'nama' => $request->kode, // nama lokasi sama dengan kode
        'lat'          => $request->lat,
        'lng'          => $request->lng,
        'odp_id'       => $request->odp_id,
        'nama_client'  => $request->nama_client,
        'id_pelanggan' => $request->id_pelanggan,
        'status'       => $request->status,
        'area_id'      => $request->area_id,
        'package_id'   => $request->package_id, // sesuai field db
        'device'       => $request->device,
        'info'         => $request->deskripsi,
    ]);

    return redirect()->back()->with('success', 'Data ONT berhasil ditambahkan');
}



}
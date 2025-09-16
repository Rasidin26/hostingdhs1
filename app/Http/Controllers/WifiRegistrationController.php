<?php

namespace App\Http\Controllers;

use App\Models\WifiRegistration;
use Illuminate\Http\Request;

class WifiRegistrationController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search');
    $statusFilter = $request->input('status');

    $registrations = WifiRegistration::query()
        ->when($search, function($q) use ($search) {
            $q->where('nama', 'like', "%$search%")
              ->orWhere('telepon', 'like', "%$search%");
        })
        ->when($statusFilter, function($q) use ($statusFilter) {
            $q->where('status', $statusFilter);
        })
        ->latest()
        ->get();

    $total = WifiRegistration::count();
    $menunggu = WifiRegistration::where('status', 'menunggu')->count();
    $disetujui = WifiRegistration::where('status', 'disetujui')->count();

    return view('registrations.index', compact(
        'registrations', 'total', 'menunggu', 'disetujui', 'search', 'statusFilter'
    ));
}


    public function create()
    {
        return view('registrations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'telepon' => 'required',
            'email' => 'nullable|email',
            'paket' => 'required',
            'harga' => 'required|numeric',
        ]);

        WifiRegistration::create($request->all());

        return redirect()->route('registrations.index')->with('success', 'Pendaftaran berhasil dikirim.');
    }

    public function approve(WifiRegistration $registration)
    {
        $registration->update(['status' => 'disetujui']);
        return back()->with('success', 'Pendaftaran disetujui.');
    }

    public function reject(WifiRegistration $registration)
    {
        $registration->update(['status' => 'ditolak']);
        return back()->with('success', 'Pendaftaran ditolak.');
    }
}

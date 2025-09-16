<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotspotProfile;
use App\Models\Queue;
use App\Models\Device;
use RouterOS\Client;
use RouterOS\Query;

class HotspotProfileController extends Controller
{
    // Tampilkan semua profil hotspot dari database
    public function index()
    {
        $profiles = HotspotProfile::all();
        return view('hotspot.index', compact('profiles'));
    }

    // Sinkronisasi profil dari MikroTik ke database lokal
    public function syncFromMikrotik()
    {
        try {
            $device = Device::firstOrFail();

            $client = new Client([
                'host' => $device->ip_address,
                'user' => $device->username,
                'pass' => $device->password,
                'port' => $device->api_port ?? 7288,
            ]);

            $query = new Query('/ip/hotspot/user/profile/print');
            $profiles = $client->query($query)->read();

            // Update atau buat data di database berdasarkan data dari MikroTik
            foreach ($profiles as $p) {
                HotspotProfile::updateOrCreate(
                    ['nama_profil' => $p['name']],
                    [
                        'batas_kecepatan' => $p['rate-limit'] ?? null,
                        'masa_berlaku'    => $p['on-expire'] ?? null,
                        'parent_queue'    => $p['parent-queue'] ?? null,
                        'shared_users'    => $p['shared-users'] ?? 1,
                        'harga_modal'     => 0,
                        'harga_jual'      => 0,
                        'status'          => 'aktif',
                    ]
                );
            }

            return redirect()->route('hotspot.index')->with('success', '✅ Data berhasil disinkronkan dari MikroTik.');
        } catch (\Throwable $e) {
            return back()->with('error', '❌ Gagal koneksi ke MikroTik: ' . $e->getMessage());
        }
    }

    // Form untuk tambah profil manual
    public function create()
    {
        $queues = Queue::all();
        return view('hotspot.create', compact('queues'));
    }

    // Simpan profil manual dan tambahkan ke MikroTik
   public function store(Request $request)
{
    $request->validate([
        'nama_profil'     => 'required|string',
        'session_timeout' => 'nullable|string',
        'idle_timeout'    => 'nullable|string',
        'rate_limit'      => 'nullable|string',
        'shared_users'    => 'required|integer',
        'harga_modal'     => 'required|numeric',
        'harga_jual'      => 'required|numeric',
        'status'          => 'required|in:aktif,nonaktif',
    ]);

    $device = Device::firstOrFail();

    try {
        $client = new Client([
            'host' => trim($device->ip_address),
            'user' => $device->username,
            'pass' => $device->password,
            'port' => $device->api_port ?? 7288,
        ]);

        // 🔹 Tambah ke MikroTik
        $query = new Query('/ip/hotspot/user/profile/add');
        $query->equal('name', $request->nama_profil);

        if ($request->session_timeout) {
            $query->equal('session-timeout', $request->session_timeout);
        }
    if ($request->idle_timeout && strtolower($request->idle_timeout) !== 'none') {
    $query->equal('idle-timeout', $request->idle_timeout);
}

        if ($request->shared_users) {
            $query->equal('shared-users', $request->shared_users);
        }
        if ($request->rate_limit) {
            $query->equal('rate-limit', $request->rate_limit);
        }

        $result = $client->query($query)->read();

        if (!empty($result)) {
            // Simpan juga ke database
            HotspotProfile::create([
                'nama_profil'     => $request->nama_profil,
                'session_timeout' => $request->session_timeout,
                'idle_timeout'    => $request->idle_timeout,
                'rate_limit'      => $request->rate_limit,
                'shared_users'    => $request->shared_users,
                'harga_modal'     => $request->harga_modal,
                'harga_jual'      => $request->harga_jual,
                'status'          => $request->status,
            ]);

            return redirect()->route('hotspot.index')
                ->with('success', '✅ Profil berhasil ditambahkan ke MikroTik dan database.');
        } else {
            return back()->with('error', '⚠️ Gagal menambahkan profil ke MikroTik.');
        }

    } catch (\Throwable $e) {
        return back()->with('error', '❌ Gagal koneksi ke MikroTik: ' . $e->getMessage());
    }
}


    // Form edit profil
    public function edit($id)
    {
        $profile = HotspotProfile::findOrFail($id);
        $queues = Queue::all();
        return view('hotspot.edit', compact('profile', 'queues'));
    }

    // Update profil di database (belum update ke MikroTik, bisa kamu tambah nanti)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_profil'     => 'required|string',
            'batas_kecepatan' => 'nullable|string',
            'masa_berlaku'    => 'nullable|string',
            'parent_queue'    => 'nullable|string',
            'shared_users'    => 'required|integer',
            'harga_modal'     => 'required|numeric',
            'harga_jual'      => 'required|numeric',
            'status'          => 'required|in:aktif,nonaktif',
        ]);

        $profile = HotspotProfile::findOrFail($id);
        $profile->update($request->all());

        return redirect()->route('hotspot.index')->with('success', '✅ Profil berhasil diperbarui');
    }

    // Hapus profil dari database (belum hapus di MikroTik, bisa kamu tambah nanti)
    public function destroy($id)
    {
        $profile = HotspotProfile::findOrFail($id);
        $profile->delete();

        return redirect()->route('hotspot.index')->with('success', '🗑 Profil berhasil dihapus');
    }

    // Jika ada route show tapi tidak digunakan, redirect ke index
    public function show($id)
    {
        return redirect()->route('hotspot.index');
    }
}

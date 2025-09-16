<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RouterOS\Client;
use RouterOS\Query;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::where('user_id', Auth::id())->get();
        return view('devices.index', compact('devices'));
    }

    public function testConnection(Device $device)
{
    try {
        $client = new Client([
            'host' => $device->ip_address,
            'user' => $device->username,
            'pass' => $device->password,
            'port' => $device->api_port ?? 7288, // ✅ samain ke 7288
            'timeout' => 20,
            'attempts' => 1
        ]);

        $query = new Query('/ip/hotspot/user/print');
        $users = $client->query($query)->read();

        if (empty($users)) {
            return back()->with('error', '⚠️ Koneksi berhasil, tapi tidak ada user hotspot.');
        }

        return view('mikrotik.index', [
            'users' => $users,
            'device' => $device
        ]);

    } catch (\Throwable $e) {
        return back()->with('error', '❌ Gagal koneksi ke Mikrotik: ' . $e->getMessage());
    }
}


    

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'dns_name'   => 'nullable',
            'ip_address' => 'required|ip',
            'api_port'   => 'nullable|numeric',
            'username'   => 'required',
            'password'   => 'required',
        ]);

        Device::create([
            'name'       => $request->name,
            'dns_name'   => $request->dns_name,
            'ip_address' => $request->ip_address,
            'api_port'   => $request->api_port ?? 7288,
            'username'   => $request->username,
            'password'   => $request->password,
            'user_id'    => Auth::id(),
        ]);

        return redirect()->route('dashboard.index')->with('success', '✅ Perangkat berhasil ditambahkan');
    }

public function mikrotikLogin(Device $device)
{
    try {
       $client = new \RouterOS\Client([
    'host' => $device->ip_address,
    'user' => $device->username,
    'pass' => $device->password,
    'port' => $device->api_port ?? 7288,
    'timeout' => 20, // naikkan dari 5 ke 20
    'attempts' => 2   // coba 2 kali jika gagal
]);


        // Jika konek berhasil, simpan session
        session([
            'mikrotik_connected' => true,
            'mikrotik_device_id' => $device->id
        ]);

        return redirect()->route('dashboard')
                         ->with('success', "✅ Berhasil konek ke Mikrotik: {$device->name}");

    } catch (\Exception $e) {
        return redirect()->back()
                         ->with('error', "❌ Gagal konek ke Mikrotik ({$device->ip_address}:7288): " . $e->getMessage());
    }
}


public function mikrotikDisconnect()
{
    session()->forget(['mikrotik_connected', 'mikrotik_device_id']);
    return redirect()->route('dashboard.index')->with('success', 'Berhasil logout dari Mikrotik.');
}
public function create()
{
    return view('devices.create');
}

 
}

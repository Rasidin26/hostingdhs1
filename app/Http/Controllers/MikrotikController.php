<?php

namespace App\Http\Controllers;

use RouterOS\Client;
use RouterOS\Query;

class MikrotikController extends Controller
{
    public function index()
    {
        // Buat koneksi dan login otomatis
        $client = new Client([
            'host' => '103.191.165.36',
            'user' => 'dhs',
            'pass' => 'alisa',
            'port' => 7288,
        ]);

        // Query untuk ambil daftar user hotspot
        $query = new Query('/ip/hotspot/user/print');
        $users = $client->query($query)->read();

        // Kirim data ke view
        return view('mikrotik.index', compact('users'));
    }
}

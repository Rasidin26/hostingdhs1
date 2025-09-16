<?php

require_once __DIR__ . '/vendor/autoload.php';

use \RouterOS\Client;
use \RouterOS\Query;

// --- Ganti dengan informasi Anda ---
$mikrotik_ip     = '103.191.165.36';
$mikrotik_port   = 7288; // Port kustom Anda
$mikrotik_user   = 'dhs';
$mikrotik_password = 'alisa';

try {
    echo "Mencoba menyambung ke {$mikrotik_ip}:{$mikrotik_port}...\n";

    // Inisialisasi koneksi client
    $client = new Client([
        'host' => $mikrotik_ip,
        'user' => $mikrotik_user,
        'pass' => $mikrotik_password,
        'port' => $mikrotik_port,
    ]);

    echo "Koneksi berhasil!\n\n";

    // Membuat query untuk mendapatkan daftar interface
    $query = new Query('/interface/print');

    // Mengirim query dan membaca hasilnya
    $interfaces = $client->query($query)->read();

    echo "Daftar Interface:\n";
    foreach ($interfaces as $interface) {
        echo "- Name: " . ($interface['name'] ?? 'N/A') . ", Type: " . ($interface['type'] ?? 'N/A') . "\n";
    }

} catch (\Exception $e) {
    die("Gagal terhubung atau terjadi error: " . $e->getMessage() . "\n");
}
<?php
require('routeros_api.class.php');

$API = new RouterosAPI();

$ip = '103.191.165.36'; // ganti IP MikroTik kamu
$user = 'dhs';          // username MikroTik
$pass = 'alisa'; // password MikroTik
$port = 7288;           // port API yang kamu pakai

if ($API->connect($ip, $user, $pass, $port)) {
    echo "✅ Berhasil konek ke MikroTik API!";
    $API->disconnect();
} else {
    echo "❌ Gagal konek ke MikroTik API!";
}

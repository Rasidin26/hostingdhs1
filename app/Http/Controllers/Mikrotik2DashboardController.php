<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RouterOS\Client;
use RouterOS\Query;
use App\Models\Device;

class Mikrotik2DashboardController extends Controller
{
    public function index()
    {
        $error      = null;
        $device     = null;     // Data dari database
        $resource   = null;     // Data dari Mikrotik
        $stats      = [];
        $interfaces = [];
        $hotspot    = [];
        $pppoe      = [];

        try {
            // === Ambil Device dari Database ===
            $device = Device::first(); // ambil device pertama
            if (!$device) {
                throw new \Exception("Device belum ditambahkan ke database.");
            }

            // === Koneksi ke Mikrotik ===
            $client = new Client([
                'host' => $device->ip_address,
                'user' => $device->username,
                'pass' => $device->password,
                'port' => $device->port ?? 7288,
                'timeout' => 5,
                'attempts' => 1,
            ]);

            // === Device Info dari Mikrotik ===
            $resource = $client->query(new Query('/system/resource/print'))->read()[0];

            // === Statistik ===
            $stats = [
                'cpu'     => $resource['cpu-load'] ?? 0,
                'memory'  => isset($resource['free-memory'], $resource['total-memory'])
                                ? round((1 - ($resource['free-memory'] / $resource['total-memory'])) * 100, 2)
                                : 0,
                'hotspot' => count($client->query(new Query('/ip/hotspot/active/print'))->read()),
                'ppp'     => count($client->query(new Query('/ppp/active/print'))->read()),
                'traffic' => count($client->query(new Query('/interface/print'))->read()),
            ];

            // === Interface List ===
            $interfaces = $client->query(new Query('/interface/print'))->read();

            // === Services Overview: Hotspot ===
            $hotspotProfiles = $client->query(new Query('/ip/hotspot/user-profile/print'))->read();
            $hotspotBindings = $client->query(new Query('/ip/hotspot/ip-binding/print'))->read();
            $hotspotUsers    = $client->query(new Query('/ip/hotspot/user/print'))->read();

            $hotspot = [
                'profiles'    => count($hotspotProfiles),
                'total_users' => count($hotspotUsers),
                'active'      => $stats['hotspot'],
                'bindings'    => count($hotspotBindings),
            ];

            // === Services Overview: PPPoE ===
            $pppoeProfiles = $client->query(new Query('/ppp/profile/print'))->read();
            $pppoeSecrets  = $client->query(new Query('/ppp/secret/print'))->read();
            $pppoeServers  = $client->query(new Query('/interface/pppoe-server/print'))->read();

            $pppoe = [
                'servers' => count($pppoeServers),
                'profiles'=> count($pppoeProfiles),
                'secrets' => count($pppoeSecrets),
                'active'  => $stats['ppp'],
            ];

        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return view('dashboard', compact(
            'device', 'resource', 'stats', 'interfaces', 'error', 'hotspot', 'pppoe'
        ));
    }

    public function ajaxStats()
    {
        try {
            $device = Device::first();
            if (!$device) {
                throw new \Exception("Device belum ditambahkan ke database.");
            }

            $client = new Client([
                'host' => $device->ip_address,
                'user' => $device->username,
                'pass' => $device->password,
                'port' => $device->port ?? 7288,
                'timeout' => 5,
                'attempts' => 1
            ]);

            $resource = $client->query(new Query('/system/resource/print'))->read()[0];

            $stats = [
                'cpu'     => $resource['cpu-load'] ?? 0,
                'memory'  => isset($resource['free-memory'], $resource['total-memory'])
                                ? round((1 - ($resource['free-memory'] / $resource['total-memory'])) * 100, 2)
                                : 0,
                'hotspot' => count($client->query(new Query('/ip/hotspot/active/print'))->read()),
                'ppp'     => count($client->query(new Query('/ppp/active/print'))->read()),
                'traffic' => count($client->query(new Query('/interface/print'))->read()),
                'updated_at' => now()->format('d/m/Y, H:i:s'),
            ];

            // interface
            $interfaces = $client->query(new Query('/interface/print'))->read();
            $interfaces = array_map(fn($iface) => [
                'name' => $iface['name'] ?? '(no-name)'
            ], $interfaces);

            // hotspot
            $hotspotProfiles = $client->query(new Query('/ip/hotspot/user-profile/print'))->read();
            $hotspotBindings = $client->query(new Query('/ip/hotspot/ip-binding/print'))->read();
            $hotspotUsers    = $client->query(new Query('/ip/hotspot/user/print'))->read();

            $hotspot = [
                'profiles'    => count($hotspotProfiles),
                'total_users' => count($hotspotUsers),
                'active'      => $stats['hotspot'],
                'bindings'    => count($hotspotBindings),
            ];

            // pppoe
            $pppoeProfiles = $client->query(new Query('/ppp/profile/print'))->read();
            $pppoeSecrets  = $client->query(new Query('/ppp/secret/print'))->read();
            $pppoeServers  = $client->query(new Query('/interface/pppoe-server/print'))->read();

            $pppoe = [
                'servers' => count($pppoeServers),
                'profiles'=> count($pppoeProfiles),
                'secrets' => count($pppoeSecrets),
                'active'  => $stats['ppp'],
            ];

            return response()->json([
                'stats'      => $stats,
                'interfaces' => $interfaces,
                'hotspot'    => $hotspot,
                'pppoe'      => $pppoe,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Router;
use Illuminate\Database\Seeder;

class RouterSeeder extends Seeder
{
    public function run(): void
    {
        Router::create([
            'nama_router' => 'SALFA Kantor',
            'ip_address' => '192.168.88.1',
            'port' => '8728',
            'status' => 'online',
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterfaceStatsTable extends Migration
{
    public function up(): void
    {
        Schema::create('interface_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interface_id')->constrained('interfaces')->onDelete('cascade');
            $table->float('traffic_rx'); // data masuk (Rx) dalam Mbps/Kbps
            $table->float('traffic_tx'); // data keluar (Tx)
            $table->float('total'); // total trafik
            $table->timestamp('created_at')->useCurrent(); // waktu statistik dicatat
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interface_stats');
    }
}
;
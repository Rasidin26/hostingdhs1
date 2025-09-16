<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouterStatsTable extends Migration
{
    public function up(): void
    {
        Schema::create('router_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->constrained('routers')->onDelete('cascade');
            $table->float('cpu_load'); // persentase CPU
            $table->float('memory_used'); // dalam MB atau persentase
            $table->float('traffic_rx'); // total RX traffic
            $table->float('traffic_tx'); // total TX traffic
            $table->float('total_traffic'); // RX + TX
            $table->integer('active_users')->default(0);
            $table->string('jenis_user')->nullable(); // hotspot, pppoe, dll
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('router_stats');
    }
}
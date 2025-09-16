<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterfacesTable extends Migration
{
    public function up(): void
    {
        Schema::create('interfaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->constrained('routers')->onDelete('cascade');
            $table->string('nama_interface'); // misalnya ether1, wlan1
            $table->text('keterangan')->nullable(); // optional: deskripsi interface
            $table->timestamp('created_at')->useCurrent(); // waktu ditambahkan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interfaces');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('wifi_registrations', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('telepon');
    $table->string('email')->nullable();
    $table->string('paket');
    $table->decimal('harga', 10, 2);
    $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wifi_registrations');
    }
};

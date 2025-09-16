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
// database/migrations/xxxx_xx_xx_create_packages_table.php
Schema::create('packages', function (Blueprint $table) {
    $table->id();
    $table->string('nama_paket');
    $table->decimal('harga', 10, 2);
    $table->string('kecepatan')->nullable(); // contoh: 10Mbps
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};

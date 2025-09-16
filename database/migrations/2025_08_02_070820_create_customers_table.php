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
        // database/migrations/xxxx_xx_xx_create_customers_table.php
Schema::create('customers', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('id_pelanggan')->unique();
    $table->string('nomor_telepon')->nullable();
    $table->enum('koneksi', ['Fiber', 'Wireless'])->default('Fiber');
    $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
    $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
    $table->decimal('biaya', 10, 2)->nullable();
    $table->enum('status_pembayaran', ['Lunas', 'Belum Bayar', 'Bayar Parsial'])->default('Belum Bayar');
    $table->enum('status', ['Aktif', 'Isolir', 'Nonaktif'])->default('Aktif');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

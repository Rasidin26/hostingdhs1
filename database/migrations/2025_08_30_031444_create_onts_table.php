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
        Schema::create('onts', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();                 // Nama/ID ONT
            $table->decimal('lat', 10, 6);                      // Latitude
            $table->decimal('lng', 10, 6);                      // Longitude
            $table->unsignedBigInteger('odp_id')->nullable();   // Relasi ke ODP
            $table->string('nama_client')->nullable();          // Nama Client
            $table->string('id_pelanggan')->nullable();         // ID Pelanggan
            $table->enum('status', ['Aktif','Nonaktif'])->default('Aktif'); // Status Pelanggan
            $table->unsignedBigInteger('area_id')->nullable();  // Relasi ke Area
            $table->unsignedBigInteger('paket_id')->nullable(); // Relasi ke Paket
            $table->string('device')->nullable();               // Device
            $table->text('deskripsi')->nullable();              // Deskripsi
            $table->timestamps();

            // Foreign Key (opsional, kalau tabel relasi sudah ada)
            $table->foreign('odp_id')->references('id')->on('odp')->onDelete('set null');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
            $table->foreign('paket_id')->references('id')->on('pakets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onts');
    }
};

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
Schema::create('new_customers', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('telepon')->nullable();
    $table->string('id_pelanggan')->unique();
    $table->string('pppoe'); // IP / username
    $table->string('paket');
    $table->string('area');
    $table->date('tgl_register');
    $table->enum('status', ['Aktif', 'Menunggu', 'Terpasang'])->default('Aktif');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_customers');
    }
};

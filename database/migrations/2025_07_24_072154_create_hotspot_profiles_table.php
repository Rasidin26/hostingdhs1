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
        Schema::create('hotspot_profiles', function (Blueprint $table) {
    $table->id();
    $table->string('nama_profil');
    $table->string('batas_kecepatan');
    $table->string('masa_berlaku');
    $table->string('parent_queue');
    $table->integer('shared_users');
    $table->integer('harga_modal');
    $table->integer('harga_jual');
    $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotspot_profiles');
    }
};

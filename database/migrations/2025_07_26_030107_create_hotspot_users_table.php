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
       Schema::create('hotspot_users', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sales_id')->constrained('sales');
    $table->foreignId('profile_id')->constrained('hotspot_profiles');
    $table->string('username')->unique();
    $table->string('password');
    $table->enum('tipe_pengguna', ['voucher', 'user']);
    $table->string('batas_waktu')->nullable();
    $table->string('batas_kuota')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotspot_users');
    }
};

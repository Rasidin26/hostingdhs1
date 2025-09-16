<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('profile_id')->constrained('hotspot_profiles')->onDelete('cascade');
            $table->integer('jumlah');
            $table->enum('tipe_pengguna', ['user', 'admin']);
            $table->string('awalan_username')->nullable();
            $table->enum('tipe_karakter', ['huruf', 'angka', 'campuran']);
            $table->string('batas_waktu')->nullable();
            $table->string('batas_kuota')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};

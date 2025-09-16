<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('bayar_kang_tagihs', function (Blueprint $table) {
        $table->id();
        $table->string('nama_petugas');     // nama kang tagih
        $table->date('tanggal');           // tanggal pembayaran
        $table->decimal('jumlah', 15, 2);  // jumlah dibayar
        $table->string('keterangan')->nullable(); // opsional
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bayar_kang_tagihs');
    }
};

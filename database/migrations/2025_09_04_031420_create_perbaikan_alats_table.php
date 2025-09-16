<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perbaikan_alats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alat');
            $table->string('kerusakan');
            $table->decimal('biaya', 15, 2);
            $table->date('tanggal');
            $table->string('status')->default('Proses'); // Proses / Selesai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perbaikan_alats');
    }
};

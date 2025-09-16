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
    Schema::create('listrik_pdam_pulsa', function (Blueprint $table) {
        $table->id();
        $table->string('jenis'); // Listrik / PDAM / Pulsa
        $table->date('tanggal');
        $table->decimal('jumlah', 15, 2);
        $table->string('keterangan')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listrik_pdam_pulsa');
    }
};

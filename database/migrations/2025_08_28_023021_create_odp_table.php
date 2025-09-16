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
        Schema::create('odp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('odc_id')->constrained('odc')->onDelete('cascade'); 
            $table->string('kode')->unique(); // contoh: ODP-1
            $table->string('nama');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->text('info')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odp');
    }
};

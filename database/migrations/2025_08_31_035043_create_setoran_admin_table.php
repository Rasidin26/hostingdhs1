<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setoran_admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id'); // admin yang setor
            $table->decimal('amount', 15, 2); // nominal setor
            $table->string('keterangan')->nullable(); // misal: setor ke pusat
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setoran_admin');
    }
};

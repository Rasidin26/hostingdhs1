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
    Schema::create('template_vouchers', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // nama template
        $table->string('filename'); // nama file di storage
        $table->text('description')->nullable(); // opsional, deskripsi
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_vouchers');
    }
};

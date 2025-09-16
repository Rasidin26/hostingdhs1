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
    Schema::create('profiles', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nama profil (misalnya: "1 Jam 1GB")
        $table->string('speed_limit')->nullable(); // Contoh: "1M/1M"
        $table->string('time_limit')->nullable();  // Contoh: "7h", "1d"
        $table->string('byte_limit')->nullable();  // Contoh: "500M", "1G"
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

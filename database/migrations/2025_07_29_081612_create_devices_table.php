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
    Schema::create('devices', function (Blueprint $table) {
        $table->id();
        $table->string('name');           // Nama perangkat (DHS)
        $table->string('dns_name');       // DNS (dipanet.com)
        $table->string('ip_address');     // 103.x.x.x
        $table->integer('api_port')->default(8728);
        $table->string('username');
        $table->string('password')->nullable();
        $table->unsignedBigInteger('user_id'); // pemilik perangkat
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};

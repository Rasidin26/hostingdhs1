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
        Schema::create('admin_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('admin_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['in','out','setor'])->default('in');
    $table->string('category')->nullable();
    $table->bigInteger('amount');
    $table->string('source')->nullable();
    $table->text('description')->nullable();
    $table->dateTime('trx_date');
    $table->timestamps();

    $table->index('trx_date');
    $table->index('admin_id');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_transactions');
    }
};

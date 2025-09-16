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
    Schema::create('voucher_templates', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nama template (misal: Template 1, Template 2)
        $table->string('view_path'); // Path view (misal: voucher_templates.template1)
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_templates');
    }
};

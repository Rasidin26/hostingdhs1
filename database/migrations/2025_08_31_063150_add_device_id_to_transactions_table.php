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
    Schema::table('transactions', function (Blueprint $table) {
        $table->unsignedBigInteger('device_id')->nullable()->after('id'); 
        $table->foreign('device_id')->references('id')->on('devices')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropForeign(['device_id']);
        $table->dropColumn('device_id');
    });
}

};

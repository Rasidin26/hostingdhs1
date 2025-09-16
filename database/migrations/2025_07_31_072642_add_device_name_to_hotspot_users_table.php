<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_add_device_name_to_hotspot_users_table.php
public function up()
{
    Schema::table('hotspot_users', function (Blueprint $table) {
        $table->string('device_name')->nullable()->after('username');
    });
}

public function down()
{
    Schema::table('hotspot_users', function (Blueprint $table) {
        $table->dropColumn('device_name');
    });
}

};

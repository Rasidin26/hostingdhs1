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
    Schema::table('hotspot_users', function (Blueprint $table) {
        $table->string('device_name')->nullable();
        $table->string('ip_address')->nullable();
        $table->enum('status', ['online', 'offline'])->default('offline');
        $table->timestamp('login_time')->nullable();
        $table->timestamp('logout_time')->nullable();
    });
}

public function down()
{
    Schema::table('hotspot_users', function (Blueprint $table) {
        $table->dropColumn(['device_name', 'ip_address', 'status', 'login_time', 'logout_time']);
    });
}
};

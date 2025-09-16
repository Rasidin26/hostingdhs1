<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiredAtToHotspotUsersTable extends Migration
{
    public function up()
    {
        Schema::table('hotspot_users', function (Blueprint $table) {
            $table->timestamp('expired_at')->nullable()->after('batas_kuota');
        });
    }

    public function down()
    {
        Schema::table('hotspot_users', function (Blueprint $table) {
            $table->dropColumn('expired_at');
        });
    }
}

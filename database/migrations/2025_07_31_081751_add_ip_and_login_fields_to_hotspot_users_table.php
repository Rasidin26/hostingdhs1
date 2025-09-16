<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('hotspot_users', function (Blueprint $table) {
            if (!Schema::hasColumn('hotspot_users', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('device_name');
            }
            if (!Schema::hasColumn('hotspot_users', 'login_time')) {
                $table->timestamp('login_time')->nullable()->after('status');
            }
            if (!Schema::hasColumn('hotspot_users', 'logout_time')) {
                $table->timestamp('logout_time')->nullable()->after('login_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hotspot_users', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'login_time', 'logout_time']);
        });
    }
};

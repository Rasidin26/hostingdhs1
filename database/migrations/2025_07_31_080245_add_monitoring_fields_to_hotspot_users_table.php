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
    Schema::table('hotspot_users', function (Blueprint $table) {
        $table->string('device_name')->nullable()->after('tipe_pengguna');
        $table->string('ip_address')->nullable()->after('device_name');
        $table->enum('status', ['online', 'offline'])->default('offline')->after('ip_address');
        $table->timestamp('login_time')->nullable()->after('status');
        $table->timestamp('logout_time')->nullable()->after('login_time');
        $table->bigInteger('upload')->default(0)->after('logout_time');   // dalam KB atau Byte
        $table->bigInteger('download')->default(0)->after('upload');
    });
}

public function down(): void
{
    Schema::table('hotspot_users', function (Blueprint $table) {
        $table->dropColumn([
            'device_name',
            'ip_address',
            'status',
            'login_time',
            'logout_time',
            'upload',
            'download'
        ]);
    });
}
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('vouchers', 'code')) {
                $table->string('code')->after('id');
            }
            if (!Schema::hasColumn('vouchers', 'price')) {
                $table->decimal('price', 15, 2)->after('code');
            }
            if (!Schema::hasColumn('vouchers', 'status')) {
                $table->enum('status', ['unused', 'used'])->default('unused')->after('price');
            }
            if (!Schema::hasColumn('vouchers', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('status');
            }
            // Foreign key sengaja tidak ditambahkan dulu
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['code', 'price', 'status', 'user_id']);
        });
    }
};

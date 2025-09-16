<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('sales', 'type')) {
                $table->enum('type', ['topup', 'billing', 'voucher'])->after('user_id');
            }
            if (!Schema::hasColumn('sales', 'price')) {
                $table->decimal('price', 15, 2)->after('type');
            }
            if (!Schema::hasColumn('sales', 'status')) {
                $table->enum('status', ['paid', 'unpaid', 'overdue'])
                      ->default('unpaid')->after('price');
            }

            // ❌ Jangan tambahkan foreign key dulu untuk menghindari error
            // Nanti bisa ditambahkan manual setelah semua user_id valid
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'type', 'price', 'status']);
        });
    }
};

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
    Schema::table('areas', function (Blueprint $table) {
        $table->string('cakupan_area')->nullable()->after('nama_area');
    });
}

public function down()
{
    Schema::table('areas', function (Blueprint $table) {
        $table->dropColumn('cakupan_area');
    });
}
};

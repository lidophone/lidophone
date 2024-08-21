<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('metro_stations', function (Blueprint $table) {
            $table->boolean('under_construction')->default(0)->after('metro_line_id');
        });
    }

    public function down(): void
    {
        Schema::table('metro_stations', function (Blueprint $table) {
            $table->dropColumn('under_construction');
        });
    }
};

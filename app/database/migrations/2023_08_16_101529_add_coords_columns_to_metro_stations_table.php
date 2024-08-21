<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('metro_stations', function (Blueprint $table) {
            $table->double('latitude')->nullable()->after('under_construction');
            $table->double('longitude')->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('metro_stations', function (Blueprint $table) {
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
        });
    }
};

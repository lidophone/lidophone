<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('housing_estates', function (Blueprint $table) {
            $table->boolean('is_region')->default(0)->after('city_id');
        });
    }

    public function down(): void
    {
        Schema::table('housing_estates', function (Blueprint $table) {
            $table->dropColumn('is_region');
        });
    }
};

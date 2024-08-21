<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('housing_estates_infrastructure', function (Blueprint $table) {
            $table->integer('time_on_foot')->nullable()->change();
        });
        Schema::table('housing_estates_metro_stations', function (Blueprint $table) {
            $table->integer('time_on_foot')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('housing_estates_infrastructure', function (Blueprint $table) {
            $table->integer('time_on_foot')->nullable(false)->change();
        });
        Schema::table('housing_estates_metro_stations', function (Blueprint $table) {
            $table->integer('time_on_foot')->nullable(false)->change();
        });
    }
};

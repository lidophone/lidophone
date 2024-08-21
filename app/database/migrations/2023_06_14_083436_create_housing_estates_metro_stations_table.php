<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('housing_estates_metro_stations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('housing_estate_id');
            $table->unsignedBigInteger('metro_station_id');
            $table->integer('time_on_foot')->comment('In minutes');
            $table->integer('time_by_car')->comment('In minutes');
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('housing_estates_metro_stations');
    }
};

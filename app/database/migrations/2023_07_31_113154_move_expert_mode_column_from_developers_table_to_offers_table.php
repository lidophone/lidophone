<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->dropColumn('expert_mode');
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->boolean('expert_mode')->default(0)->after('scenario_id');
        });
    }

    public function down(): void
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->boolean('expert_mode')->default(0)->after('name');
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('expert_mode');
        });
    }
};

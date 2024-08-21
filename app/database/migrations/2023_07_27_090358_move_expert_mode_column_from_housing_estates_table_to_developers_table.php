<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('housing_estates', function (Blueprint $table) {
            $table->dropColumn('expert_mode');
        });
        Schema::table('developers', function (Blueprint $table) {
            $table->boolean('expert_mode')->default(0)->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('housing_estates', function (Blueprint $table) {
            $table->boolean('expert_mode')->default(0)->after('payment');
        });
        Schema::table('developers', function (Blueprint $table) {
            $table->dropColumn('expert_mode');
        });
    }
};

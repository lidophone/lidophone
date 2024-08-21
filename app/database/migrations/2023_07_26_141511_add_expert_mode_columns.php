<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('expert_mode')->default(0)->after('remember_token');
        });
        Schema::table('housing_estates', function (Blueprint $table) {
            $table->boolean('expert_mode')->default(0)->after('payment');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('expert_mode');
        });
        Schema::table('housing_estates', function (Blueprint $table) {
            $table->dropColumn('expert_mode');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->boolean('other_developers')->default(0)->after('expert_mode');
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->boolean('other_developers')->default(0)->after('expert_mode');
        });
    }

    public function down(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->dropColumn('other_developers');
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('other_developers');
        });
    }
};

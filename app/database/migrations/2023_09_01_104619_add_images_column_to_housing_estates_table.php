<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('housing_estates', function (Blueprint $table) {
            $table->json('images')->nullable()->after('automatic_handling');
        });
    }

    public function down(): void
    {
        Schema::table('housing_estates', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};

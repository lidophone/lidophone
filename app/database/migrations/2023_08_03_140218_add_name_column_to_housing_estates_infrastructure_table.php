<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('housing_estates_infrastructure', function (Blueprint $table) {
            $table->string('name')->after('infrastructure_id');
        });
    }

    public function down(): void
    {
        Schema::table('housing_estates_infrastructure', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};

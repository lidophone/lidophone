<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->nullable()->after('contact_phone_number');
            $table->unsignedBigInteger('housing_estate_id')->nullable()->after('status');
            $table->boolean('is_developer')->nullable()->after('housing_estate_id');
            $table->unsignedInteger('price')->nullable()->after('is_developer');
        });
    }

    public function down(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->dropColumn(['price', 'is_developer', 'housing_estate_id', 'status']);
        });
    }
};

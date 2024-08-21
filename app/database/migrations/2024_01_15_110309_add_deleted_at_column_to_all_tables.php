<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('developers', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('developers_payment_methods', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('housing_estates', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('housing_estates_infrastructure', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('housing_estates_metro_stations', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('housing_estates_payment_methods', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('housing_estates_promotions', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('housing_estates_tags', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('infrastructure', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('metro_lines', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('metro_stations', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('objects', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('promotions', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('scenarios', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('tags', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('working_hours', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('developers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('developers_payment_methods', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('housing_estates', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('housing_estates_infrastructure', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('housing_estates_metro_stations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('housing_estates_payment_methods', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('housing_estates_promotions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('housing_estates_tags', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('infrastructure', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('metro_lines', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('metro_stations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('objects', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('scenarios', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('tags', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('working_hours', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};

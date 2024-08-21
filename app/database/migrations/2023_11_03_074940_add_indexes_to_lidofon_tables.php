<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->index('expert_mode');
        });
        Schema::table('objects', function (Blueprint $table) {
            $table->index('roominess');
            $table->index('real_estate_type');
            $table->index('finishing');
            $table->index('price');
            $table->index('done');
            $table->index('deadline_year');
            $table->index('deadline_quarter');
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->index('price');
            $table->index('operator_award');
            $table->index('client_is_out_of_town');
            $table->index('looking_not_for_himself');
            $table->index('expert_mode');
            $table->index('active');
        });
        Schema::table('working_hours', function (Blueprint $table) {
            $table->index('day_of_week');
            $table->index('start_time');
            $table->index('end_time');
        });
    }

    public function down(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->dropIndex(['expert_mode']);
        });
        Schema::table('objects', function (Blueprint $table) {
            $table->dropIndex(['roominess']);
            $table->dropIndex(['real_estate_type']);
            $table->dropIndex(['finishing']);
            $table->dropIndex(['price']);
            $table->dropIndex(['done']);
            $table->dropIndex(['deadline_year']);
            $table->dropIndex(['deadline_quarter']);
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->dropIndex(['price']);
            $table->dropIndex(['operator_award']);
            $table->dropIndex(['client_is_out_of_town']);
            $table->dropIndex(['looking_not_for_himself']);
            $table->dropIndex(['expert_mode']);
            $table->dropIndex(['active']);
        });
        Schema::table('working_hours', function (Blueprint $table) {
            $table->dropIndex(['day_of_week']);
            $table->dropIndex(['start_time']);
            $table->dropIndex(['end_time']);
        });
    }
};

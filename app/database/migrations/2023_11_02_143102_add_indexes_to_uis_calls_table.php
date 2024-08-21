<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->index('start_time');
            $table->index('finish_time');
            $table->index('first_answered_employee_id');
            $table->index('last_talked_employee_id');
            $table->index('contact_phone_number');
            $table->index('status');
            $table->index('marketplace_id');
            $table->index('housing_estate_id');
            $table->index('developer_id');
        });
    }

    public function down(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->dropIndex(['start_time']);
            $table->dropIndex(['finish_time']);
            $table->dropIndex(['first_answered_employee_id']);
            $table->dropIndex(['last_talked_employee_id']);
            $table->dropIndex(['contact_phone_number']);
            $table->dropIndex(['status']);
            $table->dropIndex(['marketplace_id']);
            $table->dropIndex(['housing_estate_id']);
            $table->dropIndex(['developer_id']);
        });
    }
};

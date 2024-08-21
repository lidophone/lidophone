<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uis_employees', function (Blueprint $table) {
            $table->string('full_name')->after('uis_id');
        });

        Schema::table('uis_calls', function (Blueprint $table) {
            $table->dropColumn([
                'last_answered_employee_full_name',
                'first_answered_employee_full_name',
                'last_talked_employee_full_name',
                'first_talked_employee_full_name',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('uis_employees', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });

        Schema::table('uis_calls', function (Blueprint $table) {
            $table->string('last_answered_employee_full_name')->after('last_answered_employee_id');
            $table->string('first_answered_employee_full_name')->after('first_answered_employee_id');
            $table->string('last_talked_employee_full_name')->after('last_talked_employee_id');
            $table->string('first_talked_employee_full_name')->after('first_talked_employee_id');
        });
    }
};

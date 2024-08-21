<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->json('employees')->after('virtual_phone_number');

            $table->bigInteger('last_answered_employee_id')->nullable()->after('employees');
            $table->string('last_answered_employee_full_name')->nullable()->after('last_answered_employee_id');

            $table->bigInteger('first_answered_employee_id')->nullable()->after('last_answered_employee_full_name');
            $table->string('first_answered_employee_full_name')->nullable()->after('first_answered_employee_id');

            $table->bigInteger('last_talked_employee_id')->nullable()->after('first_answered_employee_full_name');
            $table->string('last_talked_employee_full_name')->nullable()->after('last_talked_employee_id');

            $table->bigInteger('first_talked_employee_id')->nullable()->after('last_talked_employee_full_name');
            $table->string('first_talked_employee_full_name')->nullable()->after('first_talked_employee_id');

            $table->boolean('is_transfer')->after('first_talked_employee_full_name');
        });
    }

    public function down(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->dropColumn([
                'employees',

                'last_answered_employee_id',
                'last_answered_employee_full_name',

                'first_answered_employee_id',
                'first_answered_employee_full_name',

                'last_talked_employee_id',
                'last_talked_employee_full_name',

                'first_talked_employee_id',
                'first_talked_employee_full_name',

                'is_transfer',
            ]);
        });
    }
};

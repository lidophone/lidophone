<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->unsignedBigInteger('uis_id')->change();
            $table->unsignedBigInteger('last_answered_employee_id')->change();
            $table->unsignedBigInteger('first_answered_employee_id')->change();
            $table->unsignedBigInteger('last_talked_employee_id')->change();
            $table->unsignedBigInteger('first_talked_employee_id')->change();
            $table->dropColumn('employees');
        });

        Schema::create('uis_calls_employees', function (Blueprint $table) {
            $table->unsignedBigInteger('uis_calls_uis_id');
            $table->unsignedBigInteger('employee_id');
            $table->boolean('is_answered');
            $table->boolean('is_talked');

            $table->foreign('uis_calls_uis_id')->references('uis_id')->on('uis_calls')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uis_calls_employees');

        Schema::table('uis_calls', function (Blueprint $table) {
            $table->bigInteger('uis_id')->change();
            $table->bigInteger('last_answered_employee_id')->change();
            $table->bigInteger('first_answered_employee_id')->change();
            $table->bigInteger('last_talked_employee_id')->change();
            $table->bigInteger('first_talked_employee_id')->change();
            $table->json('employees')->after('virtual_phone_number');
        });
    }
};

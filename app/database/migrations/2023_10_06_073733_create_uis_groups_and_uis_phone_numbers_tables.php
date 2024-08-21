<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uis_employees', function (Blueprint $table) {
            $table->unsignedBigInteger('uis_id')->change();
            $table->dropColumn('phone_number');
        });

        Schema::create('uis_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uis_id')->unique();
            $table->string('name');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('uis_employees_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('uis_employees_uis_id');
            $table->unsignedBigInteger('uis_groups_uis_id');

            $table->foreign('uis_employees_uis_id')->references('uis_id')->on('uis_employees')->cascadeOnDelete();
            $table->foreign('uis_groups_uis_id')->references('uis_id')->on('uis_groups')->cascadeOnDelete();
        });

        Schema::create('uis_phone_numbers', function (Blueprint $table) {
            $table->unsignedBigInteger('uis_employee_uis_id');
            $table->string('phone_number');

            $table->foreign('uis_employee_uis_id')->references('uis_id')->on('uis_employees')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uis_phone_numbers');
        Schema::dropIfExists('uis_employees_groups');
        Schema::dropIfExists('uis_groups');
        Schema::table('uis_employees', function (Blueprint $table) {
            $table->bigInteger('uis_id')->change();
            $table->string('phone_number')->after('uis_id');
        });
    }
};

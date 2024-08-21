<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::drop('uis_phone_numbers');
    }

    public function down(): void
    {
        Schema::create('uis_phone_numbers', function (Blueprint $table) {
            $table->unsignedBigInteger('uis_employee_uis_id');
            $table->string('phone_number');

            $table->foreign('uis_employee_uis_id')->references('uis_id')->on('uis_employees')->cascadeOnDelete();
        });
    }
};

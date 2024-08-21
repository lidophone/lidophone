<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uis_calls_employees', function (Blueprint $table) {
            $table->id()->first();
        });
    }

    public function down(): void
    {
        Schema::table('uis_calls_employees', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->integer('total_duration')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->integer('total_duration')->nullable(false)->change();
        });
    }
};

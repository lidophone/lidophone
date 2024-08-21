<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('metro_lines', function (Blueprint $table) {
            $table->string('designation', 2)->nullable()->unique()->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('metro_lines', function (Blueprint $table) {
            $table->dropColumn('designation');
        });
    }
};

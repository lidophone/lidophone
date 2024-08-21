<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->boolean('reparse')->default(0)->after('operator_award');
        });
    }

    public function down(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->dropColumn('reparse');
        });
    }
};

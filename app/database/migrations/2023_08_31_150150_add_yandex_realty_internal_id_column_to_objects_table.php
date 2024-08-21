<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('objects', function (Blueprint $table) {
            $table->string('yandex_realty_internal_id')->nullable()->after('deadline_quarter');
        });
    }

    public function down(): void
    {
        Schema::table('objects', function (Blueprint $table) {
            $table->dropColumn('yandex_realty_internal_id');
        });
    }
};

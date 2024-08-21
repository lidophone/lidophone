<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('yandex_realty', function (Blueprint $table) {
            $table->text('description')->after('images');
        });
    }

    public function down(): void
    {
        Schema::table('yandex_realty', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedInteger('price')->change();
        });
        DB::statement('ALTER TABLE `offers` MODIFY COLUMN `operator_award` DOUBLE NOT NULL');
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->double('operator_award')->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->dropColumn('operator_award');
        });
        DB::statement('ALTER TABLE `offers` MODIFY COLUMN `operator_award` DOUBLE(8, 2)');
        Schema::table('offers', function (Blueprint $table) {
            $table->integer('price')->change();
        });
    }
};

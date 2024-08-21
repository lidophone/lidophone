<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedBigInteger('marketplace_id')->nullable()->after('id');
            $table->foreign('marketplace_id')->references('id')->on('marketplaces')->onDelete('CASCADE');
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign(['marketplace_id']);
            $table->dropColumn('marketplace_id');
        });
    }
};

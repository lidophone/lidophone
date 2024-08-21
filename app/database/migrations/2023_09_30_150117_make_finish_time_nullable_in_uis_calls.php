<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->dateTime('finish_time')->nullable()->change();
            DB::statement(
                'ALTER TABLE uis_calls MODIFY COLUMN communication_type ENUM(\'call\') NULL'
            );
        });
    }

    public function down(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->dateTime('finish_time')->nullable(false)->change();
            DB::statement(
                'ALTER TABLE uis_calls MODIFY COLUMN communication_type ENUM(\'call\') NOT NULL'
            );
        });
    }
};

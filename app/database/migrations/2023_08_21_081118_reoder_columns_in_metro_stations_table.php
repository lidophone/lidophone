<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `metro_stations` MODIFY COLUMN `metro_line_id` BIGINT UNSIGNED AFTER `id`');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `metro_stations` MODIFY COLUMN `metro_line_id` BIGINT UNSIGNED AFTER `name`');
    }
};

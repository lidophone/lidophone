<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `metro_lines` MODIFY COLUMN `city_id` BIGINT UNSIGNED AFTER `id`');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `metro_lines` MODIFY COLUMN `city_id` BIGINT UNSIGNED AFTER `name`');
    }
};

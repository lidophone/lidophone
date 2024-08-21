<?php

use App\Models\YandexRealty;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            'ALTER TABLE yandex_realty MODIFY COLUMN building_state ENUM(\'' . implode("','", YandexRealty::BUILDING_STATES) . '\')'
        );
    }
};

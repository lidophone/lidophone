<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
UPDATE uis_calls
    LEFT JOIN housing_estates ON housing_estates.id = uis_calls.housing_estate_id
    LEFT JOIN developers ON developers.id = housing_estates.developer_id
SET uis_calls.developer_id = developers.id
WHERE uis_calls.housing_estate_id IS NOT NULL AND is_developer = 0;

UPDATE uis_calls
    LEFT JOIN developers ON developers.id = uis_calls.housing_estate_id
SET uis_calls.developer_id = developers.id
where uis_calls.housing_estate_id IS NOT NULL AND is_developer = 1;
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->dropColumn('is_developer');
        });
    }

    public function down(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->boolean('is_developer')->after('housing_estate_id');
        });
    }
};

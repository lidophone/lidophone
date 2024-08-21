<?php

use App\Models\Offer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedBigInteger('developer_id')->after('id');
        });

        // $offers = Offer::all();
        // foreach ($offers as $offer) {
        //     $offer->update(['developer_id' => $offer->housingEstate->developer->id]);
        // }

        Schema::table('offers', function (Blueprint $table) {
            $table->foreign('developer_id')->references('id')->on('developers')->cascadeOnDelete();

            $table->unsignedBigInteger('housing_estate_id')->nullable()->change();
        });

        DB::statement('ALTER TABLE `offers` MODIFY COLUMN `housing_estate_id` BIGINT UNSIGNED AFTER `developer_id`');
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedBigInteger('housing_estate_id')->nullable(false)->change();

            $table->dropForeign(['developer_id']);
            $table->dropColumn('developer_id');
        });
    }
};

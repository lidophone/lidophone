<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('housing_estate_id');
            $table->unsignedBigInteger('external_id');
            $table->string('sip_uri');
            $table->integer('call_limit');
            $table->integer('uniqueness_period')->comment('Срок уникальности (https://shorturl.at/nzHJ5)');
            $table->integer('price');
            $table->float('operator_award')->comment('Вознаграждение оператору, дробное число; например, 0.25 (от `price`)');
            $table->boolean('client_is_out_of_town');
            $table->boolean('looking_not_for_himself');
            $table->unsignedBigInteger('scenario_id');
            $table->boolean('active');
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('housing_estate_id')->references('id')->on('housing_estates')->onDelete('CASCADE');
            $table->foreign('scenario_id')->references('id')->on('scenarios')->onDelete('CASCADE');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};

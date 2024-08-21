<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('objects', function (Blueprint $table) {
            $table->comment('Flats & Apartments & etc');

            $table->id();
            $table->unsignedBigInteger('housing_estate_id');
            $table->unsignedTinyInteger('roominess');
            $table->unsignedTinyInteger('real_estate_type');
            $table->unsignedTinyInteger('finishing');
            $table->integer('square_meters');
            $table->integer('price');
            $table->boolean('done');
            $table->unsignedSmallInteger('deadline_year')->nullable();
            $table->unsignedTinyInteger('deadline_quarter')->nullable();
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('housing_estate_id')->references('id')->on('housing_estates')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objects');
    }
};

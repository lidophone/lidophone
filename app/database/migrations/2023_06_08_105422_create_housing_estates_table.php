<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('housing_estates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('developer_id');
            $table->unsignedBigInteger('city_id');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('site')->nullable();
            $table->text('location');
            $table->text('advantages');
            $table->text('payment');
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('CASCADE');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('CASCADE');;
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('housing_estates');
    }
};

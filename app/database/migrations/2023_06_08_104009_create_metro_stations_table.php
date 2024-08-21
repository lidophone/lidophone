<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metro_stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('metro_line_id');
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('metro_line_id')->references('id')->on('metro_lines')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metro_stations');
    }
};

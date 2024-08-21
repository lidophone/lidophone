<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uis_employees', function (Blueprint $table) {
            $table->comment('Documentation: https://is.gd/kzwcxT');

            $table->id();
            $table->bigInteger('uis_id')->unique();
            $table->string('phone_number')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uis_employees');
    }
};

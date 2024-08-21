<?php

use App\Models\Settings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->string('value');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Settings::create([
            'name' => 'Выделить цены до',
            'key' => 'highlight_prices_up_to',
            'value' => 200,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

<?php

use App\Models\Uis\UisCall;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uis_calls', function (Blueprint $table) {
            $table->comment('Documentation: https://is.gd/tif04K');

            $table->id();
            $table->bigInteger('uis_id')->unique();
            $table->enum('source', UisCall::SOURCES);
            $table->boolean('is_lost');
            $table->enum('direction', UisCall::DIRECTIONS);
            $table->datetime('start_time');
            $table->datetime('finish_time');
            $table->json('call_records');
            $table->integer('cpn_region_id');
            $table->enum('finish_reason', UisCall::FINISH_REASONS);
            $table->integer('talk_duration');
            $table->integer('wait_duration');
            $table->integer('total_duration');
            $table->string('cpn_region_name');
            $table->bigInteger('communication_id');
            $table->json('wav_call_records');
            $table->enum('communication_type', ['call']);
            $table->integer('clean_talk_duration');
            $table->integer('total_wait_duration');
            $table->string('virtual_phone_number');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uis_calls');
    }
};

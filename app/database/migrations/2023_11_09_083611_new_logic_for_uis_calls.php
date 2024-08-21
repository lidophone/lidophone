<?php

use App\Models\Uis\UisCall;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::drop('uis_calls_employees');

        Schema::table('uis_calls', function (Blueprint $table) {
            $table->dropColumn([
                'source',
                'is_lost',
                'direction',
                'cpn_region_id',
                'finish_reason',
                'talk_duration',
                'wait_duration',
                'total_duration',
                'cpn_region_name',
                'wav_call_records',
                'communication_type',
                'clean_talk_duration',
                'total_wait_duration',
                'virtual_phone_number',
                'last_answered_employee_id',
                'first_talked_employee_id',
                'is_transfer',
            ]);

            $table->unsignedBigInteger('last_leg_employee_id')->nullable()->after('contact_phone_number');
            $table->string('last_leg_called_phone_number')->nullable()->after('last_leg_employee_id');
            $table->unsignedInteger('last_leg_duration')->nullable()->after('last_leg_called_phone_number');
        });
    }

    public function down(): void
    {
        Schema::create('uis_calls_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uis_calls_uis_id');
            $table->unsignedBigInteger('employee_id');
            $table->boolean('is_answered');
            $table->boolean('is_talked');

            $table->foreign('uis_calls_uis_id')->references('uis_id')->on('uis_calls')->cascadeOnDelete();
        });

        Schema::table('uis_calls', function (Blueprint $table) {
            $table->enum('source', UisCall::SOURCES)->after('uis_id');
            $table->boolean('is_lost')->after('source');
            $table->enum('direction', UisCall::DIRECTIONS)->after('is_lost');
            $table->integer('cpn_region_id')->nullable()->after('call_records');
            $table->enum('finish_reason', UisCall::FINISH_REASONS)->after('cpn_region_id');
            $table->integer('talk_duration')->nullable()->after('finish_reason');
            $table->integer('wait_duration')->nullable()->after('talk_duration');
            $table->integer('total_duration')->nullable()->after('wait_duration');
            $table->string('cpn_region_name')->nullable()->after('total_duration');
            $table->json('wav_call_records')->after('communication_id');
            $table->enum('communication_type', ['call'])->nullable()->after('wav_call_records');
            $table->integer('clean_talk_duration')->after('communication_type');
            $table->integer('total_wait_duration')->nullable()->after('clean_talk_duration');
            $table->string('virtual_phone_number')->nullable()->after('total_wait_duration');
            $table->unsignedBigInteger('last_answered_employee_id')->nullable()->after('virtual_phone_number');
            $table->unsignedBigInteger('first_talked_employee_id')->nullable()->after('last_talked_employee_id');
            $table->boolean('is_transfer')->after('first_talked_employee_id');

            $table->dropColumn([
                'last_leg_employee_id',
                'last_leg_called_phone_number',
                'last_leg_duration',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->boolean('notification_sent')->after('reparse');
        });
    }

    public function down(): void
    {
        Schema::table('uis_calls', function (Blueprint $table) {
            $table->dropColumn('notification_sent');
        });
    }
};

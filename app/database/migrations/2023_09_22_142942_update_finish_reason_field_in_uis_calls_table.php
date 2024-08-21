<?php

use App\Models\Uis\UisCall;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            'ALTER TABLE uis_calls MODIFY COLUMN finish_reason ENUM(\'' . implode("','", UisCall::FINISH_REASONS) . '\')'
        );
    }
};

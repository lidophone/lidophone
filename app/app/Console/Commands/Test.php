<?php

namespace App\Console\Commands;

use App;
use App\Notifications\TransferReport;
use Illuminate\Notifications\Notifiable;

class Test extends BaseCommand
{
    use Notifiable;

    protected $signature = 'app:test';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        // $this->notify(new TransferReport(App\Models\Uis\UisCall::first()));

        // $this->line(date_default_timezone_get());
        // $this->line(App::environment());

        // App\Events\DailyCompetitionUpdate::dispatch();

        $this->printCommandCompletionMessage();
    }
}

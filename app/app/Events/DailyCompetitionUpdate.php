<?php

namespace App\Events;

use App\Services\DailyCompetition;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DailyCompetitionUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $data;

    public function __construct()
    {
        $this->data = (new DailyCompetition())->getDataForToday();
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('daily-competition'),
        ];
    }
}

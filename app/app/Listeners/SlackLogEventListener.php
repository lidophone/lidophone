<?php

namespace App\Listeners;

use App\Events\SlackLogEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SlackLogEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SlackLogEvent $event): void
    {
        $debug = true;
    }
}

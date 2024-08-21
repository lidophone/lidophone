<?php

namespace App\Console\Commands\Offers;

use App\Console\Commands\BaseCommand;
use GuzzleHttp\Client;

abstract class BaseOffersCommand extends BaseCommand
{
    protected Client $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
    }
}

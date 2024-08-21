<?php

namespace App\Console\Commands\Uis;

use App\Console\Commands\BaseCommand;
use App\Models\Settings;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Log;

abstract class BaseUisCommand extends BaseCommand
{
    protected const BASE_URL = 'https://dataapi.uiscom.ru/v2.0';

    protected Client $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
    }

    protected function checkSettings(): void
    {
        $parseUisCalls = (bool) Settings::where('key', 'parse_uis_calls')->value('value');
        if (!$parseUisCalls) {
            $this->line('UIS calls parsing disabled');
            die;
        }
    }

    protected static function getBasePostRequestOptions(string $method, array $params = []): array
    {
        return [
            'id' => time(),
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => array_merge(['access_token' => config('app.uis.apiKey')], $params),
        ];
    }

    protected function getBody(Response $response): array
    {
        return json_decode($response->getBody(), true);
    }

    protected function logAndDieIfThereIsAnError(array $body): void
    {
        if (isset($body['error'])) {
            $this->logErrorMessage(print_r($body['error'], true));
            die;
        }
    }

    protected function logErrorMessage(string $message): void
    {
        $this->line($message);
        Log::error($message);
    }
}

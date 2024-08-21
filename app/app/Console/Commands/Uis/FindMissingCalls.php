<?php

namespace App\Console\Commands\Uis;

use App\Models\Uis\UisCall;
use DateTimeImmutable;
use Log;

class FindMissingCalls extends BaseUisCommand
{
    protected $signature = 'app:uis:find-missing-calls {--date=}';

    /**
     * Total transfers for the period from 2023-10-01 to 2023-10-02: 66
     */
    public function handle(): void
    {
        $dateTimeString = $this->option('date');
        if ($dateTimeString) {
            $dateTime = new DateTimeImmutable($dateTimeString);
        } else {
            $dateTime = LIDOFON_DATE_TIME_MOSCOW;
        }

        $params = [
            'date_from' => $dateTime->setTime(0, 0)->format('Y-m-d H:i:s'),
            'date_till' => $dateTime->modify('+1 day')->setTime(0, 0)->format('Y-m-d H:i:s'),
            'include_ongoing_calls' => true,
            'sort' => [
                ['field' => 'start_time'],
            ],
            'filter' => [
                'field' => 'is_transfer',
                'operator' => '=',
                'value' => true,
            ],
            'fields' => ['id'],
        ];

        $response = $this->client->post(self::BASE_URL, [
            'json' => self::getBasePostRequestOptions('get.calls_report', $params)
        ]);
        $body = $this->getBody($response);

        $uisIds = array_column($body['result']['data'], 'id');
        $uisIdsInOurDb = UisCall::whereBetween('start_time', [$params['date_from'], $params['date_till']])
            ->pluck('uis_id')
            ->toArray();

        $diffIds = array_diff($uisIds, $uisIdsInOurDb);
        $numberOfMissingIds = count($diffIds);

        Log::info(
            'Missing UIS calls for the day ' . $dateTime->format('Y-m-d') . ' ' .
            '(' . $numberOfMissingIds . ')' .
            ($numberOfMissingIds ? "\n\n" . '```' . implode(', ', $diffIds) . '```' : '')
        );

        $this->printCommandCompletionMessage();
    }
}

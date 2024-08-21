<?php

namespace App\Console\Commands\Offers;

use App\Enums\TrueFalse;
use App\Models\Offer;

class Callexchange extends BaseOffersCommand
{
    protected $signature = 'app:offers:callexchange';

    private const MARKETPLACE_ID = 1;
    private const URL = 'https://callexchange.ru/api/v2/stats/bo/dashboard_out/active_phones?token=82fbe8834695f497a7ab00407488439d070f93696dcfbb0c03eb4e3874736024';

    public function handle(): void
    {
        //*
        $response = $this->client->get(self::URL);
        $body = $response->getBody();
        $items = json_decode($body, true);
        /*/
        $items = [
            [
                // Change `marketplace_id` to self::MARKETPLACE_ID in the records retrieved with the following SQL:
                // select * from offers where sip_uri like '74954879138@%'
                'phone' => '74954879138',
                'unenrolled_calls_count' => 0,
                // 'unenrolled_calls_count' => 1,
                'price' => 1000,
            ]
        ];
        //*/
        foreach ($items as $item) {
            $data = [
                'active' => ($item['unenrolled_calls_count'] === 0 || $item['price'] === 0)
                    ? TrueFalse::False->value
                    : TrueFalse::True->value,
            ];
            if ($item['price'] !== 0) {
                $data['price'] = $item['price'];
            }
            Offer::where('marketplace_id', self::MARKETPLACE_ID)
                ->where('active', TrueFalse::True->value)
                ->where('sip_uri', 'like', $item['phone'] . '%')
                ->update($data);
        }
    }
}

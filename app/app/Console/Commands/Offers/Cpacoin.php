<?php

namespace App\Console\Commands\Offers;

use App\Enums\TrueFalse;
use App\Models\Offer;

class Cpacoin extends BaseOffersCommand
{
    protected $signature = 'app:offers:cpacoin';

    private const MARKETPLACE_ID = 4;
    private const URL = 'https://cpa.getflat.info/api/get_contractor_offers?token=d7fa6d6de00a496466484d341a052c86';

    public function handle(): void
    {
        //*
        $response = $this->client->get(self::URL);
        $body = $response->getBody();
        $items = json_decode($body, true);
        /*/
        $items = [
            'result' => [
                [
                    // Change `marketplace_id` to self::MARKETPLACE_ID in the records retrieved with the following SQL:
                    // select * from offers where sip_uri like '74954879138@%'
                    'phone' => '74954879138',
                    'cost' => 1000,
                ]
            ]
        ];
        //*/
        foreach ($items['result'] as $item) {
            Offer::where('marketplace_id', self::MARKETPLACE_ID)
                ->where('active', TrueFalse::True->value)
                ->where('sip_uri', 'like', $item['phone'] . '@%')
                ->update(['price' => $item['cost']]);
        }
    }
}

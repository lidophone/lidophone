<?php

namespace App\Console\Commands\Offers;

use App\Enums\TrueFalse;
use App\Models\Offer;

class Marketcall extends BaseOffersCommand
{
    protected $signature = 'app:offers:marketcall';

    private const MARKETPLACE_ID = 2;
    private const URL = 'https://www.marketcall.ru/api/v1/affiliate/programs?api_key=a5cd9a49e4fb93dda9cdf50a7bc0b6897e0e5155&states[]=6&offer_states=1';
    private const OFFER_URL = 'https://www.marketcall.ru/api/v1/affiliate/offers/{id}?api_key=a5cd9a49e4fb93dda9cdf50a7bc0b6897e0e5155';

    public function handle(): void
    {
        $response = $this->client->get(self::URL);
        $body = $response->getBody();
        $items = json_decode($body, true);
        $phones = array_column($items['data'], 'phone');
        $activeOffers = Offer::where('marketplace_id', self::MARKETPLACE_ID)
            ->where('active', TrueFalse::True->value)
            ->get();
        foreach ($activeOffers as $activeOffer) {
            $explodedSipUri = explode('@', $activeOffer->sip_uri);
            if (!in_array($explodedSipUri[0], $phones)) {
                $activeOffer->update(['active' => TrueFalse::False->value]);
            }
        }
        foreach ($items['data'] as $item) {
            $response = $this->client->get(str_replace('{id}', $item['offer_id'], self::OFFER_URL));
            $body = $response->getBody();
            $offer = json_decode($body, true);
            Offer::where('marketplace_id', self::MARKETPLACE_ID)
                ->where('active', TrueFalse::True->value)
                ->where('sip_uri', 'like', $item['phone'] . '@%')
                ->update(['price' => $offer['data']['tariffs'][0]['price']]);
        }
    }
}

<?php

namespace App\Console\Commands\PriceParsing;

use App\Console\Commands\BaseParseCommand;
use App\Models\YandexRealty;
use App\Models\YandexRealtyTmp;
use SimpleXMLElement;
use XMLReader;

class Parse extends BaseParseCommand
{
    protected $signature = 'app:price-parsing:parse {developer} {--download}';

    protected const CLASS_NAME = YandexRealty::class;

    private const ITEM_NAME = 'offer';

    /**
     * @see https://replit.com/@w3lifer/php-XMLReader
     */
    public function handle(): void
    {
        $developer = $this->argument('developer');
        $download = $this->option('download');

        if ($download) {
            $this->call(Download::class, ['developer' => $developer]);
        }

        $this->createTmpTable();

        $xml = new XMLReader();
        $pathToXml = Download::getFilename($developer);
        $xml->open($pathToXml);
        while($xml->read() && $xml->name !== self::ITEM_NAME);

        $items = [];

        while($xml->name === self::ITEM_NAME)
        {
            $element = new SimpleXMLElement($xml->readOuterXML());

            $items[] = self::simpleXmlElementToArray($element);

            if (count($items) === 500) {
                YandexRealtyTmp::insert($items);
                $items = [];
            }

            unset($element);
            $xml->next(self::ITEM_NAME);
        }
        $xml->close();

        if ($items) {
            YandexRealtyTmp::insert($items);
        }

        $this->renameTmpTable();

        $this->printCommandCompletionMessage();
    }

    /**
     * See the $element example below the method
     */
    private static function simpleXmlElementToArray(SimpleXMLElement $element): array
    {
        // [!] DON'T USE THE FOLLOWING BECAUSE MEMORY CONSUMPTION INCREASES AND "description" IS NOT PARSED PROPERLY
        // $data = json_decode(json_encode($element), true);

        $metro = [];
        if ($element->location->metro) {
            // $metro = $element->location->metro; // SimpleXMLElement
            // $metro = (array) $element->location->metro; // Associative array with the first metro station
            // SimpleXMLElement object OR array with SimpleXMLElement objects
            $metro = ((array) $element->location)['metro'];
            if ($metro instanceof SimpleXMLElement) {
                $metro = [$metro];
            }
        }

        $images = (array) $element->image;
        unset($images['@attributes']);
        // Delete an apartment plan and a floor plan
        $images = array_filter($images, fn ($url) => !str_contains($url, 'exchange.novostroy-m.ru'));
        $images = array_values($images); // Reindexing

        $description = explode("\n\n", (string) $element->description);
        $description = array_slice($description, 1); // Starting from the second paragraph
        $description = implode("\n\n", $description);
        $description = preg_replace('~ Артикул - \d+$~', '', $description); // Delete "Артикул - 123456"

        return [
            'internal_id' => (string) $element->attributes()['internal-id'],
            'organization' => (string) $element->{'sales-agent'}->organization,
            'yandex_building_id' => (int) $element->{'yandex-building-id'},
            'building_name' => (string) $element->{'building-name'},
            'built_year' => (int) $element->{'built-year'},
            'ready_quarter' => (int) $element->{'ready-quarter'},
            'building_state' => (string) $element->{'building-state'},
            'apartments' => ($apartments = (string) $element->apartments) ? $apartments : null,
            'rooms' => ($rooms = (int) $element->rooms) ? $rooms : null,
            'studio' => ($studio = (string) $element->studio) ? $studio : null,
            'renovation' => ($renovation = (string) $element->renovation) ? $renovation : null,
            'area_value' => (float) $element->area->value,
            'area_unit' => (string) $element->area->unit,
            'price_value' => (int) $element->price->value,
            'price_currency' => (string) $element->price->currency,
            'region' => ($region = $element->location->region) ? $region : null,
            'locality_name' => (string) $element->location->{'locality-name'},
            'metro' => json_encode($metro),
            'latitude' => (double) $element->location->latitude,
            'longitude' => (double) $element->location->longitude,
            'images' => json_encode($images),
            'description' => $description,
        ];
    }
}

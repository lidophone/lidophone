<?php

namespace App\Console\Commands\PriceParsing;

use App\Console\Commands\BaseCommand;
use App\Enums\Finishing;
use App\Enums\RealEstateType;
use App\Enums\Roominess;
use App\Enums\TrueFalse;
use App\Models\City;
use App\Models\Developer;
use App\Models\HousingEstate;
use App\Models\HousingEstateMetroStation;
use App\Models\MetroStation;
use App\Models\Objects;
use App\Models\YandexRealty;
use DB;
use Illuminate\Database\Eloquent\Builder;

class Sync extends BaseCommand
{
    protected $signature = 'app:price-parsing:sync {developer} {--download-and-parse}';

    private const DEVELOPER_SITE_PIK_MOSCOW = 'https://pik.ru';
    private const DEVELOPER_SITE_PIK_SPB = self::DEVELOPER_SITE_PIK_MOSCOW;
    private const DEVELOPER_SITE_KVS = 'https://kvsspb.ru';

    private const SQL_SELECT_ALL = '

SELECT
    yandex_building_id,
    building_name,
    built_year,
    ready_quarter,
    rooms,
    studio,
    MIN(price_value) AS price_value
FROM yandex_realty
GROUP BY yandex_building_id, building_name, built_year, ready_quarter, rooms, studio

';

    private const SQL_SELECT_DUPLICATES = '

SELECT MIN(id) AS id
FROM objects
GROUP BY yandex_realty_internal_id
HAVING COUNT(id) > 1 AND yandex_realty_internal_id IS NOT NULL

';

    public function handle(): void
    {
        $developerArgument = $this->argument('developer');

        if ($this->option('download-and-parse')) {
            $this->call(Parse::class, ['developer' => $developerArgument, '--download' => true]);
        }

        $objectsData = DB::select(self::SQL_SELECT_ALL);
        $yandexRealtyInternalIds = [];
        foreach ($objectsData as $objectsDatum) {
            $objectsDatum = (array) $objectsDatum;
            $object = YandexRealty::where($objectsDatum)->first();

            if (!$object->locality_name && !$object->region) {
                continue;
            }
            $city = City::where('name', $object->locality_name)->orWhere('region_name', $object->region)->first();
            if (!$city) {
                continue;
            }

            $developer = Developer::firstOrCreate([
                'name' => $object->organization . ': ' . $city->name,
                'automatic_handling' => TrueFalse::True,
            ]);

            $housingEstate = HousingEstate::where([
                'developer_id' => $developer->id,
                'city_id' => $city->id,
                'name' => $object->building_name,
            ])->first();
            $isRegion = $object->region ? TrueFalse::True : TrueFalse::False;
            $site = self::getSite($developerArgument);
            if (!$housingEstate) {
                $housingEstate = HousingEstate::create([
                    'name' => $object->building_name, // Readonly
                    'developer_id' => $developer->id, // Readonly
                    'city_id' => $city->id, // Readonly
                    'is_region' => $isRegion, // Readonly
                    'latitude' => $object->latitude, // Editable
                    'longitude' => $object->longitude, // Editable
                    'site' => $site,
                    'location' => $object->description, // Editable
                    'advantages' => '', // Editable
                    'images' => $object->images, // Readonly
                ]);
            } else {
                $housingEstate->update([
                    'site' => $site,
                    'is_region' => $isRegion,
                    'images' => $object->images,
                ]);
            }

            $metroStations = json_decode($object->metro, true);
            foreach ($metroStations as $metroStation) {
                $metroInOurDb = MetroStation::where('name', $metroStation['name'])
                    ->whereHas('metroLine.city', fn (Builder $query) => $query->where('cities.id', $city->id))
                    ->first();
                if ($metroInOurDb) {
                    HousingEstateMetroStation::updateOrInsert([
                        'housing_estate_id' => $housingEstate->id,
                        'metro_station_id' => $metroInOurDb->id,
                    ], [
                        'time_on_foot' => $metroStation['time-on-foot'] ?? null,
                        'time_by_car' => $metroStation['time-on-transport'] ?? null,
                    ]);
                }
            }

            if (in_array($object->building_state, YandexRealty::BUILDING_STATES_READY)) {
                $done = TrueFalse::True;
                $deadlineYear = null;
                $deadlineQuarter = null;
            } else {
                $done = TrueFalse::False;
                $deadlineYear = $object->built_year;
                $deadlineQuarter = $object->ready_quarter;
            }

            Objects::updateOrInsert([
                'housing_estate_id' => $housingEstate->id,
                'roominess' => self::mapRoominess($object),
                'real_estate_type' => $object->apartments ? RealEstateType::Apartments : RealEstateType::Flat,
                'finishing' => self::mapFinishing($object->renovation),
                'done' => $done,
                'deadline_year' => $deadlineYear,
                'deadline_quarter' => $deadlineQuarter,
            ], [
                'square_meters' => $object->area_value,
                'price' => $object->price_value,
                'yandex_realty_internal_id' => $object->internal_id,
            ]);

            $yandexRealtyInternalIds[] = $object->internal_id;
        }

        $duplicates = DB::select(self::SQL_SELECT_DUPLICATES);
        if ($duplicates) {
            $duplicatesIds = array_column($duplicates, 'id');
            Objects::whereIn('id', $duplicatesIds)->delete();
        }

        if (!empty($developer)) {
            Objects::whereDoesntHave(
                'housingEstate',
                fn (Builder $query) => $query->whereNot('housing_estates.developer_id', $developer->id)
            )
                ->whereNotNull('yandex_realty_internal_id')
                ->whereNotIn('yandex_realty_internal_id', $yandexRealtyInternalIds)
                ->delete();
        }

        $this->printCommandCompletionMessage();
    }

    private static function getSite(string $developer): string
    {
        return match ($developer) {
            Download::DEVELOPER_KVS => self::DEVELOPER_SITE_KVS,
            Download::DEVELOPER_PIK_MOSCOW => self::DEVELOPER_SITE_PIK_MOSCOW,
            Download::DEVELOPER_PIK_SPB => self::DEVELOPER_SITE_PIK_SPB,
        };
    }

    private static function mapRoominess(YandexRealty $yandexObject): int
    {
        if ($yandexObject->studio) {
            return Roominess::TYPE_C->value;
        }

        return match ($yandexObject->rooms) {
            1 => Roominess::TYPE_1K->value,
            2 => Roominess::TYPE_2K->value,
            3 => Roominess::TYPE_3K->value,
            4 => Roominess::TYPE_4K->value,
            5 => Roominess::TYPE_5K->value,
        };
    }

    private static function mapFinishing(?string $yandexFinishing): string
    {
        return match ($yandexFinishing) {
            null, "'...'", 'черновая отделка' => Finishing::Without_finishing->value,
            'с отделкой', 'Под ключ', 'чистовая отделка' => Finishing::With_renovation->value,
        };
    }
}

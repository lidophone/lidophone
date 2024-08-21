<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use App\Models\HousingEstate;
use App\Services\Filter;
use Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ObjectsController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $housingEstateFields = ['id', 'name', 'developer_id', 'is_region', 'latitude', 'longitude'];
        $objectFields = ['housing_estate_id', 'real_estate_type', 'done', 'deadline_year', 'deadline_quarter'];
        $offerFields = ['developer_id', 'housing_estate_id', 'price', 'operator_award', 'active'];

        $get = $request->query();
        $query = HousingEstate::with([
            'objects' => fn (HasMany $query) => $query->select($objectFields),
            'offers' => fn (HasMany $query) => $query->select($offerFields),
            'developer' => fn (BelongsTo $query) => $query->with([
                'offers' => fn (HasMany $query) => $query->select($offerFields),
            ])->select('id'),
        ])
            ->select($housingEstateFields);

        Filter::filter($get, $query);

        $housingEstatesIdsOfOffersLinkedToDeveloper = Developer::getHousingEstatesIdsOfOffersLinkedToDeveloper($get);
        if ($housingEstatesIdsOfOffersLinkedToDeveloper) {
            $queryLinkedToDeveloper = HousingEstate::with([
                'objects' => fn (HasMany $query) => $query->select($objectFields),
                'developer' => fn (BelongsTo $query) => $query->with([
                    'offers' => fn (HasMany $query) => $query->select($offerFields),
                ])->select('id'),
            ])
                ->select($housingEstateFields)
                ->whereIn('id', $housingEstatesIdsOfOffersLinkedToDeveloper);
            Filter::filter($get, $queryLinkedToDeveloper, true);
            $query->union($queryLinkedToDeveloper);
        }

        return response()->json($query->get());
    }

    public function get(int $id, Request $request): JsonResponse
    {
        $housingEstateHasOffers = HousingEstate::where(['id' => $id])->has('offers')->exists();

        $disabled = $request->get('disabled');
        $with = [
            'developer',
            'city',
            'infrastructure',
            'metroStations.metroLine',
            'promotions',
            'tags',
            'objects' => function (HasMany $query) {
                $query
                    ->orderBy('done')
                    ->orderBy('deadline_year')
                    ->orderBy('deadline_quarter')
                    ->orderBy('real_estate_type')
                    ->orderBy('finishing')
                    ->orderBy('roominess')
                ;
            },
            'media'
        ];

        $offersRelationName = $housingEstateHasOffers ? 'offers' : 'developer.offers';

        $with[$offersRelationName] = function (HasMany $query) use ($disabled) {
            if (!$disabled) {
                $query->where('active', 1);
            }
            if (!Auth::user()->expert_mode) {
                $query->whereNot('offers.expert_mode', 1);
            }
        };
        $with[] = $offersRelationName . '.marketplace';
        $with[] = $offersRelationName . '.scenario';
        $with[] = $offersRelationName . '.workingHours';

        $with[] = $housingEstateHasOffers ? 'paymentMethods' : 'developer.paymentMethods';

        $query = HousingEstate::with($with)->where(['id' => $id]);

        $housingEstate = $query->first()->toArray();
        if (isset($housingEstate['developer']['offers'])) {
            $housingEstate['offers'] = $housingEstate['developer']['offers'];
            unset($housingEstate['developer']['offers']);
        }

        return response()->json($housingEstate);
    }
}

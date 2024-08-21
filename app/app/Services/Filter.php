<?php

namespace App\Services;

use App\Enums\DayOfWeek;
use App\Enums\Roominess;
use App\Enums\TrueFalse;
use App\Enums\Uis\UisStatus;
use App\Helpers\FormatHelper;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Builder;

class Filter
{
    public static function filter(array $get, Builder $query, bool $linkedToDeveloper = false): void
    {
        $query->where(function (Builder $query) use ($get, $linkedToDeveloper) {
            self::filterByOfferColumns($get, $query, $linkedToDeveloper);
            self::filterByObjectColumns($get, $query);
            if (!empty($get['tags'])) {
                self::filterByTags($get['tags'], $query);
            }
            if (!empty($get['developers'])) {
                self::filterByDevelopers($get['developers'], $query);
            }
            if (!empty($get['paymentMethods'])) {
                self::filterByPaymentMethods($get['paymentMethods'], $query, $linkedToDeveloper);
            }
        });
        if (!empty($get['disabled']) && empty($get['developers'])) {
            $query->orDoesntHave('offers');
        }
    }

    private static function filterByOfferColumns(array $get, Builder $query, bool $linkedToDeveloper): void
    {
        $query->whereHas(
            $linkedToDeveloper ? 'developer.offers.marketplace' : 'offers.marketplace',
            static function (Builder $offers) use ($get) {
                if (!User::isExpertMode()) {
                    $offers->whereNot('marketplaces.expert_mode', 1);
                }
                if (!empty($get['marketplaces'])) {
                    $offers
                        ->where('active', TrueFalse::True->value)
                        ->whereIn('marketplaces.id', $get['marketplaces']);
                }
            }
        );

        $query->whereHas(
            $linkedToDeveloper ? 'developer.offers.workingHours' : 'offers.workingHours',
            static function (Builder $offers) use ($get) {
                if (!User::isExpertMode()) {
                    $offers->whereNot('offers.expert_mode', 1);
                }
                if (!empty($get['clientIsOutOfTown'])) {
                    $offers->where('offers.client_is_out_of_town', 1);
                }
                if (!empty($get['lookingNotForHimself'])) {
                    $offers->where('offers.looking_not_for_himself', 1);
                }
                // If `notWorkingHours` is empty, check the schedule for the current time
                if (empty($get['notWorkingHours'])) {
                    $dayOfWeek = (int) LIDOFON_DATE_TIME_MOSCOW->format('N');
                    if ($dayOfWeek < DayOfWeek::Sa->value) {
                        $offers->whereIn('working_hours.day_of_week', [
                            $dayOfWeek,
                            DayOfWeek::MoFr->value,
                            DayOfWeek::MoSu->value,
                        ]);
                    } else {
                        $offers->whereIn('working_hours.day_of_week', [
                            $dayOfWeek,
                            DayOfWeek::MoSu->value,
                        ]);
                    }

                    $hoursAndMinutes = LIDOFON_DATE_TIME_MOSCOW->format('H:i');
                    $offers
                        ->where('working_hours.start_time', '<=', $hoursAndMinutes)
                        ->where('working_hours.end_time', '>', $hoursAndMinutes);
                }
                if (empty($get['disabled'])) {
                    $offers->where('offers.active', 1);
                }
                if (!empty($get['moreExpensive'])) {
                    $offers->where(DB::raw('ROUND(offers.price * offers.operator_award)'), '>', $get['moreExpensive']);
                }
            }
        );

        if (!empty($get['phoneNumber']) && empty($get['disabled'])) {
            $query->whereDoesntHave(
                $linkedToDeveloper ? 'developer.offers.uisCalls' : 'offers.uisCalls',
                static function (Builder $uisCalls) use ($get) {
                    $uisCalls
                        ->where('offers.active', TrueFalse::True->value)
                        ->where('start_time', '>', DB::raw('CURRENT_TIMESTAMP - INTERVAL offers.uniqueness_period DAY'))
                        ->where('contact_phone_number', $get['phoneNumber'])
                        ->whereIn('status', [
                            UisStatus::Conversation_took_place->value,
                            UisStatus::Conversation_did_not_take_place->value,
                            UisStatus::Transfer_did_not_take_place->value,
                        ]);
                }
            );
        }
    }

    private static function filterByObjectColumns(array $get, Builder $query): void
    {
        $query->whereHas(
            'objects.housingEstate.city',
            static function (Builder $objects) use ($get) {

                // City

                if (!empty($get['city'])) { // TODO: Why we need to use the `empty()` function?
                    $objects->where('housing_estates.city_id', $get['city']);
                }

                // Roominess

                $roominess = [];
                if (!empty($get['roominess'])) {
                    $roominess = $get['roominess'];
                }
                if (!empty($get['duplex'])) {
                    $roominess = array_merge($roominess, Roominess::getDplxValues());
                }
                if ($roominess) {
                    $objects->whereIn('objects.roominess', $roominess);
                }

                // Deadline

                if (!empty($get['deadline'])) {
                    $objects->where(static function (Builder $query) use ($get) {
                        $query->orWhere('objects.done', 1);
                        if ($get['deadline'] === 'thisYear') {
                            $query->orWhere(
                                static fn(Builder $query) =>
                                $query
                                    ->where('objects.deadline_year', LIDOFON_DATE_TIME_MOSCOW->format('Y'))
                                    ->whereNotNull('objects.deadline_quarter')
                            );
                        } elseif ($get['deadline'] !== 'done') {
                            $yearQuarter = explode('-', $get['deadline']);
                            $query->orWhere(
                                static fn (Builder $objects) => $objects
                                    ->whereNotNull(['objects.deadline_year', 'objects.deadline_quarter'])
                                    ->where('objects.deadline_year', '<=', $yearQuarter[0])
                                    ->where('objects.deadline_quarter', '<=', $yearQuarter[1])
                            );
                        }
                    });
                }

                // Finishing

                if (!empty($get['finishing'])) {
                    $objects->whereIn('objects.finishing', $get['finishing']);
                }

                // Price

                if (!empty($get['priceFrom']) && !empty($get['priceTo'])) {
                    $objects->whereBetween('objects.price', [
                        $get['priceFrom'] . FormatHelper::getFixedNumberOfZerosAsString(),
                        $get['priceTo'] . FormatHelper::getFixedNumberOfZerosAsString(),
                    ]);
                }
            }
        );
    }

    private static function filterByTags(array $tags, Builder $query): void
    {
        foreach ($tags as $tag) {
            $query->whereHas('tags',
                // We use `where()` in the loop instead of just `whereIn($tags)`
                // because we need the AND condition rather than the OR condition provided by the `WHERE IN` clause
                static fn (Builder $tagsBuilder) => $tagsBuilder->where('tags.id', $tag)
            );
        }
    }

    private static function filterByDevelopers(array $developers, Builder $query): void
    {
        $query->whereHas('developer',
            static fn (Builder $developerBuilder) => $developerBuilder->whereIn('id', $developers)
        );
    }

    private static function filterByPaymentMethods(array $paymentMethods, Builder $query, bool $linkedToDeveloper): void
    {
        $query->whereHas(
            $linkedToDeveloper ? 'developer.paymentMethods' : 'paymentMethods',
            static fn (Builder $paymentMethodsBuilder) =>
                $paymentMethodsBuilder->whereIn('payment_methods.id', $paymentMethods)
        );
    }
}

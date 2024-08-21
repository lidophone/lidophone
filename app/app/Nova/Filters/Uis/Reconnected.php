<?php

namespace App\Nova\Filters\Uis;

use App\Enums\TrueFalse;
use App\Enums\Uis\UisStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class Reconnected extends Filter
{
    public function name(): string
    {
        return __('Reconnected');
    }

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        $query->whereIn('uis_calls.status', [
            UisStatus::Conversation_did_not_take_place->value,
            UisStatus::Transfer_did_not_take_place->value,
        ]);

        $value = (int) $value;

        if ($value === TrueFalse::True->value) {
            $query
                ->join('uis_calls as uis_calls_2', function (JoinClause $join) {
                    $join
                        ->on('uis_calls.contact_phone_number', '=', 'uis_calls_2.contact_phone_number')
                        ->on('uis_calls.last_leg_employee_id', '=', 'uis_calls_2.last_leg_employee_id')
                        ->where('uis_calls_2.status', UisStatus::Conversation_took_place->value);
                });
        } else {
            $query->whereNotIn('id', function (\Illuminate\Database\Query\Builder $query) {
                $query
                    ->select('uis_calls.id')
                    ->from('uis_calls')
                    ->whereIn('uis_calls.status', [
                        UisStatus::Conversation_did_not_take_place->value,
                        UisStatus::Transfer_did_not_take_place->value,
                    ])
                    ->join('uis_calls as uis_calls_2', function (JoinClause $join) {
                        $join
                            ->on('uis_calls.contact_phone_number', '=', 'uis_calls_2.contact_phone_number')
                            ->on('uis_calls.last_leg_employee_id', '=', 'uis_calls_2.last_leg_employee_id')
                            ->where('uis_calls_2.status', UisStatus::Conversation_took_place->value);
                    });
            });
        }

        return $query;
    }

    public function options(NovaRequest $request): array
    {
        return [
            __('Yes') => TrueFalse::True->value,
            __('No') => TrueFalse::False->value,
        ];
    }
}

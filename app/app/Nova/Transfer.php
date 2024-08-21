<?php

namespace App\Nova;

use App\Enums\Uis\UisStatus;
use App\Helpers\FormatHelper;
use App\Helpers\UisHelper;
use App\Models\Uis\UisCall;
use Idez\DateRangeFilter\DateRangeFilter;
use Idez\DateRangeFilter\Enums\Config;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Transfer extends ResourceCustom
{
    public static string $model = UisCall::class;

    public static $search = ['uis_id'];

    public static array $orderBy = [
        'start_time' => 'desc',
    ];

    public function fields(NovaRequest $request): array
    {
        // [!] CHANGE STYLES FOR "Reconnected" <TH> IN [app/resources/css/nova.css] IF YOU CHANGE THE FIELD ORDER
        return [
            Text::make(__('Employee'))
                ->displayUsing(fn (null $attributeValue, UisCall $model) => $model->firstAnsweredEmployee?->full_name),
            Text::make(__('Phone number'), 'contact_phone_number'),
            Text::make(__('Housing estate'), 'housingEstate.name')
                // ->displayUsing(
                //     fn (?string $attributeValue, UisCall $model) =>
                //         '<span title="'. $model->last_leg_called_phone_number. '">' . ($attributeValue ?: '—') . '</div>')
                // ->asHtml()
                ->withMeta(['textAlign' => 'center']),
            Text::make(__('Developer'), 'developer.name')
                ->withMeta(['textAlign' => 'center']),
            Text::make(__('Status'), 'status', function (int $attributeValue) {
                $name = UisStatus::getName($attributeValue);
                $cssClass = match ($attributeValue) {
                    UisStatus::Client_talking->value => 'client-talking',
                    UisStatus::Conversation_took_place->value => 'conversation-took-place',
                    UisStatus::Conversation_did_not_take_place->value => 'conversation-did-not-take-place',
                    UisStatus::Transfer_did_not_take_place->value => 'transfer-did-not-take-place',
                };
                return '<div class="transfer-status ' . $cssClass .'">' . $name . '</div>';
            })->asHtml(),
            Text::make(__('Reconnected'))
                ->displayUsing(function (null $attributeValue, UisCall $model) {
                    if (in_array($model->status, [
                        UisStatus::Conversation_did_not_take_place->value,
                        UisStatus::Transfer_did_not_take_place->value,
                    ])) {
                        return UisCall::where('contact_phone_number', $model->contact_phone_number)
                            ->where('last_leg_employee_id', $model->last_leg_employee_id)
                            ->where('status', UisStatus::Conversation_took_place)
                            ->exists()
                            ? self::getReconnectedMessage(true)
                            : self::getReconnectedMessage(false);
                    }
                    return '';
                })
                ->asHtml()
                ->withMeta(['textAlign' => 'center', 'compact' => true]),
            Text::make(__('Duration & Recording'))
                ->displayUsing(function (null $attributeValue, UisCall $model) {
                    $callRecords = json_decode($model->call_records, true);
                    $audios = '';
                    foreach ($callRecords as $callRecordHash) {
                        $audios .= '<audio src="' .
                            UisHelper::getCallRecordUrl($model->uis_id, $callRecordHash) .
                            '" controls preload="none"></audio>';
                    }
                    return '<div class="transfer-audio-box">' . $audios . '</div>';
                })
                ->asHtml(),
            Text::make(__('Payment'))
                ->displayUsing(fn (null $attributeValue, UisCall $model) => $model->getOperatorAward(true))
                ->withMeta(['textAlign' => 'center']),
            Text::make(__('Marketplace'), 'marketplace.name'),
            Text::make(__('Date & time'), 'start_time')
                ->displayUsing(fn (string $startTime) => FormatHelper::formatDateTime($startTime))
                ->sortable(),
            Text::make('UIS ID', 'uis_id')
                ->displayUsing(
                    fn (int $uisId) =>
                        '<a href="' . UisHelper::getUrlToCall($uisId) . '" target="_blank" class="link-default">' .
                            $uisId .
                        '</a>'
                )
                ->asHtml(),
            Boolean::make(__('Processed'), 'reparse')->resolveUsing(function (string $attributeValue) {
                return !$attributeValue;
            }),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        $initialDate = LIDOFON_DATE_TIME_MOSCOW->format('Y-m-d');
        return [
            new Filters\Uis\PhoneNumber(Text::make(__('Phone number'), 'contact_phone_number')),
            new DateRangeFilter(__('Date & time'), 'uis_calls.start_time', [
                Config::LOCALE => app()->getLocale(),
                Config::PLACEHOLDER => __('Period'),
                Config::DEFAULT_DATE => [$initialDate, $initialDate],
            ]),
            new Filters\Uis\Employee,
            new Filters\Uis\Status,
            new Filters\Uis\Reconnected,
            new Filters\Developer,
            new Filters\HousingEstate,
            new Filters\Marketplace,
        ];
    }

    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        $query->with('firstAnsweredEmployee');
        $query
            ->select('uis_calls.*') // [!] THIS IS NECESSARY TO CHECK RECONNECTION (SEE [Nova\Filters\Uis\Reconnected])
            ->whereNot('uis_calls.last_leg_employee_id', UisHelper::TEST_DEVELOPER_ID);
        return $query;
    }

    public function authorizedToView(Request $request): bool
    {
        return false;
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return false;
    }

    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }

    public function authorizedToReplicate(Request $request): bool
    {
        return false;
    }

    public static function label(): string
    {
        return __('Transfers');
    }

    private static function getReconnectedMessage(bool $yes): string
    {
        list($status, $cssClass) = $yes
            ? [__('Yes'), 'conversation-took-place']
            : [__('No'), 'conversation-did-not-take-place'];
        return '<div class="transfer-status ' . $cssClass .'">' . $status . '</div>';
    }
}

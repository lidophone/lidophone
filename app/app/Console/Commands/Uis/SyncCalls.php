<?php

namespace App\Console\Commands\Uis;

use App\Enums\TrueFalse;
use App\Enums\Uis\UisStatus;
use App\Events\DailyCompetitionUpdate;
use App\Helpers\UisHelper;
use App\Models\Uis\UisCall;
use App\Notifications\TransferReport;
use DateTimeImmutable;
use DB;
use Illuminate\Notifications\Notifiable;
use PDOException;

/**
 * [!] `start_time` & `finish_time` ARE NOT INCLUSIVE
 */
class SyncCalls extends BaseUisCommand
{
    use Notifiable;

    protected $signature = 'app:uis:sync-calls {--id=}';

    public const DATE_FROM = '2024-01-01 00:00:00';

    private bool $reparsing = false;

    public function handle(): void
    {
        $this->checkSettings();

        $body = $this->getCallsReport(self::getCallsReportParams());
        $this->logAndDieIfThereIsAnError($body);

        foreach ($body['result']['data'] as $item) {
            try {
                DB::beginTransaction();

                $model = self::saveCallsReport($item);

                $body = $this->getCallLegsReport(self::getCallLegsReportParams($model->uis_id));
                if (isset($body['error'])) {
                    // $this->logErrorMessage(print_r($body['error'], true));
                    $model->reparse = TrueFalse::True->value;
                    break;
                } else {
                    self::setCallLegsReportFields($model, $body['result']['data']);
                    self::setFieldsRelatedToOurDb($model);
                    $model->reparse = TrueFalse::False->value;
                }

                if (!$model->save()) {
                    $this->logErrorMessage(
                        self::class . ': Model not saved' . "\n\n" .
                        '*Model*' . "\n\n" .
                        '```' . json_encode($model) . '```'
                    );
                } elseif ($model->status !== UisStatus::Client_talking->value && !$model->notification_sent) {
                    $this->notifyNow(new TransferReport($model));
                    $model->notification_sent = TrueFalse::True->value;
                    $model->save();
                }

                DB::commit();
            } catch (PDOException $e) {
                DB::rollBack();
                throw $e;
            }
        }

        DailyCompetitionUpdate::dispatch();

        $this->printCommandCompletionMessage();
    }

    private function getCallsReportParams(): array
    {
        $params = [
            'date_from' => self::DATE_FROM,
            'date_till' => LIDOFON_DATE_TIME_MOSCOW
                ->modify('+1 month') // Why `+1 month`?: New Year holidays — 10-12 days
                ->format('Y-m-d H:i:s'),
            'include_ongoing_calls' => true,
            'sort' => [
                ['field' => 'start_time'],
            ],
            'filter' => [
                'field' => 'is_transfer',
                'operator' => '=',
                'value' => true,
            ],
            'fields' => [
                'id',
                'start_time',
                'finish_time',
                'call_records',
                'first_answered_employee_id',
                'contact_phone_number',
            ],
        ];

        $id = $this->option('id');

        if (is_null($id)) {
            // Reparse
            $startTime = UisCall::where('reparse', TrueFalse::True->value)->orderBy('start_time')->value('start_time');

            if ($startTime) {
                $this->reparsing = true;
                $params['date_from'] = (new DateTimeImmutable($startTime))
                    ->modify('-1 second')
                    ->format('Y-m-d H:i:s');
            } else {
                /* @var $uisCall UisCall */
                $uisCall = UisCall::whereNull('finish_time')->orderBy('start_time')->first();
                if (
                    $uisCall &&
                    $uisCall->start_time < LIDOFON_DATE_TIME_MOSCOW->modify('-1 hour -1 minute')->format('Y-m-d H:i:s')
                ) {
                    $this->call(self::class, ['--id' => $uisCall->uis_id]);
                    die;
                }
                $startTime = UisCall::whereNull('finish_time')->orderBy('start_time')->value('start_time')
                    ?: UisCall::orderByDesc('start_time')->value('start_time');
                if ($startTime) {
                    $params['date_from'] = (new DateTimeImmutable($startTime))
                        ->modify('-1 hour -1 minute')
                        ->format('Y-m-d H:i:s');
                }
            }
        } elseif ($id = (int) $id) {
            $params['date_from'] = LIDOFON_DATE_TIME_MOSCOW->modify('-1 month')->format('Y-m-d H:i:s');
            $params['filter'] = [
                'field' => 'id',
                'operator' => '=',
                'value' => $id,
            ];
        } else {
            $this->line('The "id" option cannot be empty or equals 0');
            die;
        }

        return $params;
    }

    private function getCallsReport(array $params): array
    {
        $response = $this->client->post(self::BASE_URL, [
            'json' => self::getBasePostRequestOptions('get.calls_report', $params)
        ]);
        return $this->getBody($response);
    }

    private static function saveCallsReport(array $item): UisCall
    {
        $uisId = $item['id'];
        unset($item['id']);
        $item['call_records'] = json_encode($item['call_records']);

        UisCall::updateOrInsert(['uis_id' => $uisId], $item);

        return UisCall::where('uis_id', $uisId)->first();
    }

    private static function getCallLegsReportParams(int $communicationId): array
    {
        return [
            'date_from' => self::DATE_FROM,
            'date_till' => LIDOFON_DATE_TIME_MOSCOW->format('Y-m-d H:i:s'),
            'sort' => [
                ['field' => 'start_time'],
            ],
            'filter' => [
                'field' => 'call_session_id',
                'operator' => '=',
                'value' => $communicationId,
            ],
            'fields' => [
                'called_phone_number',
                'calling_phone_number',
                'employee_id',
                'finish_reason',
            ],
        ];
    }

    private function getCallLegsReport(array $params): array
    {
        $response = $this->client->post(self::BASE_URL, [
            'json' => self::getBasePostRequestOptions('get.call_legs_report', $params)
        ]);
        return $this->getBody($response);
    }

    private static function setCallLegsReportFields(UisCall $model, array $callLegsReportItems): void
    {
        $callLegsReportItems = array_filter($callLegsReportItems, fn ($item) => !is_null($item['employee_id']));

        $firstLeg = array_shift($callLegsReportItems);

        if (!$firstLeg) {
            return;
        }

        if (strlen($firstLeg['called_phone_number']) > strlen($firstLeg['calling_phone_number'])) {
            $finishNumber = $firstLeg['called_phone_number'];
        } else {
            $finishNumber = $firstLeg['calling_phone_number'];
        }

        $lastLeg = array_pop($callLegsReportItems);

        $model->last_leg_employee_id = $lastLeg ? $lastLeg['employee_id'] : null;
        $model->last_leg_called_phone_number = $lastLeg ? $lastLeg['called_phone_number'] : null;
        $model->last_leg_duration = 0;

        if (
            $lastLeg &&
            ($lastLeg['calling_phone_number'] === $finishNumber || $firstLeg['called_phone_number'] === $finishNumber) &&
            $lastLeg['finish_reason'] !== 'subscriber_disconnects'
        ) {
            $callRecords = json_decode($model->call_records, true);
            $callRecordHash = array_pop($callRecords);
            $duration = (int) shell_exec('ffprobe -i ' . // https://stackoverflow.com/a/22243834/4223982
                UisHelper::getCallRecordUrl($model->uis_id, $callRecordHash) .
                ' -show_entries format=duration -v quiet -of csv="p=0"');
            $model->last_leg_duration = $duration;
        }
    }

    private function setFieldsRelatedToOurDb(UisCall $model): void
    {
        $marketplaceId = $housingEstateId = $developerId = $price = $operatorAward = null;

        if (is_null($model->finish_time)) {
            $status = UisStatus::Client_talking->value;
        } elseif (is_null($model->last_leg_duration) || $model->last_leg_duration === 0) {
            $status = UisStatus::Transfer_did_not_take_place->value;
        } elseif ($model->last_leg_duration < UisHelper::CONVERSATION_DURATION_FOR_SUCCESSFUL_TRANSFER) {
            $status = UisStatus::Conversation_did_not_take_place->value;
        } else {
            $status = UisStatus::Conversation_took_place->value;
        }

        if ($model->offer) {
            $marketplaceId = $model->offer->marketplace_id;
            if ($housingEstate = $model->offer->housingEstate) {
                $housingEstateId = $housingEstate->id;
                $developerId = $housingEstate->developer_id;
            } elseif ($developer = $model->offer->developer) {
                $developerId = $developer->id;
            }
            $price = $model->offer->price;
            $operatorAward = $model->offer->operator_award;
        }

        $model->status = $status;
        $model->marketplace_id = $marketplaceId;
        $model->housing_estate_id = $housingEstateId;
        $model->developer_id = $developerId;
        if (!$this->reparsing) {
            $model->price = $price;
            $model->operator_award = $operatorAward;
        }
    }
}

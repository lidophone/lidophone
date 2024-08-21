<?php

namespace App\Models\Uis;

use App\Models\Developer;
use App\Models\HousingEstate;
use App\Models\Marketplace;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Uis\UisCall
 *
 * @property int $id
 * @property int $uis_id
 * @property string $start_time
 * @property string|null $finish_time
 * @property mixed $call_records
 * @property int|null $first_answered_employee_id
 * @property string|null $contact_phone_number
 * @property int|null $last_leg_employee_id
 * @property string|null $last_leg_called_phone_number
 * @property int|null $last_leg_duration
 * @property int|null $status
 * @property int|null $marketplace_id
 * @property int|null $housing_estate_id
 * @property int|null $developer_id
 * @property int|null $price
 * @property float|null $operator_award
 * @property int $reparse
 * @property int $notification_sent
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read Developer|null $developer
 * @property-read \App\Models\Uis\UisEmployee|null $firstAnsweredEmployee
 * @property-read Developer|null $housingEstate
 * @property-read \App\Models\Uis\UisEmployee|null $lastLegEmployee
 * @property-read Marketplace|null $marketplace
 * @property-read Offer|null $offer
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall query()
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereCallRecords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereContactPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereDeveloperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereFinishTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereFirstAnsweredEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereHousingEstateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereLastLegCalledPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereLastLegDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereLastLegEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereMarketplaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereNotificationSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereOperatorAward($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereReparse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereUisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UisCall whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UisCall extends Model
{
    use HasFactory;

    /** @noinspection SpellCheckingInspection */
    public const SOURCES = [
        'callapi',
        'callapi_management_call',
        'callapi_informer_call',
        'callapi_scenario_call',
        'va',
        'call_tracking',
        'dynamic_call_tracking',
        'callout',
        'callback',
        'faxout',
        'sip',
        'consultant',
        'sitephone',
        'lead',
        'retailcrm',
        'amocrm',
        'bitrix',
        'auto_back_call',
        'auto_back_call_by_lost_call', // Not included in the documentation
        'auto_out_calls', // Not included in the documentation
    ];

    public const DIRECTIONS = ['in', 'out'];

    /** @noinspection SpellCheckingInspection */
    public const FINISH_REASONS = [
        'numb_not_exists',
        'incorrect_input',
        'numb_is_inactive',
        'sitephone_is_not_configured',
        'app_is_inactive',
        'numa_in_black_list',
        'no_active_scenario',
        'simple_forwarding_is_not_configured',
        'site_not_exists',
        'call_generator_is_not_configured',
        'add_cdr_timeout',
        'success_finish',
        'api_permission_denied',
        'api_ip_now_allowed',
        'component_is_inactive',
        'employee_not_exists',
        'not_enough_money',
        'platform_not_found',
        'internal_error',
        'incorrect_config',
        'communication_unavailable',
        'subscriber_disconnects',
        'no_operation',
        'scenario_not_found',
        'transfer_disconnects',
        'scenario_disconnects',
        'fax_session_done',
        'no_resources',
        'another_operator_answer',
        'subscriber_busy',
        'subscriber_not_responsible',
        'subscriber_number_problems',
        'operator_answer',
        'locked_numb',
        'call_not_allowed_on_tp',
        'account_not_found',
        'contract_not_found',
        'operator_busy',
        'operator_not_responsible',
        'operator_disconnects',
        'operator_number_problems',
        'timeout',
        'operator_channels_busy',
        'locked_phone',
        'max_in_call_reached',
        'max_out_call_reached',
        'employee_busy',
        'employee_busy_after_call',
        'phone_group_inactive_by_schedule',
        'sip_offline',
        'employee_inactive',
        'employee_inactive_by_schedule',
        'employee_phone_inactive',
        'employee_phone_inactive_by_schedule',
        'action_interval_exceeded',
        'group_phone_inactive',
        'no_operator_confirmation',
        'max_transition_count_exceeded',
        'disconnect_before_call_processing',
        'no_success_subscriber_call',
        'no_success_operator_call',
        'group_inactive_by_schedule',
        'net_lock',
        'fmc_is_disabled',
        'fmc_is_locked',
        'processing_method_not_found',
        'employee_without_phones',
        'group_without_phones',
        'no_operator_cdr_found',
        'in_call_not_allowed',
        'phone_protocol_not_allowed_by_status',
        'employee_status_break',
        'employee_status_do_not_disturb',
        'employee_status_not_at_workplace',
        'employee_status_not_at_work',
        'sip_trunk_is_locked',
        'numa_in_spam_list',
        'numa_dnd_interval',
        'limit_exceeded',
        'no_money',
        'out_call_not_allowed',
        'out_call_not_allowed_by_status',
        'external_call_not_allowed',
        'external_call_not_allowed_by_status',
        'internal_call_not_allowed',
        'internal_call_not_allowed_by_status',
        'call_enqueued',
        'employee_status_auto_out_call',
        'employee_status_available',
        'too_many_identical_incoming_calls',
        'employee_auto_status_do_not_disturb',
        'auto_out_calls_not_allowed_by_status',
    ];

    public function firstAnsweredEmployee(): HasOne
    {
        return $this->hasOne(UisEmployee::class, 'uis_id', 'first_answered_employee_id');
    }

    public function lastLegEmployee(): HasOne
    {
        return $this->hasOne(UisEmployee::class, 'uis_id', 'last_leg_employee_id');
    }

    public function marketplace(): BelongsTo
    {
        return $this->belongsTo(Marketplace::class);
    }

    public function housingEstate(): BelongsTo
    {
        if (is_null($this->housing_estate_id)) {
            return $this->developer();
        } else {
            return $this->belongsTo(HousingEstate::class);
        }
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class, 'developer_id');
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class, 'last_leg_called_phone_number', 'sip_uri');
    }

    public function getOperatorAward(bool $rubleSign = false): string
    {
        return $this->price && $this->operator_award
            ? round($this->price * $this->operator_award) . ($rubleSign ? ' ₽' : '')
            : '';
    }
}

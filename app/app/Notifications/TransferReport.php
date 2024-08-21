<?php

namespace App\Notifications;

use App;
use App\Enums\Uis\UisStatus;
use App\Helpers\UisHelper;
use App\Models\Settings;
use App\Models\Uis\UisCall;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TransferReport extends Notification
{
    use Queueable;

    private const TG_GROUP_TEST = '-4178565496';

    private const ALL_KEYS_OF_TG_GROUPS = [
        self::KEY_OF_MANAGERS_TG_GROUP,
        self::KEY_OF_TRAINEES_TG_GROUP,
    ];

    private const KEY_OF_MANAGERS_TG_GROUP = 'id_of_managers_tg_group';
    private const KEY_OF_TRAINEES_TG_GROUP = 'id_of_trainees_tg_group';

    private string $idOfManagersTgGroup;
    private string $idOfTraineesTgGroup;

    private UisCall $uisCall;

    public function __construct(UisCall $uisCall)
    {
        $this->uisCall = $uisCall;
        if ($this->uisCall->notification_sent) {
            die;
        }
        if (App::environment('local')) {
            $this->idOfManagersTgGroup = self::TG_GROUP_TEST;
        } else {
            $settings = Settings::whereIn('key', self::ALL_KEYS_OF_TG_GROUPS)->get();
            foreach ($settings as $setting) {
                match ($setting->key) {
                    self::KEY_OF_MANAGERS_TG_GROUP => ($this->idOfManagersTgGroup = $setting->value),
                    self::KEY_OF_TRAINEES_TG_GROUP => ($this->idOfTraineesTgGroup = $setting->value),
                };
            }
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['telegram'];
    }

    public function toTelegram(): TelegramMessage
    {
        $status = match ($this->uisCall->status) {
            UisStatus::Conversation_took_place->value => '✅',
            UisStatus::Conversation_did_not_take_place->value => '⛔️',
            UisStatus::Transfer_did_not_take_place->value => '❌',
            null => '???',
        };
        $status .= ' ' . ($this->uisCall->status ? UisStatus::getName($this->uisCall->status) : '???');

        $groups = $this->uisCall->firstAnsweredEmployee->groups->toArray();
        $groups = array_column($groups, 'uis_id');

        if (App::environment('local')) {
            $chatId = self::TG_GROUP_TEST;
        } else {
            $chatId = in_array(UisHelper::TRAINEES_GROUP_ID, $groups)
                ? $this->idOfTraineesTgGroup
                : $this->idOfManagersTgGroup;
        }

        return TelegramMessage::create()
            ->to($chatId)
            // Markdown supported
            ->line(
                '👤 *' . $this->uisCall->firstAnsweredEmployee->full_name . "*\n" .
                '🏙 ' . ($this->uisCall->housingEstate ? $this->uisCall->housingEstate->name : '???') .
                ' (' . ($this->uisCall->developer ? $this->uisCall->developer->name : '???') . ')' .
                (
                    in_array($chatId, [$this->idOfManagersTgGroup, self::TG_GROUP_TEST])
                    ? ' 💰 ' .  $this->uisCall->getOperatorAward(true)
                    : ''
                ) . "\n" .
                '📞 ' . $this->uisCall->contact_phone_number . "\n" .
                $status . $this->getCallRecords() . "\n" .
                $this->getStatusMessage()
            );
    }

    private function getStatusMessage(): string
    {
        return match ($this->uisCall->status) {
            UisStatus::Conversation_took_place->value =>
            __('Call the client back and transfer again to another developer.'),

            UisStatus::Conversation_did_not_take_place->value =>
            __('The conversation between the client and the sales department lasted less than 60 seconds. Listen to the recording, and if necessary, reconnect or transfer to another developer.'),

            UisStatus::Transfer_did_not_take_place->value =>
            __('Call the client back and reconnect the client. If that doesn\'t work, transfer to another developer.'),
        };
    }

    private function getCallRecords(): string
    {
        $urls = '';
        $callRecords = json_decode($this->uisCall->call_records, true);
        $i = 1;
        foreach ($callRecords as $callRecord) {
            $urls .= ' [' . $i . '](' . UisHelper::getCallRecordUrl($this->uisCall->uis_id, $callRecord) .') |';
            $i++;
        }
        $urls = substr($urls, 0, -2);
        return $urls;
    }
}

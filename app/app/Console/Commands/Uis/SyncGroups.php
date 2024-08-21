<?php

namespace App\Console\Commands\Uis;

use App\Models\Uis\UisGroup;

class SyncGroups extends BaseUisCommand
{
    protected $signature = 'app:uis:sync-groups';

    public function handle(): void
    {
        $this->checkSettings();

        $response = $this->client->post(self::BASE_URL, [
            'json' => self::getBasePostRequestOptions('get.group_employees', [
                'fields' => [
                    'id',
                    'name',
                ],
            ])
        ]);

        $body = $this->getBody($response);
        $this->logAndDieIfThereIsAnError($body);
        $items = $body['result']['data'];

        $groupIs = [];

        foreach ($items as $item) {
            /** @var $item array */
            $uisId = $item['id'];
            unset($item['id']);
            UisGroup::updateOrInsert(['uis_id' => $uisId], $item);
            $groupIs[] = $uisId;
        }

        UisGroup::whereNotIn('uis_id', $groupIs)->delete();

        $this->printCommandCompletionMessage();
    }
}

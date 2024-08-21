<?php

namespace App\Console\Commands\Uis;

use App\Models\Uis\UisEmployee;
use App\Models\Uis\UisEmployeeGroup;

class SyncEmployees extends BaseUisCommand
{
    protected $signature = 'app:uis:sync-employees';

    public function handle(): void
    {
        $this->checkSettings();

        $response = $this->client->post(self::BASE_URL, [
            'json' => self::getBasePostRequestOptions('get.employees', [
                'fields' => [
                    'id',
                    'full_name',
                    'groups',
                ],
                'limit' => 10000, // https://uiscom.ru/academiya/spravochnyj-centr/dokumentatsiya-api/data-api#_20
            ])
        ]);

        $body = $this->getBody($response);
        $this->logAndDieIfThereIsAnError($body);
        $items = $body['result']['data'];

        foreach ($items as $item) {
            /** @var $item array */
            $uisId = $item['id'];
            UisEmployee::updateOrInsert(['uis_id' => $uisId], ['full_name' => $item['full_name']]);
            if ($item['groups']) {
                UisEmployeeGroup::where('uis_employees_uis_id', $uisId)->delete();
                $groups = [];
                foreach ($item['groups'] as $group) {
                    $groups[] = [
                        'uis_employees_uis_id' => $uisId,
                        'uis_groups_uis_id' => $group['group_id'],
                    ];
                }
                UisEmployeeGroup::insert($groups);
            }
        }

        $this->printCommandCompletionMessage();
    }
}

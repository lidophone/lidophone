<?php

namespace App\Helpers;

class UisHelper
{
    public const DEVELOPERS_GROUP_ID = 485078;
    public const TRAINEES_GROUP_ID = 559409;
    public const TEST_DEVELOPER_ID = 2460017;
    public const CONVERSATION_DURATION_FOR_SUCCESSFUL_TRANSFER = 60;

    public static function getCallRecordUrl(int $communicationId, string $callRecordHash): string
    {
        return 'https://app.comagic.ru/system/media/talk/' . $communicationId . '/' . $callRecordHash . '/';
    }

    public static function getUrlToCall(int $id): string
    {
        $gridStoreParams = [
            'sort' => [],
            'filter' => [
                [
                    'comparison' => 'eq',
                    'field' => 'id',
                    'type' => 'text',
                    'value' => $id,
                    'columnField' => 'session_id',
                ],
            ],
        ];

        $gridStoreParams['sort'] = json_encode($gridStoreParams['sort']);
        $gridStoreParams['filter'] = json_encode($gridStoreParams['filter']);
        $gridStoreParams = json_encode($gridStoreParams);
        $gridStoreParams = urlencode($gridStoreParams);
        $gridStoreParams = str_replace(['%3A', '%2C'], [':', ','], $gridStoreParams);
        $gridStoreParams = urlencode($gridStoreParams);

        $href = 'https://app.uiscom.ru/#';
        $href .= 'controller.id=%22analytics.call.controller.CallDetailsPage%22';
        $href .= '&controller.params.callSessionId=' . $id;
        $href .= '&controller.params.gridStoreParams=%22' . $gridStoreParams . '%22';

        return $href;
    }
}

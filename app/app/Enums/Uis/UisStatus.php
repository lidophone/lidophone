<?php

namespace App\Enums\Uis;

use App\Enums\Base;

enum UisStatus: int
{
    use Base;

    case Client_talking = 0;
    case Conversation_took_place = 1;
    case Conversation_did_not_take_place = 2;
    case Transfer_did_not_take_place = 3;
}

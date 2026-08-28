<?php
namespace Wpint\WPAPI\Enqueuer\Enum;

use WPINT\Framework\Include\Traits\EnumToArray;

enum EnqueuerScopeEnum : string
{
    use EnumToArray;

    case ADMIN = 'admin';
    case CLIENT = 'client';

}

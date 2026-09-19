<?php
namespace Wpint\WPAPI\Enqueuer\Enum;

use Wpint\Support\Traits\EnumToArray;

enum EnqueuerScopeEnum : string
{
    use EnumToArray;

    case ADMIN = 'admin';
    case CLIENT = 'client';

}

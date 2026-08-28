<?php
namespace Wpint\WPAPI\Hook\Enum;

use WPINT\Framework\Include\Traits\EnumToArray;

enum HookTypeEnum : string
{
    use EnumToArray;

    case ACTION = 'action';
    case FILTER = 'filter';

}

<?php
namespace Wpint\WPAPI\Hook\Enum;

use Wpint\Support\Traits\EnumToArray;

enum HookTypeEnum : string
{
    use EnumToArray;

    case ACTION = 'action';
    case FILTER = 'filter';

}

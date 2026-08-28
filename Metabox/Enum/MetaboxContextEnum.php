<?php
namespace Wpint\WPAPI\Metabox\Enum;

use WPINT\Framework\Include\Traits\EnumToArray;

enum MetaboxContextEnum : string
{
    use EnumToArray;

    case ADVANCED = 'advanced';
    case NORMAL = 'normal';
    case SIDE = 'side';

}

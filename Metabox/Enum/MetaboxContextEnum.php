<?php
namespace Wpint\WPAPI\Metabox\Enum;

use Wpint\Support\Traits\EnumToArray;

enum MetaboxContextEnum : string
{
    use EnumToArray;

    case ADVANCED = 'advanced';
    case NORMAL = 'normal';
    case SIDE = 'side';

}

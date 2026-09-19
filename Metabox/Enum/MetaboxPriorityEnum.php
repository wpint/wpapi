<?php
namespace Wpint\WPAPI\Metabox\Enum;

use Wpint\Support\Traits\EnumToArray;

enum MetaboxPriorityEnum : string
{
    use EnumToArray;

    case DEFAULT = 'default';
    case CORE = 'core';
    case HIGH = 'high';
    case LOW = 'low';

}

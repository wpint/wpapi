<?php
namespace Wpint\WPAPI\Setting\Enum;

/**
 * WordPress option groups are open-ended (a plugin can register a
 * setting under any group string), so this stays a plain constants
 * bag rather than a restrictive enum.
 */
final class OptionGroupEnum
{

    public const GENERAL = 'general';
    public const DISCUSSION = 'discussion';
    public const MEDIA = 'media';
    public const READING = 'reading';
    public const WRITING = 'writing';
    public const OPTIONS = 'options';

}

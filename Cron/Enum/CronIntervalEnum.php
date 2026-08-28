<?php
namespace Wpint\WPAPI\Cron\Enum;

/**
 * WordPress cron intervals are open-ended (plugins register their own
 * via Cron::addCronInterval()), so this stays a plain constants bag
 * of the built-in intervals rather than a restrictive enum.
 */
final class CronIntervalEnum
{

    public const HOURLY = 'hourly';
    public const DAILY = 'daily';
    public const TWICEDAILY = 'twicedaily';
    public const WEEKLY = 'weekly';

}

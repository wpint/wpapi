<?php
namespace Wpint\WPAPI\Tests\Cron;

use PHPUnit\Framework\TestCase;
use Wpint\WPAPI\Cron\Enum\CronIntervalEnum;

/**
 * Regression test for the PSR-4 filename/classname mismatch bug:
 * Cron/Enum/CronIntervalEnum.php used to declare `enum CronInterval`,
 * which the PSR-4 autoloader could never find under the class name
 * `Wpint\WPAPI\Cron\Enum\CronIntervalEnum`. Simply referencing the
 * class here is enough to prove autoloading now resolves it correctly
 * — a mismatch would fatal with "Class not found" before any assertion runs.
 *
 * @covers \Wpint\WPAPI\Cron\Enum\CronIntervalEnum
 */
class CronIntervalTest extends TestCase
{

    public function test_class_autoloads_under_its_own_filename() : void
    {
        $this->assertTrue(class_exists(CronIntervalEnum::class));
    }

    public function test_built_in_interval_constants() : void
    {
        $this->assertSame('hourly', CronIntervalEnum::HOURLY);
        $this->assertSame('daily', CronIntervalEnum::DAILY);
        $this->assertSame('twicedaily', CronIntervalEnum::TWICEDAILY);
        $this->assertSame('weekly', CronIntervalEnum::WEEKLY);
    }

}

<?php
namespace Wpint\WPAPI\Tests\Cron;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use Wpint\WPAPI\Cron\Cron;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\Cron\Cron
 */
class CronTest extends TestCase
{

    public function test_register_wires_the_execute_hook_and_schedules_a_recurring_event() : void
    {
        Functions\expect('add_action')->once()->with('wpint_cleanup', Mockery::type(Closure::class));
        Functions\expect('wp_next_scheduled')->once()->with('wpint_cleanup')->andReturn(false);
        Functions\expect('wp_schedule_event')->once()->with(1000, 'hourly', 'wpint_cleanup', [], false);

        (new Cron())
            ->name('wpint_cleanup')
            ->execute(fn() => null)
            ->start(1000)
            ->every('hourly')
            ->register();
    }

    public function test_start_defaults_to_now_when_never_explicitly_set() : void
    {
        // Regression: $start previously had no fallback and would throw
        // "must not be accessed before initialization" if start() was
        // never called.
        Functions\expect('add_action')->once();
        Functions\expect('wp_next_scheduled')->once()->andReturn(false);
        Functions\expect('wp_schedule_event')->once()->with(Mockery::type('int'), 'daily', 'wpint_job', [], false);

        (new Cron())->name('wpint_job')->execute(fn() => null)->every('daily')->register();
    }

    public function test_single_schedule_uses_wp_schedule_single_event() : void
    {
        Functions\expect('add_action')->once();
        Functions\expect('wp_next_scheduled')->once()->andReturn(false);
        Functions\expect('wp_schedule_single_event')->once()->with(500, 'wpint_once', [], false);

        (new Cron())->name('wpint_once')->execute(fn() => null)->start(500)->isSingle()->register();
    }

    public function test_add_cron_interval_registers_a_cron_schedules_filter() : void
    {
        Functions\expect('add_filter')->once()->with('cron_schedules', Mockery::type(Closure::class));

        $this->assertTrue(Cron::addCronInterval('every_five_minutes', 300, 'Every 5 Minutes'));
    }

}

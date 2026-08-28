<?php
namespace Wpint\WPAPI\Tests\Hook;

use Brain\Monkey\Functions;
use Closure;
use Wpint\WPAPI\Hook\Enum\HookTypeEnum;
use Wpint\WPAPI\Hook\Hook;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\Hook\Hook
 */
class HookTest extends TestCase
{

    public function test_action_type_calls_add_action_with_configured_priority_and_args() : void
    {
        Functions\expect('add_action')->once()->with('save_post', \Mockery::type(Closure::class), 10, 1);

        (new Hook())->name('save_post')->type(HookTypeEnum::ACTION)->callback(fn() => null)->register();
    }

    public function test_filter_type_calls_add_filter_with_configured_priority_and_args() : void
    {
        Functions\expect('add_filter')->once()->with('the_content', \Mockery::type(Closure::class), 20, 2);

        (new Hook())
            ->name('the_content')
            ->type(HookTypeEnum::FILTER)
            ->callback(fn($content) => $content)
            ->priority(20)
            ->acceptedArgs(2)
            ->register();
    }

}

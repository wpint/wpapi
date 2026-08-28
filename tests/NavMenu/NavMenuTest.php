<?php
namespace Wpint\WPAPI\Tests\NavMenu;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use Wpint\WPAPI\NavMenu\NavMenu;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\NavMenu\NavMenu
 */
class NavMenuTest extends TestCase
{

    public function test_register_passes_merged_locations_to_register_nav_menus() : void
    {
        Functions\expect('add_action')
            ->once()
            ->with('after_setup_theme', Mockery::type(Closure::class))
            ->andReturnUsing(fn($hook, $callback) => $callback());

        Functions\expect('register_nav_menus')->once()->with([
            'primary' => 'Primary Menu',
            'footer'  => 'Footer Menu',
        ]);

        (new NavMenu())
            ->location('primary', 'Primary Menu')
            ->locations(['footer' => 'Footer Menu'])
            ->register();
    }

}

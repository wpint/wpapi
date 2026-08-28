<?php
namespace Wpint\WPAPI\Tests\Sidebar;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use Wpint\WPAPI\Sidebar\Sidebar;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\Sidebar\Sidebar
 */
class SidebarTest extends TestCase
{

    public function test_register_passes_expected_args_to_register_sidebar() : void
    {
        Functions\expect('add_action')
            ->once()
            ->with('widgets_init', Mockery::type(Closure::class))
            ->andReturnUsing(fn($hook, $callback) => $callback());

        Functions\expect('register_sidebar')->once()->with(Mockery::on(
            fn(array $args) => $args['id'] === 'footer-widgets'
                && $args['name'] === 'Footer Widgets'
                && $args['before_widget'] === '<section id="%1$s" class="widget %2$s">'
                && $args['after_title'] === '</h2>'
        ));

        (new Sidebar())->name('Footer Widgets')->id('footer-widgets')->register();
    }

}

<?php
namespace Wpint\WPAPI\Tests\Shortcode;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use Wpint\WPAPI\Shortcode\Shortcode;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\Shortcode\Shortcode
 */
class ShortcodeTest extends TestCase
{

    public function test_register_wires_add_shortcode() : void
    {
        Functions\expect('add_shortcode')->once()->with('wpint_year', Mockery::type(Closure::class));

        (new Shortcode())->tag('wpint_year')->callback(fn() => date('Y'))->register();
    }

}

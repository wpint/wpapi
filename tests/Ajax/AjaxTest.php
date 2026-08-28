<?php
namespace Wpint\WPAPI\Tests\Ajax;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use Wpint\WPAPI\Ajax\Ajax;
use Wpint\WPAPI\Tests\TestCase;

/**
 * The handler closure itself is intentionally never invoked here — it
 * calls through to the external Wpint\Support\CallbackResolver, which
 * is already alias-mocked once in Support/RegistrableTest.php and can't
 * be redefined again in the same process. Hook wiring is asserted
 * structurally instead.
 *
 * @covers \Wpint\WPAPI\Ajax\Ajax
 */
class AjaxTest extends TestCase
{

    public function test_register_wires_only_the_logged_in_hook_by_default() : void
    {
        Functions\expect('add_action')->once()->with('wp_ajax_wpint_refresh', Mockery::type(Closure::class));

        (new Ajax())->action('wpint_refresh')->callback(fn() => null)->register();
    }

    public function test_public_also_wires_the_nopriv_hook() : void
    {
        Functions\expect('add_action')->once()->with('wp_ajax_wpint_refresh', Mockery::type(Closure::class));
        Functions\expect('add_action')->once()->with('wp_ajax_nopriv_wpint_refresh', Mockery::type(Closure::class));

        (new Ajax())->action('wpint_refresh')->callback(fn() => null)->public()->register();
    }

}

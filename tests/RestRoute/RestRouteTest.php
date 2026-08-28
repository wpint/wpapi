<?php
namespace Wpint\WPAPI\Tests\RestRoute;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use Wpint\WPAPI\RestRoute\RestRoute;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\RestRoute\RestRoute
 */
class RestRouteTest extends TestCase
{

    public function test_permission_callback_defaults_to_deny_when_never_set() : void
    {
        // Regression: routes must never silently default to __return_true.
        Functions\expect('add_action')
            ->once()
            ->with('rest_api_init', Mockery::type(Closure::class))
            ->andReturnUsing(fn($hook, $callback) => $callback());

        Functions\expect('register_rest_route')->once()->with('wpint/v1', '/books', Mockery::on(
            fn(array $args) => $args['permission_callback'] === '__return_false'
        ));

        (new RestRoute())->namespace('wpint/v1')->route('/books')->callback(fn() => null)->register();
    }

    public function test_public_explicitly_opts_in_to_return_true() : void
    {
        Functions\expect('add_action')->once()->andReturnUsing(fn($hook, $cb) => $cb());
        Functions\expect('register_rest_route')->once()->with('wpint/v1', '/books', Mockery::on(
            fn(array $args) => $args['permission_callback'] === '__return_true'
        ));

        (new RestRoute())->namespace('wpint/v1')->route('/books')->callback(fn() => null)->public()->register();
    }

    public function test_explicit_permission_callback_is_used_as_is() : void
    {
        Functions\expect('add_action')->once()->andReturnUsing(fn($hook, $cb) => $cb());

        $permission = fn() => current_user_can('edit_posts');

        Functions\expect('register_rest_route')->once()->with('wpint/v1', '/books', Mockery::on(
            fn(array $args) => $args['permission_callback'] === $permission
        ));

        (new RestRoute())->namespace('wpint/v1')->route('/books')->callback(fn() => null)->permissionCallback($permission)->register();
    }

}

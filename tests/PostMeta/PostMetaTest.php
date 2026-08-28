<?php
namespace Wpint\WPAPI\Tests\PostMeta;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use Wpint\WPAPI\PostMeta\PostMeta;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\PostMeta\PostMeta
 */
class PostMetaTest extends TestCase
{

    public function test_register_passes_expected_args_to_register_post_meta() : void
    {
        Functions\expect('add_action')
            ->once()
            ->with('init', Mockery::type(Closure::class))
            ->andReturnUsing(fn($hook, $callback) => $callback());

        Functions\expect('register_post_meta')->once()->with('book', 'isbn', Mockery::on(
            fn(array $args) => $args['type'] === 'string'
                && $args['single'] === true
                && $args['sanitize_callback'] === 'sanitize_text_field'
                && $args['show_in_rest'] === true
        ));

        (new PostMeta())
            ->postType('book')
            ->key('isbn')
            ->single(true)
            ->showInRest(true)
            ->register();
    }

    public function test_custom_sanitize_callback_overrides_the_default() : void
    {
        Functions\expect('add_action')->once()->andReturnUsing(fn($hook, $cb) => $cb());

        $sanitizer = fn($value) => strtoupper($value);

        Functions\expect('register_post_meta')->once()->with('book', 'isbn', Mockery::on(
            fn(array $args) => $args['sanitize_callback'] === $sanitizer
        ));

        (new PostMeta())->postType('book')->key('isbn')->sanitizeCallback($sanitizer)->register();
    }

}

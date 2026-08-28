<?php
namespace Wpint\WPAPI\Tests\PostType;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use Wpint\WPAPI\PostType\Enum\PostTypeSupportsEnum;
use Wpint\WPAPI\PostType\PostType;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\PostType\PostType
 */
class PostTypeTest extends TestCase
{

    protected function setUp() : void
    {
        parent::setUp();
        Functions\when('config')->justReturn('');
        Functions\when('__')->returnArg(1);
    }

    public function test_register_passes_the_expected_args_to_register_post_type() : void
    {
        Functions\expect('add_action')
            ->once()
            ->with('init', Mockery::type(Closure::class))
            ->andReturnUsing(fn($hook, $callback) => $callback());

        Functions\expect('register_post_type')
            ->once()
            ->with('book', Mockery::on(function (array $args) {
                return $args['show_ui'] === true
                    && $args['has_archive'] === true
                    && ! array_key_exists('singularName', $args)
                    && ! array_key_exists('name', $args);
            }));

        (new PostType())
            ->name('book')
            ->showUI(true)
            ->hasArchive(true)
            ->register();
    }

    public function test_show_ui_sets_show_ui_not_publicly_queryable() : void
    {
        // Regression for the bug where showUI() wrote to $publicly_queryable.
        Functions\expect('add_action')->once()->andReturnUsing(fn($hook, $cb) => $cb());
        Functions\expect('register_post_type')->once()->with('book', Mockery::on(
            fn(array $args) => ($args['show_ui'] ?? null) === true && ! array_key_exists('publicly_queryable', $args)
        ));

        (new PostType())->name('book')->showUI(true)->register();
    }

    public function test_edit_link_sets_edit_link_not_builtin() : void
    {
        // Regression for the bug where editLink() wrote to $_builtin.
        Functions\expect('add_action')->once()->andReturnUsing(fn($hook, $cb) => $cb());
        Functions\expect('register_post_type')->once()->with('book', Mockery::on(
            fn(array $args) => ($args['_edit_link'] ?? null) === 'custom.php?post=%d' && ! array_key_exists('_builtin', $args)
        ));

        (new PostType())->name('book')->editLink('custom.php?post=%d')->register();
    }

    public function test_supports_uses_real_wordpress_supports_strings() : void
    {
        // Regression for PostTypeSupportsEnum previously holding capability strings.
        Functions\expect('add_action')->once()->andReturnUsing(fn($hook, $cb) => $cb());
        Functions\expect('register_post_type')->once()->with('book', Mockery::on(
            fn(array $args) => $args['supports'] === ['title', 'editor', 'thumbnail']
        ));

        (new PostType())
            ->name('book')
            ->supports(PostTypeSupportsEnum::TITLE, PostTypeSupportsEnum::EDITOR, PostTypeSupportsEnum::THUMBNAIL)
            ->register();
    }

}

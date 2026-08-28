<?php
namespace Wpint\WPAPI\Tests\Taxonomy;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use Wpint\WPAPI\Taxonomy\Taxonomy;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\Taxonomy\Taxonomy
 */
class TaxonomyTest extends TestCase
{

    protected function setUp() : void
    {
        parent::setUp();
        Functions\when('config')->justReturn('');
        Functions\when('__')->returnArg(1);
        Functions\when('_x')->returnArg(1);
    }

    public function test_register_without_calling_post_type_does_not_throw() : void
    {
        // Regression: $postTypes previously had no default, and register()
        // reads it directly — this used to fatal with "must not be
        // accessed before initialization" when postType() was never called.
        Functions\expect('add_action')
            ->once()
            ->with('init', Mockery::type(Closure::class))
            ->andReturnUsing(fn($hook, $callback) => $callback());

        Functions\expect('register_taxonomy')
            ->once()
            ->with('genre', [], Mockery::type('array'));

        (new Taxonomy())->name('genre')->register();
    }

    public function test_get_slug_without_calling_slug_does_not_throw() : void
    {
        // Regression: getSlug() previously read $this->slug directly.
        $taxonomy = (new Taxonomy())->name('genre');

        $this->assertSame('genre', $taxonomy->getSlug());
    }

    public function test_capabilities_are_included_in_args_despite_being_private() : void
    {
        Functions\expect('add_action')->once()->andReturnUsing(fn($hook, $cb) => $cb());
        Functions\expect('register_taxonomy')->once()->with('genre', ['book'], Mockery::on(
            fn(array $args) => ($args['capabilities'] ?? null) === ['manage_terms']
        ));

        (new Taxonomy())
            ->name('genre')
            ->postType('book')
            ->capabilities(\Wpint\WPAPI\Taxonomy\Enum\TaxonomyCapabilitiesEnum::MANAGE_TERMS)
            ->register();
    }

}

<?php
namespace Wpint\WPAPI\Tests\Role;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use stdClass;
use Wpint\WPAPI\Role\Role;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\Role\Role
 */
class RoleTest extends TestCase
{

    public function test_register_adds_a_new_role_when_it_does_not_exist_yet() : void
    {
        Functions\expect('add_action')
            ->once()
            ->with('init', Mockery::type(Closure::class))
            ->andReturnUsing(fn($hook, $callback) => $callback());

        Functions\expect('get_role')->once()->with('book_editor')->andReturn(null);
        Functions\expect('add_role')->once()->with('book_editor', 'Book Editor', ['edit_books' => true]);

        (new Role())
            ->name('book_editor')
            ->displayName('Book Editor')
            ->capabilities('edit_books')
            ->register();
    }

    public function test_extends_seeds_capabilities_from_the_existing_role() : void
    {
        Functions\expect('add_action')->once()->andReturnUsing(fn($hook, $cb) => $cb());

        $existing = new stdClass();
        $existing->capabilities = ['read' => true];

        Functions\expect('get_role')->twice()->andReturnUsing(
            fn($role) => $role === 'editor' ? $existing : null
        );

        Functions\expect('add_role')->once()->with('book_editor', 'book_editor', [
            'read'       => true,
            'edit_books' => true,
        ]);

        (new Role())
            ->name('book_editor')
            ->extends('editor')
            ->capabilities('edit_books')
            ->register();
    }

    public function test_register_adds_capability_to_an_existing_role_instead_of_recreating_it() : void
    {
        Functions\expect('add_action')->once()->andReturnUsing(fn($hook, $cb) => $cb());

        $existingRole = Mockery::mock();
        $existingRole->shouldReceive('add_cap')->once()->with('edit_books');

        Functions\expect('get_role')->once()->with('book_editor')->andReturn($existingRole);
        Functions\expect('add_role')->never();

        (new Role())->name('book_editor')->capabilities('edit_books')->register();
    }

}

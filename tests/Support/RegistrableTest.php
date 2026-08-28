<?php
namespace Wpint\WPAPI\Tests\Support;

use Mockery;
use Wpint\WPAPI\Support\Registrable;
use Wpint\WPAPI\Tests\TestCase;

class RegistrableStub extends Registrable
{
    public string $name;
    public ?string $description = null;
    public string $neverInitialized;

    public function register() : void
    {
    }

    public function exposeBuildArgs(array $whitelist) : array
    {
        return $this->buildArgs($whitelist);
    }

    public function exposeProp(string $name, mixed $default = null) : mixed
    {
        return $this->prop($name, $default);
    }

    public function exposeResolveCallback($callback, array $args = [], bool $useKeyedArgs = true) : mixed
    {
        return $this->resolveCallback($callback, $args, $useKeyedArgs);
    }
}

/**
 * @covers \Wpint\WPAPI\Support\Registrable
 */
class RegistrableTest extends TestCase
{

    public function test_build_args_restricts_to_whitelist_and_drops_nulls() : void
    {
        $stub = new RegistrableStub();
        $stub->name = 'demo';
        $stub->description = null;

        $args = $stub->exposeBuildArgs(['name', 'description', 'neverInitialized']);

        $this->assertSame(['name' => 'demo'], $args);
    }

    public function test_build_args_never_throws_on_uninitialized_typed_property() : void
    {
        $stub = new RegistrableStub();
        $stub->name = 'demo';

        // Regression: get_object_vars() called from inside the declaring
        // class must silently omit $neverInitialized, not throw.
        $args = $stub->exposeBuildArgs(['name', 'neverInitialized']);

        $this->assertSame(['name' => 'demo'], $args);
    }

    public function test_prop_returns_default_for_uninitialized_typed_property() : void
    {
        $stub = new RegistrableStub();

        $this->assertSame('fallback', $stub->exposeProp('neverInitialized', 'fallback'));
    }

    public function test_prop_returns_the_actual_value_once_set() : void
    {
        $stub = new RegistrableStub();
        $stub->name = 'demo';

        $this->assertSame('demo', $stub->exposeProp('name', 'fallback'));
    }

    public function test_resolve_callback_delegates_to_callback_resolver() : void
    {
        $resolver = Mockery::mock('alias:Wpint\Support\CallbackResolver');
        $resolver->shouldReceive('call')
            ->once()
            ->with('my_callback', ['a' => 1], false)
            ->andReturn('resolved');

        $stub = new RegistrableStub();

        $this->assertSame('resolved', $stub->exposeResolveCallback('my_callback', ['a' => 1], false));
    }

}

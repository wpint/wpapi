<?php
namespace Wpint\WPAPI\Support;

use Closure;
use Wpint\Contracts\Hook\HookContract;
use Wpint\Support\CallbackResolver;

/**
 * Base class for every fluent WPAPI object.
 *
 * Concrete classes each defer registration into WordPress in a different
 * shape (an `init`-wrapped call, a direct `add_action`, two hooks at once,
 * ...), so `register()` is intentionally left abstract rather than forced
 * into a single template method.
 *
 * @see \Wpint\WPAPI\Support\Registrable
 */
abstract class Registrable implements HookContract
{

    /**
     * Register the object against WordPress.
     *
     * @return void
     */
    abstract public function register(): void;

    /**
     * Build a WP core args array from this object's own properties,
     * restricted to a whitelist of real argument keys and stripped of
     * properties that were never set.
     *
     * Reading properties via get_object_vars() from inside the declaring
     * class silently omits uninitialized typed properties instead of
     * throwing, so this is safe to call even when only some fluent
     * setters were used.
     *
     * Scope caveat: get_object_vars() here runs in Registrable's own
     * scope, so it can only ever see PUBLIC properties of the calling
     * subclass (private/protected properties declared on the subclass
     * are invisible to a parent class's scope in PHP). Whitelist only
     * public properties here; append private ones manually in the
     * subclass's own getArgs()-style method instead.
     *
     * @param string[] $whitelist
     * @return array
     */
    protected function buildArgs(array $whitelist): array
    {
        $vars = array_intersect_key(get_object_vars($this), array_flip($whitelist));

        return array_filter($vars, fn($value) => $value !== null);
    }

    /**
     * Read a property that may not have been initialized yet, without
     * triggering PHP's "must not be accessed before initialization" error.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    protected function prop(string $name, mixed $default = null): mixed
    {
        return isset($this->{$name}) ? $this->{$name} : $default;
    }

    /**
     * Resolve and invoke a Closure|string|array callback via CallbackResolver.
     *
     * @param Closure|string|array $callback
     * @param array $args
     * @param bool $useKeyedArgs
     * @return mixed
     */
    protected function resolveCallback(Closure|string|array $callback, array $args = [], bool $useKeyedArgs = true): mixed
    {
        return CallbackResolver::call($callback, $args, $useKeyedArgs);
    }

}

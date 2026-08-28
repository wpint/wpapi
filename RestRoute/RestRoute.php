<?php
namespace Wpint\WPAPI\RestRoute;

use Wpint\WPAPI\Support\Registrable;
use Closure;

/**
 * Fluent wrapper around register_rest_route().
 *
 * Security default: if permissionCallback() is never called, register()
 * denies every request (__return_false) rather than falling back to the
 * common insecure __return_true footgun. Call public() to explicitly
 * opt in to a publicly accessible route.
 *
 * @method \Wpint\WPAPI\RestRoute\RestRoute namespace()
 * @method \Wpint\WPAPI\RestRoute\RestRoute route()
 * @method \Wpint\WPAPI\RestRoute\RestRoute methods()
 * @method \Wpint\WPAPI\RestRoute\RestRoute callback()
 * @method \Wpint\WPAPI\RestRoute\RestRoute permissionCallback()
 * @method \Wpint\WPAPI\RestRoute\RestRoute args()
 * @method \Wpint\WPAPI\RestRoute\RestRoute public()
 * @method void register()
 *
 * @see \Wpint\WPAPI\RestRoute\RestRoute
 */
class RestRoute extends Registrable
{

    /**
     * $namespace
     *
     * @var string
     */
    private string $namespace;

    /**
     * $route
     *
     * @var string
     */
    private string $route;

    /**
     * $methods
     *
     * @var string|array
     */
    private string|array $methods = 'GET';

    /**
     * $callback
     *
     * @var callable
     */
    private $callback;

    /**
     * $permissionCallback
     *
     * @var callable|null
     */
    private $permissionCallback = null;

    /**
     * $args
     *
     * @var array
     */
    private array $args = [];

    /**
     * Register the REST route
     *
     * @return void
     */
    public function register() : void
    {
        add_action('rest_api_init', function()
        {
            register_rest_route($this->namespace, $this->route, [
                'methods'             => $this->methods,
                'callback'            => $this->callback,
                'permission_callback' => $this->permissionCallback ?? '__return_false',
                'args'                => $this->args,
            ]);
        });
    }

    /**
     * set $namespace
     *
     * @param string $namespace
     * @return self
     */
    public function namespace(string $namespace) : self
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * set $route
     *
     * @param string $route
     * @return self
     */
    public function route(string $route) : self
    {
        $this->route = $route;
        return $this;
    }

    /**
     * set $methods
     *
     * @param string|array $methods
     * @return self
     */
    public function methods(string|array $methods) : self
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * set $callback
     *
     * @param callable $callback
     * @return self
     */
    public function callback(callable $callback) : self
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * set $permissionCallback
     *
     * @param callable $callback
     * @return self
     */
    public function permissionCallback(callable $callback) : self
    {
        $this->permissionCallback = $callback;
        return $this;
    }

    /**
     * set $args (per-parameter validate/sanitize schema)
     *
     * @param array $args
     * @return self
     */
    public function args(array $args) : self
    {
        $this->args = $args;
        return $this;
    }

    /**
     * Explicitly opt this route in to being publicly accessible,
     * bypassing the deny-by-default permission callback.
     *
     * @return self
     */
    public function public() : self
    {
        $this->permissionCallback = '__return_true';
        return $this;
    }

}

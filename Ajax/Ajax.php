<?php
namespace Wpint\WPAPI\Ajax;

use Wpint\WPAPI\Support\Registrable;
use Closure;

/**
 * Fluent wrapper around wp_ajax_{action} / wp_ajax_nopriv_{action}.
 *
 * Security default: a check_ajax_referer() nonce check runs automatically
 * before the callback, and the handler is only wired up for logged-in
 * users unless public() is called. Opt out of the nonce check explicitly
 * via withoutNonceCheck() rather than it being opt-in.
 *
 * @method \Wpint\WPAPI\Ajax\Ajax action()
 * @method \Wpint\WPAPI\Ajax\Ajax callback()
 * @method \Wpint\WPAPI\Ajax\Ajax public()
 * @method \Wpint\WPAPI\Ajax\Ajax capability()
 * @method \Wpint\WPAPI\Ajax\Ajax nonce()
 * @method \Wpint\WPAPI\Ajax\Ajax withoutNonceCheck()
 * @method void register()
 *
 * @see \Wpint\WPAPI\Ajax\Ajax
 */
class Ajax extends Registrable
{

    /**
     * $action
     *
     * @var string
     */
    private string $action;

    /**
     * $callback
     *
     * @var Closure|string|array
     */
    private Closure|string|array $callback;

    /**
     * Whether to also register wp_ajax_nopriv_{action} for logged-out users.
     *
     * @var bool
     */
    private bool $nopriv = false;

    /**
     * $capability
     *
     * @var string|null
     */
    private ?string $capability = null;

    /**
     * Nonce action name used by check_ajax_referer(). Defaults to $action.
     *
     * @var string
     */
    private string $nonceAction;

    /**
     * $checkNonce
     *
     * @var bool
     */
    private bool $checkNonce = true;

    /**
     * Register the AJAX handler(s)
     *
     * @return void
     */
    public function register() : void
    {
        $handler = function()
        {
            if ($this->checkNonce)
            {
                check_ajax_referer($this->prop('nonceAction', $this->action));
            }

            if ($this->capability && ! current_user_can($this->capability))
            {
                wp_send_json_error('forbidden', 403);
            }

            return $this->resolveCallback($this->callback);
        };

        add_action('wp_ajax_' . $this->action, $handler);

        if ($this->nopriv)
        {
            add_action('wp_ajax_nopriv_' . $this->action, $handler);
        }
    }

    /**
     * set $action
     *
     * @param string $action
     * @return self
     */
    public function action(string $action) : self
    {
        $this->action = $action;
        return $this;
    }

    /**
     * set $callback
     *
     * @param Closure|string|array $callback
     * @return self
     */
    public function callback(Closure|string|array $callback) : self
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * Allow logged-out users to reach this handler too
     * (registers wp_ajax_nopriv_{action} alongside wp_ajax_{action}).
     *
     * @param bool $nopriv
     * @return self
     */
    public function public(bool $nopriv = true) : self
    {
        $this->nopriv = $nopriv;
        return $this;
    }

    /**
     * set $capability
     *
     * @param string $capability
     * @return self
     */
    public function capability(string $capability) : self
    {
        $this->capability = $capability;
        return $this;
    }

    /**
     * set the nonce action name checked by check_ajax_referer()
     *
     * @param string $nonceAction
     * @return self
     */
    public function nonce(string $nonceAction) : self
    {
        $this->nonceAction = $nonceAction;
        return $this;
    }

    /**
     * Opt out of the automatic check_ajax_referer() nonce verification.
     * Only do this for handlers that genuinely need no CSRF protection.
     *
     * @return self
     */
    public function withoutNonceCheck() : self
    {
        $this->checkNonce = false;
        return $this;
    }

}

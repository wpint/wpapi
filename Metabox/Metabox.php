<?php
namespace Wpint\WPAPI\Metabox;

use Wpint\WPAPI\Metabox\Enum\MetaboxPriorityEnum;
use Wpint\WPAPI\Metabox\Enum\MetaboxContextEnum;
use Wpint\WPAPI\Support\Registrable;
use Closure;

/**
 * @method \Wpint\WPAPI\Metabox\Metabox id()
 * @method \Wpint\WPAPI\Metabox\Metabox title()
 * @method \Wpint\WPAPI\Metabox\Metabox screen()
 * @method \Wpint\WPAPI\Metabox\Metabox callback()
 * @method \Wpint\WPAPI\Metabox\Metabox context()
 * @method \Wpint\WPAPI\Metabox\Metabox priority()
 * @method \Wpint\WPAPI\Metabox\Metabox args()
 * @method \Wpint\WPAPI\Metabox\Metabox metaKey()
 * @method \Wpint\WPAPI\Metabox\Metabox postKey()
 * @method \Wpint\WPAPI\Metabox\Metabox nonceAction()
 * @method \Wpint\WPAPI\Metabox\Metabox sanitizeCallback()
 * @method void remove()
 * @method void register()
 *
 * @see \Wpint\WPAPI\Metabox\Metabox
 */
class Metabox extends Registrable
{

    /**
     * $id
     *
     * @var string
     */
    private string $id;

    /**
     * $title
     *
     * @var string
     */
    private string $title;

    /**
     * $screens
     *
     * @var array
     */
    private array $screens;

    /**
     * $callback
     *
     * @var array|string|Closure
     */
    private array|string|Closure $callback;

    /**
     * $metaKey
     *
     * @var string
     */
    private string $metaKey;

    /**
     * $postKey
     *
     * @var string
     */
    private string $postKey;

    /**
     * $context
     *
     * @var string|MetaboxContextEnum
     */
    private string|MetaboxContextEnum $context = MetaboxContextEnum::ADVANCED;

    /**
     * $priority
     *
     * @var string|MetaboxPriorityEnum
     */
    private string|MetaboxPriorityEnum $priority = MetaboxPriorityEnum::DEFAULT;

    /**
     * $args
     *
     * @var array
     */
    private array $args = [];

    /**
     * Name of the action passed to wp_nonce_field()/wp_verify_nonce().
     * Defaults to "{$id}_wpint_metabox" when not explicitly set.
     *
     * @var string
     */
    private string $nonceAction;

    /**
     * Callable used to sanitize the posted value before it's saved via
     * update_post_meta(). Defaults to 'sanitize_text_field'.
     *
     * @var callable|string
     */
    private $sanitizeCallback = 'sanitize_text_field';

    /**
     * Register metabox
     *
     * @return void
     */
    public function register() : void
    {

        add_action( 'add_meta_boxes', function()
        {
            foreach ( $this->screens as $screen )
            {
                add_meta_box(
                    $this->id,
                    $this->title,
                    function($post, $props)
                    {
                        $result = $this->resolveCallback($this->callback, ['post' => $post, 'args' => $props['args']], false);
                        wp_nonce_field($this->getNonceAction(), $this->getNonceFieldName(), false);
                        return $result;
                    },
                    $screen,
                    $this->context,
                    $this->priority,
                    $this->args
                );
            }
        } );

        add_action( 'save_post', function($post_id)
        {
            // Never persist meta while WordPress is doing an autosave request.
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

            // Only act on posts of a type this metabox is actually registered for.
            if ( ! in_array( get_post_type($post_id), $this->screens, true ) ) return;

            // CSRF protection: the metabox render callback must have emitted a valid nonce.
            $nonceField = $this->getNonceFieldName();
            if (
                ! isset($_POST[$nonceField])
                || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[$nonceField] ) ), $this->getNonceAction() )
            ) return;

            // Authorization: only users allowed to edit this specific post may write to it.
            $postType = get_post_type_object( get_post_type($post_id) );
            if ( ! $postType || ! current_user_can( $postType->cap->edit_post, $post_id ) ) return;

            $postKey = $this->prop('postKey', $this->metaKey);

            if ( array_key_exists( $postKey, $_POST ) ) {
                $sanitized = call_user_func( $this->sanitizeCallback, wp_unslash( $_POST[$postKey] ) );

                update_post_meta(
                    $post_id,
                    $this->metaKey,
                    $sanitized
                );
            }
        } );
    }

    /**
     * set $id
     *
     * @param string $id
     * @return self
     */
    public function id(string $id) : self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * set $title
     *
     * @param string $title
     * @return self
     */
    public function title(string $title) : self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * set $screens
     *
     * @param [type] ...$screen
     * @return self
     */
    public function screen(...$screen) : self
    {
        $this->screens = $screen;
        return $this;
    }

    /**
     * set $callback
     *
     * @param array|string|Closure $callback
     * @return self
     */
    public function callback(array|string|Closure $callback) : self
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * set $context
     *
     * @param string|MetaboxContextEnum $context
     * @return self
     */
    public function context(string|MetaboxContextEnum $context) : self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * set $priority
     *
     * @param string|MetaboxPriorityEnum $priority
     * @return self
     */
    public function priority(string|MetaboxPriorityEnum $priority) : self
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * set $args
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
     * set $metaKey
     *
     * @param string $metaKey
     * @return self
     */
    public function metaKey(string $metaKey) : self
    {
        $this->metaKey = $metaKey;
        return $this;
    }

    /**
     * set $postKey
     *
     * @param string $postKey
     * @return self
     */
    public function postKey(string $postKey) : self
    {
        $this->postKey = $postKey;
        return $this;
    }

    /**
     * set $nonceAction
     *
     * @param string $action
     * @return self
     */
    public function nonceAction(string $action) : self
    {
        $this->nonceAction = $action;
        return $this;
    }

    /**
     * set $sanitizeCallback
     *
     * @param callable $callback
     * @return self
     */
    public function sanitizeCallback(callable $callback) : self
    {
        $this->sanitizeCallback = $callback;
        return $this;
    }

    /**
     * Remove the meta box.
     *
     * @return void
     */
    public function remove() : void
    {
        remove_meta_box( $this->id, $this->screens, $this->context);
    }

    /**
     * get the nonce action, falling back to a value derived from $id.
     *
     * @return string
     */
    private function getNonceAction() : string
    {
        return $this->prop('nonceAction', "{$this->id}_wpint_metabox");
    }

    /**
     * get the nonce hidden field name.
     *
     * @return string
     */
    private function getNonceFieldName() : string
    {
        return "{$this->id}_wpint_nonce";
    }

}

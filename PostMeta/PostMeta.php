<?php
namespace Wpint\WPAPI\PostMeta;

use Wpint\WPAPI\Support\Registrable;

/**
 * Fluent wrapper around register_post_meta().
 *
 * @method \Wpint\WPAPI\PostMeta\PostMeta postType()
 * @method \Wpint\WPAPI\PostMeta\PostMeta key()
 * @method \Wpint\WPAPI\PostMeta\PostMeta type()
 * @method \Wpint\WPAPI\PostMeta\PostMeta description()
 * @method \Wpint\WPAPI\PostMeta\PostMeta single()
 * @method \Wpint\WPAPI\PostMeta\PostMeta default()
 * @method \Wpint\WPAPI\PostMeta\PostMeta sanitizeCallback()
 * @method \Wpint\WPAPI\PostMeta\PostMeta authCallback()
 * @method \Wpint\WPAPI\PostMeta\PostMeta showInRest()
 * @method \Wpint\WPAPI\PostMeta\PostMeta revisionsEnabled()
 * @method void register()
 *
 * @see \Wpint\WPAPI\PostMeta\PostMeta
 */
class PostMeta extends Registrable
{

    /**
     * $postType
     *
     * @var string
     */
    private string $postType;

    /**
     * $key
     *
     * @var string
     */
    private string $key;

    /**
     * $type
     *
     * @var string
     */
    private string $type = 'string';

    /**
     * $description
     *
     * @var string
     */
    private string $description = '';

    /**
     * $single
     *
     * @var bool
     */
    private bool $single = true;

    /**
     * $default
     *
     * @var mixed
     */
    private mixed $default = null;

    /**
     * Sanitizer applied to the value before it's saved. WordPress core
     * applies none by default, which is a real risk once show_in_rest
     * is enabled — this defaults to 'sanitize_text_field'.
     *
     * @var callable|string
     */
    private $sanitizeCallback = 'sanitize_text_field';

    /**
     * $authCallback
     *
     * @var callable|null
     */
    private $authCallback = null;

    /**
     * $showInRest
     *
     * @var bool|array
     */
    private bool|array $showInRest = false;

    /**
     * $revisionsEnabled
     *
     * @var bool
     */
    private bool $revisionsEnabled = false;

    /**
     * Register the post meta
     *
     * @return void
     */
    public function register() : void
    {
        add_action('init', function()
        {
            register_post_meta($this->postType, $this->key, array_filter([
                'type'              => $this->type,
                'description'       => $this->description,
                'single'            => $this->single,
                'default'           => $this->default,
                'sanitize_callback' => $this->sanitizeCallback,
                'auth_callback'     => $this->authCallback,
                'show_in_rest'      => $this->showInRest,
                'revisions_enabled' => $this->revisionsEnabled,
            ], fn($value) => $value !== null));
        });
    }

    /**
     * set $postType
     *
     * @param string $postType
     * @return self
     */
    public function postType(string $postType) : self
    {
        $this->postType = $postType;
        return $this;
    }

    /**
     * set $key
     *
     * @param string $key
     * @return self
     */
    public function key(string $key) : self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * set $type
     *
     * @param string $type one of 'string', 'boolean', 'integer', 'number', 'array', 'object'
     * @return self
     */
    public function type(string $type) : self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * set $description
     *
     * @param string $description
     * @return self
     */
    public function description(string $description) : self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * set $single
     *
     * @param bool $single
     * @return self
     */
    public function single(bool $single = true) : self
    {
        $this->single = $single;
        return $this;
    }

    /**
     * set $default
     *
     * @param mixed $default
     * @return self
     */
    public function default(mixed $default) : self
    {
        $this->default = $default;
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
     * set $authCallback
     *
     * @param callable $callback
     * @return self
     */
    public function authCallback(callable $callback) : self
    {
        $this->authCallback = $callback;
        return $this;
    }

    /**
     * set $showInRest
     *
     * @param bool|array $show
     * @return self
     */
    public function showInRest(bool|array $show = true) : self
    {
        $this->showInRest = $show;
        return $this;
    }

    /**
     * set $revisionsEnabled
     *
     * @param bool $enabled
     * @return self
     */
    public function revisionsEnabled(bool $enabled = true) : self
    {
        $this->revisionsEnabled = $enabled;
        return $this;
    }

}

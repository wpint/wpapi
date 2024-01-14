<?php 
namespace Wpint\WPAPI\Metabox;

use Wpint\WPAPI\Metabox\Enum\MetaboxPriorityEnum;
use Wpint\WPAPI\Metabox\Enum\MetaboxContextEnum;
use Wpint\Contracts\Hook\HookContract;
use Closure;
use Wpint\Support\CallbackResolver;

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
 * @method void remove()
 * @method void register()
 * 
 * @see \Wpint\WPAPI\Metabox\Metabox
 */
class Metabox implements HookContract
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
    private string|MetaboxContextEnum $context = MetaboxContextEnum::ADVANCES;
    
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
     * Register metabox
     *
     * @return void
     */
    public function register()
    {
        
        add_action( 'add_meta_boxes', function()
        {
            foreach ( $this->screens as $screen ) 
            {
                add_meta_box(
                    $this->id,  
                    $this->title, 
                    function($post, $props){
                        return CallbackResolver::call($this->callback, ['post' => $post, 'args' => $props['args']], false);
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
            if ( array_key_exists( $this->postKey ?? $this->metaKey, $_POST ) ) {
                update_post_meta(
                    $post_id,
                    $this->metaKey,
                    $_POST[$this->postKey]
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
     * Remove the meta box.
     *
     * @return void
     */
    public function remove() : void
    {
        remove_meta_box( $this->id, $this->screens, $this->context);
    }



}
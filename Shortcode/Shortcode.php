<?php 
namespace Wpint\WPAPI\Shortcode;

use Wpint\WPAPI\Support\Registrable;
use Closure;

/**
 * @method \Wpint\WPAPI\Shortcode\Shortcode tag()
 * @method \Wpint\WPAPI\Shortcode\Shortcode callback()
 * @method void register()
 * 
 * @see \Wpint\WPAPI\Shortcode\Shortcode
 */
class Shortcode extends Registrable
{

    /**
     * $tag
     *
     * @var string
     */
    private string $tag;

    /**
     * $callback
     *
     * @var array|string|Closure
     */
    private array|string|Closure $callback;

    /**
     * Register shortcode
     *
     * @return void
     */
    public function register()
    {

        add_shortcode($this->tag, function($attrs, $content){
            return $this->resolveCallback($this->callback, ["attrs" => $attrs, "content" => $content], false);
        });

    }

    /**
     * set $tag
     *
     * @param string $tag
     * @return self
     */
    public function tag( string $tag ) : self
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * set $callback
     *
     * @param array|string|Closure $callback
     * @return self
     */
    public function callback( array|string|Closure $callback ) : self
    {
        $this->callback =  $callback;
        return $this;
    }


}
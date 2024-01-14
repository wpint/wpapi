<?php 
namespace Wpint\WPAPI;

use Wpint\Support\Exceptions\MethodDoesNotExistException;
use Wpint\Contracts\Hook\HookContract;
use Wpint\Contracts\WPAPI\WPAPIContract;
use Wpint\WPAPI\Cron\Cron;
use Wpint\WPAPI\Enqueuer\Enqueuer;
use Wpint\WPAPI\Hook\Hook;
use Wpint\WPAPI\Metabox\Metabox;
use Wpint\WPAPI\PostType\PostType;
use Wpint\WPAPI\Taxonomy\Taxonomy;
use Wpint\WPAPI\Setting\Setting;
use Wpint\WPAPI\Shortcode\Shortcode;
use Illuminate\Support\Traits\Macroable;

final class WPAPI implements WPAPIContract
{
    use Macroable;

    /**
     * Instance of Setting API class
     *
     * @return HookContract
     */
    public static function setting() : HookContract
    {
        return new Setting();
    }

    /**
     * Instance of Shortcode API class
     *
     * @return HookContract
     */
    public static function shortcode() : HookContract
    {
        return new Shortcode();
    }

    /**
     * Instnce of postType API class
     *
     * @return HookContract
     */
    public static function postType() : HookContract
    {
        return new PostType();
    }

    /**
     * Instance of Taxonomy API class
     *
     * @return HookContract
     */
    public static function taxonomy() : HookContract
    {
        return new Taxonomy();
    }

    /**
     * Instance of Cron API class
     *
     * @return HookContract
     */
    public static function cron() : HookContract
    {
        return new Cron();
    }

    /**
     * Instance of Metabox API class
     *
     * @return HookContract
     */
    public static function metabox() : HookContract
    {
        return new Metabox();
    }

    /**
     * Instance of Hook API class
     *
     * @return HookContract
     */
    public static function hook() : HookContract
    {
        return new Hook();
    }

    /**
     * Instance of Enqueuer API class
     *
     * @return HookContract
     */
    public static function enqueuer() : HookContract
    {
        return new Enqueuer();
    }


}
<?php 
namespace Wpint\WPAPI;

use Wpint\Contracts\Hook\HookContract;
use Wpint\Contracts\WPAPI\WPAPIContract;
use Wpint\WPAPI\Ajax\Ajax;
use Wpint\WPAPI\Cron\Cron;
use Wpint\WPAPI\Enqueuer\Enqueuer;
use Wpint\WPAPI\Hook\Hook;
use Wpint\WPAPI\ImageSize\ImageSize;
use Wpint\WPAPI\Metabox\Metabox;
use Wpint\WPAPI\NavMenu\NavMenu;
use Wpint\WPAPI\PostMeta\PostMeta;
use Wpint\WPAPI\PostType\PostType;
use Wpint\WPAPI\RestRoute\RestRoute;
use Wpint\WPAPI\Role\Role;
use Wpint\WPAPI\Sidebar\Sidebar;
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

    /**
     * Instance of PostMeta API class
     *
     * @return HookContract
     */
    public static function postMeta() : HookContract
    {
        return new PostMeta();
    }

    /**
     * Instance of RestRoute API class
     *
     * @return HookContract
     */
    public static function restRoute() : HookContract
    {
        return new RestRoute();
    }

    /**
     * Instance of Ajax API class
     *
     * @return HookContract
     */
    public static function ajax() : HookContract
    {
        return new Ajax();
    }

    /**
     * Instance of ImageSize API class
     *
     * @return HookContract
     */
    public static function imageSize() : HookContract
    {
        return new ImageSize();
    }

    /**
     * Instance of Sidebar API class
     *
     * @return HookContract
     */
    public static function sidebar() : HookContract
    {
        return new Sidebar();
    }

    /**
     * Instance of NavMenu API class
     *
     * @return HookContract
     */
    public static function navMenu() : HookContract
    {
        return new NavMenu();
    }

    /**
     * Instance of Role API class
     *
     * @return HookContract
     */
    public static function role() : HookContract
    {
        return new Role();
    }


}
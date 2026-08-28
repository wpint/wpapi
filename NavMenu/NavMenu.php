<?php
namespace Wpint\WPAPI\NavMenu;

use Wpint\WPAPI\Support\Registrable;

/**
 * Fluent wrapper around register_nav_menu()/register_nav_menus().
 *
 * @method \Wpint\WPAPI\NavMenu\NavMenu location()
 * @method \Wpint\WPAPI\NavMenu\NavMenu locations()
 * @method void register()
 *
 * @see \Wpint\WPAPI\NavMenu\NavMenu
 */
class NavMenu extends Registrable
{

    /**
     * Map of location slug => description, merged from single location()
     * calls and/or a bulk locations() call.
     *
     * @var array<string, string>
     */
    private array $locations = [];

    /**
     * Register the nav menu location(s)
     *
     * @return void
     */
    public function register() : void
    {
        add_action('after_setup_theme', function()
        {
            register_nav_menus($this->locations);
        });
    }

    /**
     * add a single nav menu location
     *
     * @param string $location
     * @param string $description
     * @return self
     */
    public function location(string $location, string $description = '') : self
    {
        $this->locations[$location] = $description;
        return $this;
    }

    /**
     * add several nav menu locations at once
     *
     * @param array<string, string> $descriptionByLocation
     * @return self
     */
    public function locations(array $descriptionByLocation) : self
    {
        $this->locations = array_merge($this->locations, $descriptionByLocation);
        return $this;
    }

}

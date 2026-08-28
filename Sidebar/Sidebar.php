<?php
namespace Wpint\WPAPI\Sidebar;

use Wpint\WPAPI\Support\Registrable;

/**
 * Fluent wrapper around register_sidebar().
 *
 * Note: the before/after wrap markup is developer-supplied, static
 * template HTML, not user input — if a consumer interpolates dynamic
 * data into it, escaping that data remains the consumer's responsibility.
 *
 * @method \Wpint\WPAPI\Sidebar\Sidebar name()
 * @method \Wpint\WPAPI\Sidebar\Sidebar id()
 * @method \Wpint\WPAPI\Sidebar\Sidebar description()
 * @method \Wpint\WPAPI\Sidebar\Sidebar class()
 * @method \Wpint\WPAPI\Sidebar\Sidebar beforeWidget()
 * @method \Wpint\WPAPI\Sidebar\Sidebar afterWidget()
 * @method \Wpint\WPAPI\Sidebar\Sidebar beforeTitle()
 * @method \Wpint\WPAPI\Sidebar\Sidebar afterTitle()
 * @method void register()
 *
 * @see \Wpint\WPAPI\Sidebar\Sidebar
 */
class Sidebar extends Registrable
{

    /**
     * $name
     *
     * @var string
     */
    private string $name;

    /**
     * $id
     *
     * @var string
     */
    private string $id;

    /**
     * $description
     *
     * @var string
     */
    private string $description = '';

    /**
     * $class
     *
     * @var string
     */
    private string $class = '';

    /**
     * $beforeWidget
     *
     * @var string
     */
    private string $beforeWidget = '<section id="%1$s" class="widget %2$s">';

    /**
     * $afterWidget
     *
     * @var string
     */
    private string $afterWidget = '</section>';

    /**
     * $beforeTitle
     *
     * @var string
     */
    private string $beforeTitle = '<h2 class="widget-title">';

    /**
     * $afterTitle
     *
     * @var string
     */
    private string $afterTitle = '</h2>';

    /**
     * Register the sidebar
     *
     * @return void
     */
    public function register() : void
    {
        add_action('widgets_init', function()
        {
            register_sidebar([
                'name'          => $this->name,
                'id'            => $this->id,
                'description'   => $this->description,
                'class'         => $this->class,
                'before_widget' => $this->beforeWidget,
                'after_widget'  => $this->afterWidget,
                'before_title'  => $this->beforeTitle,
                'after_title'   => $this->afterTitle,
            ]);
        });
    }

    /**
     * set $name
     *
     * @param string $name
     * @return self
     */
    public function name(string $name) : self
    {
        $this->name = $name;
        return $this;
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
     * set $class
     *
     * @param string $class
     * @return self
     */
    public function class(string $class) : self
    {
        $this->class = $class;
        return $this;
    }

    /**
     * set $beforeWidget
     *
     * @param string $html
     * @return self
     */
    public function beforeWidget(string $html) : self
    {
        $this->beforeWidget = $html;
        return $this;
    }

    /**
     * set $afterWidget
     *
     * @param string $html
     * @return self
     */
    public function afterWidget(string $html) : self
    {
        $this->afterWidget = $html;
        return $this;
    }

    /**
     * set $beforeTitle
     *
     * @param string $html
     * @return self
     */
    public function beforeTitle(string $html) : self
    {
        $this->beforeTitle = $html;
        return $this;
    }

    /**
     * set $afterTitle
     *
     * @param string $html
     * @return self
     */
    public function afterTitle(string $html) : self
    {
        $this->afterTitle = $html;
        return $this;
    }

}

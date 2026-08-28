<?php
namespace Wpint\WPAPI\ImageSize;

use Wpint\WPAPI\Support\Registrable;

/**
 * Fluent wrapper around add_image_size(), with an optional opt-in to
 * also list the size in the media library's "Image Size" dropdown via
 * the image_size_names_choose filter.
 *
 * @method \Wpint\WPAPI\ImageSize\ImageSize name()
 * @method \Wpint\WPAPI\ImageSize\ImageSize width()
 * @method \Wpint\WPAPI\ImageSize\ImageSize height()
 * @method \Wpint\WPAPI\ImageSize\ImageSize crop()
 * @method \Wpint\WPAPI\ImageSize\ImageSize label()
 * @method \Wpint\WPAPI\ImageSize\ImageSize showInDropdown()
 * @method void register()
 *
 * @see \Wpint\WPAPI\ImageSize\ImageSize
 */
class ImageSize extends Registrable
{

    /**
     * $name
     *
     * @var string
     */
    private string $name;

    /**
     * $width
     *
     * @var int
     */
    private int $width = 0;

    /**
     * $height
     *
     * @var int
     */
    private int $height = 0;

    /**
     * $crop
     *
     * @var bool|array
     */
    private bool|array $crop = false;

    /**
     * $label
     *
     * @var string
     */
    private string $label;

    /**
     * $showInDropdown
     *
     * @var bool
     */
    private bool $showInDropdown = false;

    /**
     * Register the image size
     *
     * @return void
     */
    public function register() : void
    {
        add_action('after_setup_theme', function()
        {
            add_image_size($this->name, $this->width, $this->height, $this->crop);
        });

        if ($this->showInDropdown)
        {
            add_filter('image_size_names_choose', function(array $sizes)
            {
                $sizes[$this->name] = $this->prop('label', $this->name);
                return $sizes;
            });
        }
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
     * set $width
     *
     * @param int $width
     * @return self
     */
    public function width(int $width) : self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * set $height
     *
     * @param int $height
     * @return self
     */
    public function height(int $height) : self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * set $crop
     *
     * @param bool|array $crop
     * @return self
     */
    public function crop(bool|array $crop = true) : self
    {
        $this->crop = $crop;
        return $this;
    }

    /**
     * set $label
     *
     * @param string $label
     * @return self
     */
    public function label(string $label) : self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * set $showInDropdown
     *
     * @param bool $show
     * @return self
     */
    public function showInDropdown(bool $show = true) : self
    {
        $this->showInDropdown = $show;
        return $this;
    }

}

<?php
namespace Wpint\WPAPI\Setting;

use Wpint\WPAPI\Setting\Enum\OptionGroupEnum;
use Wpint\WPAPI\Support\Registrable;
use Illuminate\Support\Str;
use Closure;

/**
 * @method \Wpint\WPAPI\Setting\Setting name()
 * @method \Wpint\WPAPI\Setting\Setting sectionTitle()
 * @method \Wpint\WPAPI\Setting\Setting sectionCallback()
 * @method \Wpint\WPAPI\Setting\Setting fieldTitle()
 * @method \Wpint\WPAPI\Setting\Setting fieldCallback()
 * @method \Wpint\WPAPI\Setting\Setting optionGroup()
 * @method \Wpint\WPAPI\Setting\Setting sanitizeCallback()
 * @method \Wpint\WPAPI\Setting\Setting type()
 * @method \Wpint\WPAPI\Setting\Setting default()
 * @method \Wpint\WPAPI\Setting\Setting showInRest()
 * @method void register()
 *
 * @see \Wpint\WPAPI\Setting\Setting
 */
class Setting extends Registrable
{

    /**
     * $name
     *
     * @var string
     */
    private string $name;

    /**
     * $sectionTitle
     *
     * @var string
     */
    private string $sectionTitle;

    /**
     * $sectionCallback
     *
     * @var array|string|Closure
     */
    private array|string|Closure $sectionCallback;

    /**
     * $fieldTitle
     *
     * @var string
     */
    private string $fieldTitle;

    /**
     * $fieldCallback
     *
     * @var array|string|Closure
     */
    private array|string|Closure $fieldCallback;

    /**
     * $optionGroup
     *
     * @var string
     */
    private string $optionGroup = OptionGroupEnum::OPTIONS;

    /**
     * Sanitizer applied to the option's value before it's saved.
     * Defaults to 'sanitize_text_field' — WordPress applies no
     * sanitization by default, which is a common security gap.
     *
     * @var callable|string
     */
    private $sanitizeCallback = 'sanitize_text_field';

    /**
     * $type
     *
     * @var string
     */
    private string $type;

    /**
     * $default
     *
     * @var mixed
     */
    private mixed $default = null;

    /**
     * $showInRest
     *
     * @var bool
     */
    private bool $showInRest = false;

    /**
     * Register setting
     *
     * @return void
     */
    public  function register()
    {
        add_action('admin_init', function(){

            register_setting($this->optionGroup, $this->name, array_filter([
                'type'              => $this->prop('type'),
                'sanitize_callback' => $this->sanitizeCallback,
                'default'           => $this->default,
                'show_in_rest'      => $this->showInRest,
            ], fn($value) => $value !== null));

            add_settings_section(
                Str::slug($this->sectionTitle, '_'),
                $this->sectionTitle,
                function()
                {
                    return $this->resolveCallback($this->sectionCallback);
                },
                $this->optionGroup
            );

            add_settings_field(
                Str::slug($this->fieldTitle, '_'),
                $this->fieldTitle,
                function()
                {
                    return $this->resolveCallback($this->fieldCallback);
                },
                $this->optionGroup,
                Str::slug($this->sectionTitle, '_')
            );

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
     * set $sectionTitle
     *
     * @param string $sectionTitle
     * @return self
     */
    public function sectionTitle(string $sectionTitle) : self
    {
        $this->sectionTitle = $sectionTitle;
        return $this;
    }

    /**
     * set $fieldTitle
     *
     * @param string $fieldTitle
     * @return self
     */
    public function fieldtitle(string $fieldTitle) : self
    {
        $this->fieldTitle = $fieldTitle;
        return $this;
    }

    /**
     * set $optionGroup
     *
     * @param string $optionGroup
     * @return self
     */
    public function optionGroup(string $optionGroup) : self
    {
        $this->optionGroup = $optionGroup;
        return $this;
    }

    /**
     * set $sectionCallback
     *
     * @param array|string|Closure $closure
     * @return self
     */
    public function sectionCallback(array|string|Closure $closure) : self
    {
        $this->sectionCallback =  $closure;
        return $this;
    }

    /**
     * set $fieldCallback
     *
     * @param array|string|Closure $closure
     * @return self
     */
    public function fieldCallback(array|string|Closure $closure) : self
    {
        $this->fieldCallback = $closure;
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
     * set $showInRest
     *
     * @param bool $show
     * @return self
     */
    public function showInRest(bool $show = true) : self
    {
        $this->showInRest = $show;
        return $this;
    }

}

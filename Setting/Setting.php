<?php
namespace Wpint\WPAPI\Setting;

use Wpint\Support\CallbackResolver;
use Wpint\Contracts\Hook\HookContract;
use Wpint\WPAPI\Setting\Enum\OptionGroupEnum;
use Illuminate\Support\Str;
use Closure;

/**
 * @method \Wpint\WPAPI\Setting\Setting name()
 * @method \Wpint\WPAPI\Setting\Setting sectionTitle()
 * @method \Wpint\WPAPI\Setting\Setting sectionCallback()
 * @method \Wpint\WPAPI\Setting\Setting fieldTitle()
 * @method \Wpint\WPAPI\Setting\Setting fieldCallback()
 * @method \Wpint\WPAPI\Setting\Setting optionGroup()
 * @method void register()
 * 
 * @see \Wpint\WPAPI\Setting\Setting
 */
class Setting implements HookContract
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
     * Register setting
     *
     * @return void
     */
    public  function register() 
    {
        add_action('admin_init', function(){

            register_setting($this->optionGroup, $this->name);

            add_settings_section(
                Str::slug($this->sectionTitle, '_'),
                $this->sectionTitle, 
                function()
                {
                    return CallbackResolver::call($this->sectionCallback);
                },
                $this->optionGroup
            );
    
            add_settings_field(
                Str::slug($this->fieldTitle, '_'),
                $this->fieldTitle, 
                function()
                {
                    return CallbackResolver::call($this->fieldCallback);
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

}
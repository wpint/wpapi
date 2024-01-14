<?php 
namespace Wpint\WPAPI\Hook;

use Wpint\Support\CallbackResolver;
use Wpint\Contracts\Hook\HookContract;
use Wpint\WPAPI\Hook\Enum\HookTypeEnum;
use Closure;

/**
 * @method \Wpint\WPAPI\Hook\Hook name()
 * @method \Wpint\WPAPI\Hook\Hook type()
 * @method \Wpint\WPAPI\Hook\Hook callback()
 * @method \Wpint\WPAPI\Hook\Hook priority()
 * @method \Wpint\WPAPI\Hook\Hook acceptedArgs()
 * @method void register()
 * 
 * @see \Wpint\WPAPI\Hook\Hook
 */
class Hook implements HookContract
{
    
    /**
     * $name
     *
     * @var string
     */
    private string $name;

    /**
     * $callback
     *
     * @var Closure|string
     */
    private Closure|string|array $callback;

    /**
     * $type
     *
     * @var string|HookTypeEnum
     */
    private string|HookTypeEnum $type = HookTypeEnum::ACTION; 

    /**
     * $priority
     *
     * @var integer
     */
    private int $priority = 10;

    /**
     * $acceptedArgs
     *
     * @var integer
     */
    private int $acceptedArgs = 1;

    /**
     * register the hook
     *
     * @return void
     */
    public function register()
    {
        $this->applyHook();
    }

    /**
     * set $name
     *
     * @param string $name
     * @return self
     */
    public function name( string $name ) : self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * set $type
     *
     * @param string|HookTypeEnum $type
     * @return self
     */
    public function type( string|HookTypeEnum $type ) : self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * set $callback
     *
     * @param Closure|string|array $callback
     * @return self
     */
    public function callback( Closure|string|array $callback ) : self
    {
        $this->callback =  $callback;
        return $this;
    }

    /**
     * set $priority
     *
     * @param integer $priority
     * @return self
     */
    public function priority( int $priority ) : self
    {
        $this->priority =  $priority;
        return $this;
    }

    /**
     * set $accepterArgs
     *
     * @param integer $acceptedArgs
     * @return self
     */
    public function acceptedArgs( int $acceptedArgs ) : self
    {
        $this->acceptedArgs =  $acceptedArgs;
        return $this;
    }

    /**
     * Execute  & apply the wp hook
     *
     * @return void
     */
    private function applyHook()
    {


        if($this->type == HookTypeEnum::ACTION){
            add_action(
                $this->name, 
                function(...$args){
                    return CallbackResolver::call($this->callback, $args);
                },
                $this->priority,
                $this->acceptedArgs
            );
            return;
        };

        add_filter(
            $this->name, 
            function(...$args){
                return CallbackResolver::call($this->callback, $args);
            },
            $this->priority,
            $this->acceptedArgs
        );   

    }

}
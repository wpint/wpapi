<?php 
namespace Wpint\WPAPI;

use WPINT\Framework\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class WPAPIServiceProvider extends ServiceProvider
{
   
    /**
     * Register WPAPI service
     *
     * @return void
     */
    public function register() : void 
    {
        $this->app->bind('wpapi', function(Application $app){
           return new WPAPI();
        });

    }

    /**
     * Bootstrap any application service
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {

    }


}
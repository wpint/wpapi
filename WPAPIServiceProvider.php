<?php 
namespace Wpint\WPAPI;

use WPINT\Core\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;
use WPINT\Core\Foundation\ServiceProvider;

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
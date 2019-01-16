<?php

namespace App\Providers;

use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class RiakServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(Connection::class,function($app){

            return new Connection(config('riak'));


        });


    }

    /**
     * @var array
     */
    public $bindings=[

        ServiceProvider::class=>DigitaOceanServerProvider::class,
    ];

    public $singleton=[
      DowntimeNotifier::class=>PingdomDowntimeNofier::class,
    ];

}

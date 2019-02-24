<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StoreBranchServiceProvider extends ServiceProvider
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
        //build connection between interface and implements class
        //bind by singleton
//        $this->app->singleton('test',function(){
//            return new TestService();
//        });

        //bind with interface
        $this->app->bind('App\Services\Interfaces\NodeTree',
            'App\Services\Implementations\StoreBranchTree');

    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MyProvider extends ServiceProvider
{

    // protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // error_log('register');
        // Logger('booted');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // error_log('boot');
    }
}

<?php

namespace Spatie\BladeX;

use Illuminate\Support\ServiceProvider;

class BladeXServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/BladeX.php' => config_path('BladeX.php'),
            ], 'config');

            /*
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'BladeX');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/BladeX'),
            ], 'views');
            */
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'BladeX');
    }
}

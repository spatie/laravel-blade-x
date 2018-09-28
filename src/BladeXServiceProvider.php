<?php

namespace Spatie\BladeX;

use Illuminate\Support\ServiceProvider;

class BladeXServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/BladeX.php' => config_path('BladeX.php'),
            ], 'config');
        }

        $this->app->singleton(BladeX::class);


        $this->app->alias(BladeX::class, 'blade-x');

        $this->app['blade.compiler']->extend(function ($view, $compiler) {
            return BladeX::compile($view);
        });

    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/blade-x.php', 'BladeX');
    }
}

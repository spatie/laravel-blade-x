<?php

namespace Spatie\BladeX;

use Illuminate\Support\ServiceProvider;

class BladeXServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(BladeX::class);

        $this->app->alias(BladeX::class, 'blade-x');

        $this->app['blade.compiler']->extend(function ($view) {
            return $this->app[BladeX::class]->compile($view);
        });
    }
}

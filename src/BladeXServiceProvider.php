<?php

namespace Spatie\BladeX;

use Illuminate\Support\ServiceProvider;

class BladeXServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BladeX::class);
        $this->app->singleton(ContextStack::class);

        $this->app->alias(BladeX::class, 'blade-x');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bladex');
    }

    public function boot()
    {
        $this->app['blade.compiler']->extend(function ($view) {
            return $this->app[Compiler::class]->compile($view);
        });

        //$this->app->make(BladeX::class)->component('bladex::context', 'context');
    }
}

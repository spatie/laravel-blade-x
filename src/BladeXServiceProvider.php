<?php

namespace Spatie\BladeX;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeXServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BladeX::class);
        $this->app->singleton(ContextStack::class);

        $this->app->alias(BladeX::class, 'blade-x');

        Blade::directive('attributes', function () {
            return "<?php echo \Spatie\BladeX\Html::getAttributeStringForInheritedProperties(get_defined_vars()); ?>";
        });
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bladex');

        $this->app['blade.compiler']->extend(function ($view) {
            return $this->app[Compiler::class]->compile($view);
        });

        $this->app->make(BladeX::class)->component('bladex::context', 'context');
    }
}

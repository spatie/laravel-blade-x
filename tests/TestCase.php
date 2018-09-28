<?php

namespace Spatie\BladeX\Tests;

use Illuminate\Support\Facades\Blade;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\BladeX\BladeXServiceProvider;
use Spatie\ViewComponents\ViewComponentsServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            BladeXServiceProvider::class,
        ];
    }

    protected function assertBladeCompilesTo(string $expected, string $template)
    {
        $this->assertEquals($expected, Blade::compileString($template));
    }
}
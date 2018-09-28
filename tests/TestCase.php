<?php

namespace Spatie\BladeX\Tests;

use Illuminate\Support\Facades\Blade;
use Spatie\BladeX\BladeXServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

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

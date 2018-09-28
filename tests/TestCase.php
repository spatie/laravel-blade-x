<?php

namespace Spatie\BladeX\Tests;

use Illuminate\Support\Facades\View;
use Spatie\BladeX\BladeXServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\BladeX\Facades\BladeX;

abstract class TestCase extends Orchestra
{
    protected function setUp()
    {
        parent::setUp();

        View::addLocation(__DIR__.'/stubs/views');
    }

    protected function getPackageProviders($app)
    {
        return [
            BladeXServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'BlaxeX' => BladeX::class,
        ];
    }

    protected function assertBladeCompilesTo(string $expected, string $template, array $parameters = [])
    {
        $this->assertEquals($expected, view($template, $parameters)->render());
    }

    protected function getStub(string $fileName): string
    {
        return __DIR__ . "/stubs/{$fileName}";
    }
}

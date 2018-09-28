<?php

namespace Spatie\BladeX\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;
use Spatie\BladeX\BladeXServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\BladeX\Facades\BladeX;
use Spatie\Snapshots\MatchesSnapshots;

abstract class TestCase extends Orchestra
{
    use MatchesSnapshots;

    protected function setUp()
    {
        parent::setUp();

        View::addLocation(__DIR__.'/stubs/views');

        Artisan::call('view:clear');
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
            'BladeX' => BladeX::class,
        ];
    }

    protected function getStub(string $fileName): string
    {
        return __DIR__ . "/stubs/{$fileName}";
    }
}

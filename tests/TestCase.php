<?php

namespace Spatie\BladeX\Tests;

use Spatie\BladeX\Facades\BladeX;
use Illuminate\Support\Facades\View;
use Spatie\Snapshots\MatchesSnapshots;
use Illuminate\Support\Facades\Artisan;
use Spatie\BladeX\BladeXServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use MatchesSnapshots;

    protected function setUp()
    {
        parent::setUp();

        View::addLocation(__DIR__.'/stubs');

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
        return __DIR__."/stubs/{$fileName}";
    }

    protected function assertMatchesViewSnapshot(string $viewName)
    {
        $this->assertMatchesXmlSnapshot(
            '<div>'.view("views.{$viewName}")->render().'</div>'
        );
    }
}

<?php

namespace Spatie\BladeX\Tests;

use Spatie\BladeX\Facades\BladeX;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
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

        View::addLocation(__DIR__.'/previous-stubsbla');

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
        return __DIR__."/previous-stubs/{$fileName}";
    }

    protected function assertMatchesViewSnapshot(string $viewName, array $data = [])
    {
        $fullViewName = "views.{$viewName}";

        $this->assertMatchesXmlSnapshot(
            '<div>'.view($fullViewName, $data)->render().'</div>'
        );

        $this->assertMatchesXmlSnapshot(
            '<div>'.Blade::compileString($this->getViewContents($viewName)).'</div>'
        );
    }

    protected function getViewContents(string $viewName): string
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $testFile = last($backtrace)['file'];

        $baseDirectory = pathinfo($testFile, PATHINFO_DIRNAME);

        $viewFileName = "{$baseDirectory}/stubs/views/{$viewName}.blade.php";

        return file_get_contents($viewFileName);
    }
}

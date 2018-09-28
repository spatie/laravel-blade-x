<?php

namespace Spatie\BladeX;

use Illuminate\Support\Facades\File;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Symfony\Component\Finder\SplFileInfo;

class BladeX
{
    /** @var array */
    public $registeredComponents = [];

    public function component(string $componentName, string $viewName)
    {
        if (! view()->exists($viewName)) {
            throw CouldNotRegisterComponent::viewNotFound($componentName, $viewName);
        }

        $this->registeredComponents[$componentName] = $viewName;
    }

    public function components(string $directory)
    {
        collect(File::allFiles($directory))
            ->filter(function (SplFileInfo $file) {
                return ends_with($file->getFilename(), '.blade.php');
            })

            ->each(function(SplFileInfo $fileInfo) {
                $componentName = rtrim($fileInfo->getFilename(), '.blade.php');

                $viewName = $this->getViewName($fileInfo->getPathname());

                dd($componentName, $viewName);
            });
    }

    private function getViewName(string $pathName): string
    {
        foreach (app('view.finder')->getPaths() as $registeredViewPath) {
            dump('foreach', $pathName, realpath($registeredViewPath), '---');
            $pathName = str_replace(realpath($registeredViewPath), '', $pathName);
        }

        return $pathName;
    }
}

<?php

namespace Spatie\BladeX;

use Illuminate\Support\Facades\File;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Symfony\Component\Finder\SplFileInfo;

class BladeX
{
    /** @var array */
    public $registeredComponents = [];

    public function component(string $componentName, string $classOrView)
    {
        $component = $this->getComponent($classOrView);

        if (!$component) {
            throw CouldNotRegisterComponent::componentNotFound($componentName, $classOrView);
        }

        $this->registeredComponents[$componentName] = $component;
    }

    public function components(string $directory)
    {
        if (!File::isDirectory($directory)) {
            throw CouldNotRegisterComponent::componentDirectoryNotFound($directory);
        }

        collect(File::allFiles($directory))
            ->filter(function (SplFileInfo $file) {
                return ends_with($file->getFilename(), '.blade.php');
            })
            ->each(function(SplFileInfo $fileInfo) {
                $componentName = rtrim($fileInfo->getFilename(), '.blade.php');

                dd($componentName, $fileInfo->getPathname());
            });
    }

    protected function getComponent(string $classOrView): ?object
    {
        if (class_exists($classOrView)) {
            return app($classOrView);
        }

        if (view()->exists($classOrView)) {
            return new BladeViewComponent($classOrView);
        }

        return null;
    }
}

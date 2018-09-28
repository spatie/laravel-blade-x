<?php

namespace Spatie\BladeX;

use Illuminate\Support\Facades\File;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;

class BladeX
{
    /** @var array */
    public $registeredComponents = [];

    public function component(string $componentName, string $classOrView)
    {
        $component = $this->getComponent($classOrView);

        if (! $component) {
            throw CouldNotRegisterComponent::componentNotFound($componentName, $classOrView);
        }

        $this->registeredComponents[$componentName] = $component;
    }

    public function components(string $directory)
    {
        if (! File::isDirectory($directory)) {
            throw CouldNotRegisterComponent::componentDirectoryNotFound($directory);
        }

        dd(File::allFiles($directory));
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

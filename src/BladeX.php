<?php

namespace Spatie\BladeX;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Symfony\Component\Finder\SplFileInfo;
use Spatie\BladeX\Exceptions\CouldNotRegisterBladeXComponent;

class BladeX
{
    /** @var array */
    public $registeredComponents = [];

    /** @var string */
    protected $prefix = '';

    public function component(string $bladeViewName, string $bladeXComponentName = null): BladeXComponent
    {
        $bladeViewName = str_replace('.', '/', $bladeViewName);

        if (is_null($bladeXComponentName)) {
            $baseComponentName = explode('/', $bladeViewName);

            $bladeXComponentName = kebab_case(end($baseComponentName));
        }

        if (! view()->exists($bladeViewName)) {
            throw CouldNotRegisterBladeXComponent::viewNotFound($bladeViewName, $bladeXComponentName);
        }

        $newBladeXComponent = new BladeXComponent($bladeXComponentName, $bladeViewName);

        $this->registeredComponents[$newBladeXComponent->name] = $newBladeXComponent;

        return $newBladeXComponent;
    }

    public function getRegisteredComponents(): array
    {
        return array_values($this->registeredComponents);
    }

    public function components(string $directory)
    {
        if (! File::isDirectory($directory)) {
            throw CouldNotRegisterBladeXComponent::componentDirectoryNotFound($directory);
        }

        collect(File::allFiles($directory))
            ->filter(function (SplFileInfo $file) {
                return ends_with($file->getFilename(), '.blade.php');
            })
            ->each(function (SplFileInfo $fileInfo) {
                $viewName = $this->getViewName($fileInfo->getPathname());

                $componentName = str_replace_last('.blade.php', '', $fileInfo->getFilename());

                $componentName = kebab_case($componentName);

                $this->component($viewName, $componentName);
            });
    }

    public function prefix(string $prefix = ''): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getPrefix(): string
    {
        return empty($this->prefix) ? '' : str_finish($this->prefix, '-');
    }

    protected function getViewName(string $pathName): string
    {
        foreach (View::getFinder()->getPaths() as $registeredViewPath) {
            $pathName = str_replace(realpath($registeredViewPath).'/', '', $pathName);
        }

        $viewName = str_replace_last('.blade.php', '', $pathName);

        return $viewName;
    }
}

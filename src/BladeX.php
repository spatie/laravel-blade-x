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

    /**
     * @param string|\Spatie\BladeX\BladeXComponent $bladeViewName
     * @param string $bladeXComponentName
     *
     * @return \Spatie\BladeX\BladeXComponent
     */
    public function component($bladeViewName, string $bladeXComponentName = ''): BladeXComponent
    {
        $newBladeXComponent = new BladeXComponent($bladeViewName, $bladeXComponentName);

        $this->registeredComponents[$newBladeXComponent->name] = $newBladeXComponent;

        return $newBladeXComponent;
    }

    public function getRegisteredComponents(): array
    {
        return array_values($this->registeredComponents);
    }

    /**
     * @param string|array $directory
     */
    public function components($directory, string $namespace = '')
    {
        if (is_string($directory)) {
            $directory = [$namespace => $directory];
        }

        if (! is_array($directory)) {
            throw CouldNotRegisterBladeXComponent::invalidArgument();
        }

        collect($directory)->each(function (string $directory, string $namespace = '') {
            $this->registerComponents($directory, $namespace);
        });
    }

    protected function registerComponents(string $directory, string $namespace = '')
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

        if ($namespace !== '') {
            View::addNamespace($namespace, $directory);
        }
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
        $viewPaths = collect(View::getFinder()->getPaths())
            ->map(function (string $registeredViewPath) {
                return realpath($registeredViewPath);
            })
            ->filter()
            ->toArray();

        foreach ($viewPaths as $viewPath) {
            $pathName = str_replace($viewPath.'/', '', $pathName);
        }

        $viewName = str_replace_last('.blade.php', '', $pathName);

        return $viewName;
    }
}

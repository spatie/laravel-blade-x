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

    public function component(string $view, string $tag = ''): ?Component
    {
        if (ends_with($view, '.*')) {
            dd($view);
            $this->components($view);

            return null;
        }

        $component = new Component($view, $tag);

        $this->registeredComponents[$component->tag] = $component;

        return $component;
    }

    public function getRegisteredComponents(): array
    {
        return array_values($this->registeredComponents);
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

    protected function components(string $viewDirectory)
    {
        $directory = $this->getAbsoluteDirectory($viewDirectory);

        collect(File::allFiles($directory))
            ->filter(function (SplFileInfo $file) {
                return ends_with($file->getFilename(), '.blade.php');
            })
            ->map(function(SplFileInfo $file) use ($viewDirectory) {
                return $this->getViewName($file, $viewDirectory);
            })
            ->each(function (string $viewName) {
                $this->component($viewName);
            });

        if (! is_array($directory)) {
            throw CouldNotRegisterBladeXComponent::invalidArgument();
        }

        collect($directory)->each(function (string $directory) use ($namespace) {
            $this->registerComponents($directory, $namespace);
        });
    }

    protected function getAbsoluteDirectory(string $viewDirectory): string
    {
        $absoluteDirectory = collect(View::getFinder()->getPaths())
            ->map(function(string $path) use ($viewDirectory) {
                return realpath($path . '/' . $viewDirectory);
            })
            ->filter()
            ->first();

        if (! $absoluteDirectory)
        {
            /** TODO: make dedicated exception */
            throw new \Exception('absolute directory not found');
        }

        return $absoluteDirectory;

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
            ->each(function (SplFileInfo $fileInfo) use ($namespace) {
                $viewName = $this->getViewName($fileInfo->getPathname(), $namespace);

                $componentName = str_replace_last('.blade.php', '', $fileInfo->getFilename());

                $componentName = kebab_case($componentName);

                $this->component($viewName, $componentName);
            });
    }

    protected function getViewName(SplFileInfo $viewFile, string $viewPath): string
    {
        $view = str_replace_last('.blade.php', '', $viewFile->getFilename());

        return "{$viewPath}.{$view}";
    }
}

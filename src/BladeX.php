<?php

namespace Spatie\BladeX;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Symfony\Component\Finder\SplFileInfo;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;

class BladeX
{
    /** @var array */
    public $registeredComponents = [];

    /** @var string */
    protected $prefix = '';

    /**
     * @param string|array $view
     * @param string $tag
     *
     * @return null|\Spatie\BladeX\Component
     */
    public function component($view, string $tag = ''): ?Component
    {
        if (is_array($view)) {
            collect($view)->each(function(string $singleView) {
                $this->component($singleView);
            });

            return null;
        }

        if ($view instanceof Component) {
            $this->registeredComponents[$view->tag] = $view;

            return $view;
        }

        if (! is_string($view)) {
            throw CouldNotRegisterComponent::invalidArgument();
        }

        if (ends_with($view, '.*')) {
            $this->componentDirectory($view);

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

    protected function componentDirectory(string $viewDirectory)
    {
        $viewDirectory = str_before($viewDirectory, '.*');

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
    }

    protected function getAbsoluteDirectory(string $viewPath): string
    {
        $viewPath = str_replace('.', '/', $viewPath);

        $absoluteDirectory = collect(View::getFinder()->getPaths())
            ->map(function(string $path) use ($viewPath) {
                return realpath($path . '/' . $viewPath);
            })
            ->filter()
            ->first();

        if (! $absoluteDirectory) {
            throw CouldNotRegisterComponent::viewPathNotFound($viewPath);
        }

        return $absoluteDirectory;
    }

    protected function registerComponents(string $directory, string $namespace = '')
    {
        if (! File::isDirectory($directory)) {
            throw CouldNotRegisterComponent::viewPathNotFound($directory);
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

<?php

namespace Spatie\BladeX;

use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;
use Spatie\BladeX\ComponentDirectory\RegularDirectory;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Spatie\BladeX\ComponentDirectory\NamespacedDirectory;

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
            foreach ($view as $singleView) {
                $this->component($singleView);
            }

            return null;
        }

        if ($view instanceof Component) {
            $this->registeredComponents[$view->tag] = $view;

            return $view;
        }

        if (! is_string($view)) {
            throw CouldNotRegisterComponent::invalidArgument();
        }

        if (ends_with($view, '*')) {
            $this->registerComponents($view);

            return null;
        }

        if (! view()->exists($view)) {
            throw CouldNotRegisterComponent::viewNotFound($view, $tag);
        }

        $component = new Component($view, $tag);

        $this->registeredComponents[$component->tag] = $component;

        return $component;
    }

    public function registeredComponents(): array
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

    public function registerComponents(string $viewDirectory)
    {
        $componentDirectory = str_contains($viewDirectory, '::')
            ? new NamespacedDirectory($viewDirectory)
            : new RegularDirectory($viewDirectory);

        collect(File::files($componentDirectory->getAbsoluteDirectory()))
            ->filter(function (SplFileInfo $file) {
                return ends_with($file->getFilename(), '.blade.php');
            })
            ->map(function (SplFileInfo $file) use ($componentDirectory) {
                return $componentDirectory->getViewName($file);
            })
            ->each(function (string $viewName) {
                $this->component($viewName);
            });
    }
}

<?php

namespace Spatie\BladeX;

use Illuminate\Support\Str;
use Spatie\BladeX\ComponentDirectory\NamespacedDirectory;
use Spatie\BladeX\ComponentDirectory\RegularDirectory;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Symfony\Component\Finder\SplFileInfo;

class BladeX
{
    /** @var array */
    public $registeredComponents = [];

    /** @var string */
    protected $prefix = '';

    /**
     * @param string|string[] $view
     * @param string $tag
     *
     * @return null|\Spatie\BladeX\Component
     */
    public function component($view, string $tag = ''): ?Component
    {
        if (is_iterable($view)) {
            $this->registerViews($view);

            return null;
        }

        if ($view instanceof Component) {
            $this->registeredComponents[] = $view;

            return $view;
        }

        if (! is_string($view)) {
            throw CouldNotRegisterComponent::invalidArgument();
        }

        if (Str::endsWith($view, '*')) {
            $this->registerComponents($view);

            return null;
        }

        if (! view()->exists($view)) {
            throw CouldNotRegisterComponent::viewNotFound($view, $tag);
        }

        $component = new Component($view, $tag);

        $this->registeredComponents[] = $component;

        return $component;
    }

    /**
     * @param string|string[] $viewDirectory
     *
     * @return \Spatie\BladeX\ComponentCollection|\Spatie\BladeX\Component[]
     */
    public function components($viewDirectory): ComponentCollection
    {
        if (is_iterable($viewDirectory)) {
            $components = new ComponentCollection();

            foreach ($viewDirectory as $singleViewDirectory) {
                if (Str::endsWith($singleViewDirectory, '*')) {
                    $components = $components->merge($this->registerComponents($singleViewDirectory));
                } else {
                    $components->push($this->component($singleViewDirectory));
                }
            }

            return $components;
        }

        return $this->registerComponents($viewDirectory);
    }

    /**
     * @return \Spatie\BladeX\Component[]
     */
    public function registeredComponents(): array
    {
        return collect($this->registeredComponents)->reverse()->unique(function (Component $component) {
            return $component->getTag();
        })->reverse()->values()->all();
    }

    public function prefix(string $prefix = ''): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getPrefix(): string
    {
        return empty($this->prefix) ? '' : Str::finish($this->prefix, '-');
    }

    /**
     * @internal
     *
     * @param string $viewDirectory
     *
     * @return \Spatie\BladeX\ComponentCollection|\Spatie\BladeX\Component[]
     */
    public function registerComponents(string $viewDirectory)
    {
        if (! Str::endsWith($viewDirectory, '*')) {
            throw CouldNotRegisterComponent::viewDirectoryWithoutWildcard($viewDirectory);
        }

        $includeSubdirectories = Str::endsWith($viewDirectory, '**.*');

        $componentDirectory = Str::contains($viewDirectory, '::')
            ? new NamespacedDirectory($viewDirectory, $includeSubdirectories)
            : new RegularDirectory($viewDirectory, $includeSubdirectories);

        return $this->registerViews(
            ComponentCollection::make($componentDirectory->getFiles())

                ->filter(function (SplFileInfo $file) {
                    return Str::endsWith($file->getFilename(), '.blade.php');
                })
                ->map(function (SplFileInfo $file) use ($componentDirectory) {
                    return $componentDirectory->getViewName($file);
                })
        );
    }

    /**
     * @param iterable|string[] $views
     *
     * @return \Spatie\BladeX\ComponentCollection|\Spatie\BladeX\Component[]
     */
    protected function registerViews(iterable $views): ComponentCollection
    {
        return ComponentCollection::make($views)->map(function (string $viewName) {
            return $this->component($viewName);
        });
    }
}

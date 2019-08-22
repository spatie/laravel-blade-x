<?php

namespace Spatie\BladeX;

use Illuminate\Support\Str;
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
     * @param string $viewDirectory
     *
     * @return \Spatie\BladeX\ComponentCollection|\Spatie\BladeX\Component[]
     */
    public function registerComponents(string $viewDirectory): ComponentCollection
    {
        $componentDirectory = Str::contains($viewDirectory, '::')
            ? new NamespacedDirectory($viewDirectory)
            : new RegularDirectory($viewDirectory);

        return $this->registerViews(
            ComponentCollection::make(File::files($componentDirectory->getAbsoluteDirectory()))
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

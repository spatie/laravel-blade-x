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
            collect($view)->each(function (string $singleView) {
                $this->component($singleView);
            });

            return null;
        }

        if ($view instanceof Component) {
            $this->registeredComponents[$view->tag] = $view;

            return $view;
        }

        if (!is_string($view)) {
            throw CouldNotRegisterComponent::invalidArgument();
        }

        if (ends_with($view, '*')) {
            $this->componentDirectory($view);

            return null;
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

    public function componentDirectory(string $viewDirectory)
    {
        $componentDirectory = new ComponentDirectory($viewDirectory);

        collect(File::allFiles($componentDirectory->getAbsoluteDirectory()))
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

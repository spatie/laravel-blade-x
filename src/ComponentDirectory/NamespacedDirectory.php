<?php

namespace Spatie\BladeX\ComponentDirectory;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Symfony\Component\Finder\SplFileInfo;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;

class NamespacedDirectory extends ComponentDirectory
{
    /** @var string */
    protected $namespace;

    public function __construct(string $viewDirectory)
    {
        [$this->namespace, $viewDirectory] = explode('::', $viewDirectory);
        $this->viewDirectory = trim(Str::before($viewDirectory, '*'), '.');
    }

    public function getAbsoluteDirectory(): string
    {
        $viewPath = str_replace('.', '/', $this->viewDirectory);

        $absoluteDirectory = View::getFinder()->getHints()[$this->namespace][0] ?? null;

        if (! $absoluteDirectory) {
            throw CouldNotRegisterComponent::viewPathNotFound($viewPath);
        }

        return $viewPath ? "{$absoluteDirectory}/{$viewPath}" : $absoluteDirectory;
    }

    public function getViewName(SplFileInfo $viewFile): string
    {
        return "{$this->namespace}::".parent::getViewName($viewFile);
    }
}

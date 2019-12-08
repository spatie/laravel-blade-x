<?php

namespace Spatie\BladeX\ComponentDirectory;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Symfony\Component\Finder\SplFileInfo;

class NamespacedDirectory extends ComponentDirectory
{
    /** @var string */
    protected $namespace;

    public function __construct(string $viewDirectory, bool $includeSubdirectories)
    {
        [$this->namespace, $viewDirectory] = explode('::', $viewDirectory);
        $this->viewDirectory = trim(Str::before($viewDirectory, '*'), '.');
        $this->includeSubdirectories = $includeSubdirectories;
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

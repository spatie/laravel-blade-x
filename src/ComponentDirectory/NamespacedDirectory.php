<?php

namespace Spatie\BladeX\ComponentDirectory;

use Illuminate\Support\Facades\View;
use Symfony\Component\Finder\SplFileInfo;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;

class NamespacedDirectory extends ComponentDirectory
{
    /** @var string */
    protected $namespace;

    /** @var string */
    protected $viewDirectory;

    public function __construct(string $viewDirectory)
    {
        [$this->namespace, $viewDirectory] = explode('::', $viewDirectory);

        $this->viewDirectory = str_before($viewDirectory, '*');
    }

    public function getAbsoluteDirectory(): string
    {
        $viewPath = str_replace('.', '/', $this->viewDirectory);

        $absoluteDirectory = View::getFinder()->getHints()[$this->namespace][0] ?? null;

        if (! $absoluteDirectory) {
            throw CouldNotRegisterComponent::viewPathNotFound($viewPath);
        }

        return $absoluteDirectory;
    }

    public function getViewName(SplFileInfo $viewFile): string
    {
        $view = str_replace_last('.blade.php', '', $viewFile->getFilename());

        $viewDirectory = '';

        if ($this->viewDirectory !== '') {
            $viewDirectory = $this->viewDirectory;
        }

        return "{$this->namespace}::{$viewDirectory}{$view}";
    }
}

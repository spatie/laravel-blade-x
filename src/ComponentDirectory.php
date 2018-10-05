<?php

namespace Spatie\BladeX;

use Illuminate\Support\Facades\View;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Symfony\Component\Finder\SplFileInfo;

class ComponentDirectory
{
    /** @var string */
    protected $viewDirectory;

    public function __construct(string $viewDirectory)
    {
        $this->viewDirectory = str_before($viewDirectory, '.*');
    }

    public function getAbsoluteDirectory(): string
    {
        $viewPath = str_replace('.', '/', $this->viewDirectory);

        $absoluteDirectory = str_contains($viewPath, '::')
            ? $this->getNamespacedAbsoluteDirectory($viewPath)
            : $this->getRegularAbsoluteDirectory($viewPath);

        if (! $absoluteDirectory) {
            throw CouldNotRegisterComponent::viewPathNotFound($viewPath);
        }

        return $absoluteDirectory;
    }

    protected function getNamespacedAbsoluteDirectory(string $viewPath): ?string
    {
        $namespace = str_before($viewPath, '::');

        return View::getFinder()->getHints()[$namespace][0] ?? null;
    }

    protected function getRegularAbsoluteDirectory(string $viewPath): ?string
    {
        return collect(View::getFinder()->getPaths())
            ->map(function(string $path) use ($viewPath) {
                return realpath($path . '/' . $viewPath);
            })
            ->filter()
            ->first();
    }

    public function getViewName(SplFileInfo $viewFile): string
    {
        $view = str_replace_last('.blade.php', '', $viewFile->getFilename());

        return "{$this->viewDirectory}.{$view}";
    }
}
<?php

namespace Spatie\BladeX\ComponentDirectory;

use Illuminate\Support\Facades\View;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Symfony\Component\Finder\SplFileInfo;

class RegularComponentDirectory extends ComponentDirectory
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

    public function getViewName(SplFileInfo $viewFile): string
    {
        $view = str_replace_last('.blade.php', '', $viewFile->getFilename());

        return "{$this->viewDirectory}.{$view}";
    }
}
<?php

namespace Spatie\BladeX\ComponentDirectory;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Symfony\Component\Finder\SplFileInfo;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;

class RegularDirectory extends ComponentDirectory
{
    public function __construct(string $viewDirectory)
    {
        $this->viewDirectory = Str::before($viewDirectory, '.*');
    }

    public function getAbsoluteDirectory(): string
    {
        $viewPath = str_replace('.', '/', $this->viewDirectory);

        $absoluteDirectory = collect(View::getFinder()->getPaths())
            ->map(function (string $path) use ($viewPath) {
                return realpath($path.'/'.$viewPath);
            })
            ->filter()
            ->first();

        if (! $absoluteDirectory) {
            throw CouldNotRegisterComponent::viewPathNotFound($viewPath);
        }

        return $absoluteDirectory;
    }
}

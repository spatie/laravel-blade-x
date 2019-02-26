<?php

namespace Spatie\BladeX\ComponentDirectory;

use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

abstract class ComponentDirectory
{
    abstract public function getAbsoluteDirectory(): string;

    public function getViewName(SplFileInfo $viewFile): string
    {
        $view = Str::replaceLast('.blade.php', '', $viewFile->getFilename());

        return "{$this->viewDirectory}.{$view}";
    }
}

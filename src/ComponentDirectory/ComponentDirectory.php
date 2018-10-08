<?php

namespace Spatie\BladeX\ComponentDirectory;

use Symfony\Component\Finder\SplFileInfo;

abstract class ComponentDirectory
{
    abstract public function getAbsoluteDirectory(): string;

    public function getViewName(SplFileInfo $viewFile): string
    {
        $view = str_replace_last('.blade.php', '', $viewFile->getFilename());

        return "{$this->viewDirectory}.{$view}";
    }
}

<?php

namespace Spatie\BladeX\ComponentDirectory;

use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

abstract class ComponentDirectory
{
    /** @var string */
    protected $viewDirectory;

    abstract public function getAbsoluteDirectory(): string;

    public function getViewName(SplFileInfo $viewFile): string
    {
        $view = Str::replaceLast('.blade.php', '', $viewFile->getFilename());

        return empty($this->viewDirectory) ? $view : "{$this->viewDirectory}.{$view}";
    }
}

<?php

namespace Spatie\BladeX\ComponentDirectory;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

abstract class ComponentDirectory
{
    /** @var string */
    protected $viewDirectory;

    /** @var bool */
    protected $includeSubdirectories;

    abstract public function getAbsoluteDirectory(): string;

    public function getViewName(SplFileInfo $viewFile): string
    {
        $subDirectory = $viewFile->getRelativePath();

        $view = Str::replaceLast('.blade.php', '', $viewFile->getFilename());

        return implode('.', array_filter([
            $this->viewDirectory,
            $subDirectory,
            $view,
        ]));
    }

    public function getFiles(): array
    {
        return $this->includeSubdirectories
            ? File::allFiles($this->getAbsoluteDirectory())
            : File::files($this->getAbsoluteDirectory());
    }
}

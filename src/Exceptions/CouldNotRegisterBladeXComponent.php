<?php

namespace Spatie\BladeX\Exceptions;

use Exception;

class CouldNotRegisterBladeXComponent extends Exception
{
    public static function viewNotFound(string $viewName, string $componentName)
    {
        return new static("Could not register component `{$componentName}` because view `{$viewName}` was not found.");
    }

    public static function componentDirectoryNotFound(string $directory)
    {
        return new static("Could not register the components in directory `{$directory}`, because the directory does not exist.");
    }
}

<?php

namespace Spatie\BladeX\Exceptions;

use Exception;

class CouldNotRegisterComponent extends Exception
{
    public static function componentNotFound(string $componentName, string $classOrView)
    {
        return new static("Could not register component `{$componentName}` because `{$classOrView}` was neither an existing class or and existing Blade view.");
    }

    public static function componentDirectoryNotFound(string $directory)
    {
        return new static("Could not register the components in directory `{$directory}`, because the directory does not exist.");
    }
}
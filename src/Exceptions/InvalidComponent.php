<?php

namespace Spatie\BladeX\Exceptions;

use Exception;

class InvalidComponent extends Exception
{
    public static function notFound(string $componentName, string $classOrView)
    {
        return new static("Could not register component `{$componentName}` because `{$classOrView}` was neither an existing class or and existing Blade view.");
    }
}
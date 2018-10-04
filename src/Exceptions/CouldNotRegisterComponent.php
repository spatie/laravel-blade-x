<?php

namespace Spatie\BladeX\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Arrayable;

class CouldNotRegisterComponent extends Exception
{
    public static function viewNotFound(string $view, string $tag)
    {
        return new static("Could not register component `{$tag}` because view `{$view}` was not found.");
    }

    public static function viewPathNotFound(string $viewPath)
    {
        return new static("Could not register the components in view path `{$viewPath}`, because the directory does not exist.");
    }

    public static function invalidArgument()
    {
        return new static("You passed an invalid argument to `BladeX:component`. A valid argument is either a string, an array or an instance of  `Spatie\BladeX\Component`");
    }

    public static function viewModelNotFound(string $componentName, string $viewModelClass)
    {
        return new static("Could not register component `{$componentName}` because the view model class `{$viewModelClass}` was not found.");
    }

    public static function viewModelNotArrayable(string $componentName, string $viewModelClass)
    {
        return new static("Could not register component `{$componentName}` because the view model class `{$viewModelClass}` does not implement `".Arrayable::class.'`.');
    }
}

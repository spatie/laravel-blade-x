<?php

namespace Spatie\BladeX\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\BladeX\BladeX
 *
 * @method static null|\Spatie\BladeX\Component component($view, string $tag = '')
 * @method static \Spatie\BladeX\Component[] registeredComponents()
 * @method static \Spatie\BladeX\BladeX prefix(string $prefix = '')
 * @method static string getPrefix()
 * @method static \Spatie\BladeX\ComponentCollection|\Spatie\BladeX\Component[] registerComponents(string $viewDirectory)
 */
class BladeX extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'blade-x';
    }
}

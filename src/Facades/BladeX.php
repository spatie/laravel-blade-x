<?php

namespace Spatie\BladeX\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\BladeX\BladeXClass
 */
class BladeX extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'blade-x';
    }
}

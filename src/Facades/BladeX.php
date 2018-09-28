<?php

namespace Spatie\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\BladeX\BladeXClass
 */
class BladeX extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'BladeX';
    }
}

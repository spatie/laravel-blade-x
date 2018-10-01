<?php

namespace Spatie\BladeX\Exceptions;

use Exception;
use Spatie\BladeX\BladeXComponent;

class CouldNotParseComponent extends Exception
{
    public static function invalidHtml(string $componentHtml, BladeXComponent $bladeXComponent)
    {
        return new static("Could not parse a usage of BladeX component `{$bladeXComponent->name}` that uses the `{$bladeXComponent->bladeViewName}` view because of invalid html. Html found: `{$componentHtml}`.");
    }
}

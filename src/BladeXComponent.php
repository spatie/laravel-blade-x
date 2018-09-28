<?php

namespace Spatie\BladeX;

class BladeXComponent
{
    /** @var string */
    public $name;

    /** @var string */
    public $bladeViewName;

    public function __construct(string $name, string $bladeViewName)
    {
        $this->name = $name;

        $this->bladeViewName = $bladeViewName;
    }
}

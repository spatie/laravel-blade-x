<?php

namespace Spatie\BladeX;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\View\View;

class BladeViewComponent
{
    /** @var string */
    protected $view;

    public function __construct(string $view)
    {
        $this->view = $view;
    }

    public function __toString()
    {
        // <test-component>
        //</test-component>

        return "@include({$this->view})";
    }
}
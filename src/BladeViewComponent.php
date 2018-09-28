<?php

namespace Spatie\BladeX;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\View\View;

class BladeViewComponent implements Htmlable
{
    /** @var \Illuminate\View\View */
    protected $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function toHtml()
    {
        return $this->view->render();
    }
}
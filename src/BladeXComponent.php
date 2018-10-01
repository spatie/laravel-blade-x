<?php

namespace Spatie\BladeX;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\BladeX\Exceptions\CouldNotRegisterBladeXComponent;

class BladeXComponent
{
    /** @var string */
    public $name;

    /** @var string */
    public $bladeViewName;

    /** @var string */
    public $viewModelClass;

    public function __construct(string $name, string $bladeViewName)
    {
        $this->name = $name;

        $this->bladeViewName = $bladeViewName;
    }

    public function viewModel(string $viewModelClass)
    {
        if (! class_exists($viewModelClass)) {
            throw CouldNotRegisterBladeXComponent::viewModelNotFound($this->name, $viewModelClass);
        }

        if (! is_a($viewModelClass, Arrayable::class, true)) {
            throw CouldNotRegisterBladeXComponent::viewModelNotArrayable($this->name, $viewModelClass);
        }

        $this->viewModelClass = $viewModelClass;

        return $this;
    }
}

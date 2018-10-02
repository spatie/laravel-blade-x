<?php

namespace Spatie\BladeX;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\BladeX\Exceptions\CouldNotRegisterBladeXComponent;

class BladeXComponent
{
    /** @var string */
    public $bladeViewName;

    /** @var string */
    public $name;

    /** @var string */
    public $viewModelClass;

    public static function make(string $bladeViewName, string $name = '')
    {
        return new BladeXComponent($bladeViewName, $name = '');
    }

    public function __construct(string $bladeViewName, string $name = '')
    {
        $bladeViewName = str_replace('.', '/', $bladeViewName);

        if ($name === '') {
            $baseComponentName = explode('/', $bladeViewName);

            $name = kebab_case(end($baseComponentName));
        }

        if (! view()->exists($bladeViewName)) {
            throw CouldNotRegisterBladeXComponent::viewNotFound($bladeViewName, $name);
        }

        $this->bladeViewName = $bladeViewName;

        $this->name = $name;
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

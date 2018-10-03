<?php

namespace Spatie\BladeX;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\BladeX\Exceptions\CouldNotRegisterBladeXComponent;

class BladeXComponent
{
    /** @var string */
    public $bladeViewName;

    /** @var string */
    public $name;

    /** @var string|Closure */
    public $viewModel;

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

    /**
     * @param string|\Closure $viewModel
     *
     * @return $this
     */
    public function viewModel($viewModel)
    {

        if (! is_callable($viewModel)) {
            if (!class_exists($viewModel)) {
                throw CouldNotRegisterBladeXComponent::viewModelNotFound($this->name, $viewModel);
            }

            if (!is_a($viewModel, Arrayable::class, true)) {
                throw CouldNotRegisterBladeXComponent::viewModelNotArrayable($this->name, $viewModel);
            }
        }

        $this->viewModel = $viewModel;

        return $this;
    }
}

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

    /** @var string */
    public $viewModel;

    protected static $callableViewModelCount = 0;

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

        if (!view()->exists($bladeViewName)) {
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
        if (is_callable($viewModel)) {
            $viewModel = $this->convertCallableToViewModel($viewModel);

            $this->viewModel = $viewModel;

            return $this;
        }

        if (!class_exists($viewModel)) {
            throw CouldNotRegisterBladeXComponent::viewModelNotFound($this->name, $viewModel);
        }

        if (!is_a($viewModel, Arrayable::class, true)) {
            throw CouldNotRegisterBladeXComponent::viewModelNotArrayable($this->name, $viewModel);
        }

        $this->viewModel = $viewModel;

        return $this;
    }

    protected function convertCallableToViewModel(Closure $closure): string
    {
        $viewModel = new class implements Arrayable
        {
            public static $closure;

            public $arguments = [];

            public function __construct(...$arguments)
            {
                $this->arguments = $arguments[0] ?? [];
            }

            public function toArray(): array
            {
                return app()->call(static::$closure, $this->arguments);
            }
        };

        $viewModel::$closure = $closure;

        $viewModelClassName = 'bladex-view-model-' . static::$callableViewModelCount++;

        app()->bind($viewModelClassName, function ($app, $arguments) use ($viewModel) {
            return new $viewModel($arguments ?? []);
        });

        return $viewModelClassName;
    }
}

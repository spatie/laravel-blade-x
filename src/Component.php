<?php

namespace Spatie\BladeX;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;

class Component
{
    /** @var string */
    public $view;

    /** @var string */
    public $tag;

    /** @var string */
    public $viewModel;

    public static function make(string $view, string $tag = '')
    {
        return new static($view, $tag);
    }

    public function __construct(string $view, string $tag = '')
    {
        if ($tag === '') {
            $tag = $this->determineDefaultTag($view);
        }

        if (! view()->exists($view)) {
            throw CouldNotRegisterComponent::viewNotFound($view, $tag);
        }

        $this->view = $view;

        $this->tag = $tag;
    }

    public function tag(string $tag)
    {
        $this->tag = $tag;

        return $this;
    }

    public function viewModel(string $viewModel)
    {
        if (! class_exists($viewModel)) {
            throw CouldNotRegisterComponent::viewModelNotFound($this->tag, $viewModel);
        }

        if (! is_a($viewModel, Arrayable::class, true)) {
            throw CouldNotRegisterComponent::viewModelNotArrayable($this->tag, $viewModel);
        }

        $this->viewModel = $viewModel;

        return $this;
    }

    protected function determineDefaultTag(string $view): string
    {
        $baseComponentName = explode('.', $view);

        $tag = kebab_case(end($baseComponentName));

        return $tag;
    }
}

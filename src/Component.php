<?php

namespace Spatie\BladeX;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;

class Component
{
    /** @var BladeX */
    protected $bladeX;

    /** @var string */
    public $view;

    /**
     * @var string
     * @internal
     * @see Component::getTag()
     */
    public $tag;

    /** @var string */
    public $viewModel;

    /** @var string */
    protected $prefix;

    /** @var bool */
    protected $withNamespace;

    public static function make(string $view, string $tag = '', string $prefix = '', bool $withNamespace = true)
    {
        return new static($view, $tag, $prefix, $withNamespace);
    }

    public function __construct(string $view, string $tag = '', string $prefix = '', bool $withNamespace = true)
    {
        $this->view = $view;

        $this->tag = $tag;

        $this->prefix = $prefix;

        $this->withNamespace = $withNamespace;

        $this->bladeX = app(BladeX::class);
    }

    public function tag(string $tag)
    {
        $this->tag = $tag;

        return $this;
    }

    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function withoutNamespace()
    {
        $this->withNamespace = false;

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

    public function getTag(): string
    {
        $tag = empty($this->prefix) ? $this->bladeX->getPrefix() : Str::finish($this->prefix, '-');

        $tag .= empty($this->tag) ? $this->determineDefaultTag() : $this->tag;

        return $tag;
    }

    protected function determineDefaultTag(): string
    {
        $baseComponentName = explode('.', $this->view);

        $tag = Str::kebab(end($baseComponentName));

        if (Str::contains($tag, '_')) {
            $tag = str_replace('_', '-', $tag);
        }

        if (Str::contains($this->view, '::') && ! Str::contains($tag, '::')) {
            $namespace = Str::before($this->view, '::');
            $tag = "{$namespace}::{$tag}";
        }

        if (! $this->withNamespace && Str::contains($tag, '::')) {
            $tag = Str::after($tag, '::');
        }

        return $tag;
    }
}

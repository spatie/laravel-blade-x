<?php

namespace Spatie\BladeX;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

class ClosureViewModel implements Arrayable
{
    /** @var callable */
    protected $closure;

    /** @var callable */
    protected $arguments;

    public function __construct(...$arguments)
    {
        $this->arguments = $arguments[0] ?? [];
    }

    public function withClosure(Closure $closure)
    {
        $this->closure = $closure;

        return $this;
    }

    public function toArray(): array
    {
        return app()->call($this->closure, $this->arguments);
    }
}

<?php

namespace Spatie\BladeX;

class ContextStack
{
    /** @var array */
    protected $stack = [];

    public function push(array $data)
    {
        $this->stack[] = array_merge($this->read(), $data);
    }

    public function read(): array
    {
        return array_last($this->stack) ?? [];
    }

    public function pop()
    {
        array_pop($this->stack);
    }

}

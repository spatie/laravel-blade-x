<?php

namespace Spatie\BladeX;

class Context
{
    /** @var array */
    protected $data = [];

    public function start(array $definedVars)
    {
        $data = array_except(
            $definedVars,
            ['__path', '__data', 'obLevel', '__env', 'app', 'slot']
        );

        $previousData = array_last($this->data) ?? [];

        $this->data[] = array_merge($previousData, $data);
    }

    public function end()
    {
        array_pop($this->data);
    }

    public function read()
    {
        return array_last($this->data) ?? [];
    }
}

<?php

namespace Spatie\BladeX;

use Illuminate\Support\Collection;

/**
 * @property-read Component $each
 */
class ComponentCollection extends Collection
{
    public function prefix(string $prefix)
    {
        $this->each->prefix($prefix);

        return $this;
    }

    public function withoutNamespace()
    {
        $this->each->withoutNamespace();

        return $this;
    }
}

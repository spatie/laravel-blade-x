<?php

namespace Spatie\BladeX\Tests\TestClasses;

use Spatie\BladeX\BladeXViewModel;

class SelectViewModel extends BladeXViewModel
{
    /** @var string */
    public $name;

    /** @var array */
    public $options;

    /** @var string */
    public $selected;

    public function __construct(string $name, array $options, string $selected = null)
    {
        $this->name = $name;

        $this->options = $options;

        $this->selected = $selected;
    }

    public function isSelected(string $optionName): bool
    {
        return $optionName === $this->selected;
    }
}

<?php

namespace Spatie\BladeX\Tests\Features\ViewModel\TestClasses;

use Spatie\BladeX\ViewModel;

class SelectFieldViewModel extends ViewModel
{
    /** @var string */
    public $name;

    /** @var array */
    public $options;

    /** @var string */
    private $selected;

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

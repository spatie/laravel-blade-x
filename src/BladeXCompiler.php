<?php

namespace Spatie\BladeX;

use SimpleXMLElement;

class BladeXCompiler
{
    /** @var \Spatie\BladeX\BladeX */
    protected $bladeX;

    public function __construct(BladeX $bladeX)
    {
        return $this->bladeX = $bladeX;
    }

    public function compile(string $viewContents): string
    {
        return array_reduce(
            $this->bladeX->getRegisteredComponents(),
            [$this, 'replaceBladeXComponentWithRegularBladeComponent'],
            $viewContents);
    }

    protected function replaceBladeXComponentWithRegularBladeComponent(string $viewContents, BladeXComponent $bladeXComponent)
    {
        $pattern = "/<\s*{$bladeXComponent->name}[^>]*>((.|\n)*?)<\s*\/\s*{$bladeXComponent->name}>/m";

        return preg_replace_callback($pattern, function (array $regexResult) use ($bladeXComponent) {
            [$componentHtml, $componentInnerHtml] = $regexResult;

            return "@component('{$bladeXComponent->bladeViewName}', [{$this->getComponentAttributes($componentHtml)}])"
                . $this->parseComponentInnerHtml($componentInnerHtml)
                . '@endcomponent';
        }, $viewContents);
    }

    protected function getComponentAttributes(string $componentHtml): string
    {
        $componentXml = new SimpleXMLElement($componentHtml);

        return collect($componentXml->attributes())
            ->map(function ($value, $attribute) {
                $value = str_replace("'", "\\'", $value);

                return "'{$attribute}' => '{$value}',";
            })->implode('');
    }

    protected function parseComponentInnerHtml(string $componentInnerHtml): string
    {
        $pattern = '/<\s*slot[^>]*name=[\'"](.*)[\'"][^>]*>((.|\n)*?)<\s*\/\s*slot>/m';

        return preg_replace_callback($pattern, function ($result) {
            [$slot, $name, $contents] = $result;

            return "@slot('{$name}'){$contents}@endslot";
        }, $componentInnerHtml);
    }
}

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
            [$this, 'parseComponentHtml'],
            $viewContents
        );
    }

    protected function parseComponentHtml(string $viewContents, BladeXComponent $bladeXComponent)
    {
        $viewContents = $this->parseSlots($viewContents);

        $viewContents = $this->parseSelfClosingTags($viewContents, $bladeXComponent);

        $viewContents = $this->parseOpeningTags($viewContents, $bladeXComponent);

        $viewContents = $this->parseClosingTags($viewContents, $bladeXComponent);

        return $viewContents;
    }

    protected function parseSelfClosingTags(string $viewContents, BladeXComponent $bladeXComponent): string
    {
        $prefix = $this->bladeX->getPrefix();

        $pattern = "/<\s*{$prefix}{$bladeXComponent->name}[^>]*\/>/m";

        return preg_replace_callback($pattern, function (array $regexResult) use ($bladeXComponent) {
            $componentHtml = $regexResult[0];

            return $this->componentString($bladeXComponent, $componentHtml);
        }, $viewContents);
    }

    protected function parseOpeningTags(string $viewContents, BladeXComponent $bladeXComponent): string
    {
        $prefix = $this->bladeX->getPrefix();

        $pattern = "/<\s*{$prefix}{$bladeXComponent->name}[^>]*(?<!\/)>/m";

        return preg_replace_callback($pattern, function (array $regexResult) use ($bladeXComponent) {
            $componentHtml = $regexResult[0];

            $attributes = $this->getComponentAttributes($bladeXComponent, $componentHtml);

            return $this->componentStartString($bladeXComponent, $attributes);
        }, $viewContents);
    }

    protected function parseClosingTags(string $viewContents, BladeXComponent $bladeXComponent): string
    {
        $prefix = $this->bladeX->getPrefix();

        $pattern = "/<\/\s*{$prefix}{$bladeXComponent->name}[^>]*>/m";

        return preg_replace($pattern, $this->componentEndString(), $viewContents);
    }

    protected function componentString(BladeXComponent $bladeXComponent, string $componentHtml): string
    {
        $attributes = $this->getComponentAttributes($bladeXComponent, $componentHtml);

        return $this->componentStartString($bladeXComponent, $attributes).$this->componentEndString();
    }

    protected function componentStartString(BladeXComponent $bladeXComponent, string $attributes = ''): string
    {
        return  "@component('{$bladeXComponent->bladeViewName}', [{$attributes}])";
    }

    protected function componentEndString(): string
    {
        return '@endcomponent';
    }

    protected function getComponentAttributes(BladeXComponent $bladeXComponent, string $componentHtml): string
    {
        $prefix = $this->bladeX->getPrefix();

        $elementName = $prefix.$bladeXComponent->name;

        if ($this->isOpeningHtmlTag($elementName, $componentHtml)) {
            $componentHtml .= "</{$elementName}>";
        }

        return $this->getHtmlElementAttributes($componentHtml);
    }

    protected function getHtmlElementAttributes(string $componentHtml): string
    {
        $componentXml = new SimpleXMLElement($componentHtml);

        return collect($componentXml->attributes())
            ->map(function ($value, $attribute) {
                if (preg_match('/{{(.*)}}/', $value, $matches)) {
                    $value = trim($matches[1]);

                    return "'{$attribute}' => {$value},";
                }

                $value = str_replace("'", "\\'", $value);

                return "'{$attribute}' => '{$value}',";
            })->implode('');
    }

    protected function parseSlots(string $viewContents): string
    {
        $pattern = '/<\s*slot[^>]*name=[\'"](.*)[\'"][^>]*>((.|\n)*?)<\s*\/\s*slot>/m';

        return preg_replace_callback($pattern, function ($regexResult) {
            [$slot, $name, $contents] = $regexResult;

            return "@slot('{$name}'){$contents}@endslot";
        }, $viewContents);
    }

    protected function isOpeningHtmlTag(string $tagName, string $html): bool
    {
        return ! ends_with($html, ["</{$tagName}>", '/>']);
    }
}

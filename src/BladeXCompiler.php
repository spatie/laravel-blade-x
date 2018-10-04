<?php

namespace Spatie\BladeX;

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

        $pattern = "/<\s*{$prefix}{$bladeXComponent->name}\s*(.*)\s*\/>/m";

        return preg_replace_callback($pattern, function (array $regexResult) use ($bladeXComponent) {
            [$componentHtml, $attributesString] = $regexResult;

            $attributes = $this->getAttributesFromAttributeString($attributesString);

            return $this->componentString($bladeXComponent, $attributes);
        }, $viewContents);
    }

    protected function parseOpeningTags(string $viewContents, BladeXComponent $bladeXComponent): string
    {
        $prefix = $this->bladeX->getPrefix();

        $pattern = "/<\s*{$prefix}{$bladeXComponent->name}((?:\s+[\w\-:]*=(?:\\\"(?:.*?)\\\"|\'(?:.*)\'|[^\'\\\"=<>]*))*\s*)(?<![\/=\-])>/m";

        return preg_replace_callback($pattern, function (array $regexResult) use ($bladeXComponent) {
            [$componentHtml, $attributesString] = $regexResult;

            $attributes = $this->getAttributesFromAttributeString($attributesString);

            return $this->componentStartString($bladeXComponent, $attributes);
        }, $viewContents);
    }

    protected function parseClosingTags(string $viewContents, BladeXComponent $bladeXComponent): string
    {
        $prefix = $this->bladeX->getPrefix();

        $pattern = "/<\/\s*{$prefix}{$bladeXComponent->name}[^>]*>/m";

        return preg_replace($pattern, $this->componentEndString($bladeXComponent), $viewContents);
    }

    protected function componentString(BladeXComponent $bladeXComponent, array $attributes = []): string
    {
        return $this->componentStartString($bladeXComponent, $attributes).$this->componentEndString($bladeXComponent);
    }

    protected function componentStartString(BladeXComponent $bladeXComponent, array $attributes = []): string
    {
        $attributesString = $this->attributesToString($attributes);

        $componentAttributeString = "[{$attributesString}]";

        if ($bladeXComponent->bladeViewName === 'bladex::context') {
            return "@php(app(Spatie\BladeX\ContextStack::class)->push({$componentAttributeString}))";
        }

        if ($bladeXComponent->viewModel) {
                $componentAttributeString = "
                array_merge(
                    app(Spatie\BladeX\ContextStack::class)->read(),
                    {$componentAttributeString},
                    app(
                        '{$bladeXComponent->viewModel}',
                        array_merge(
                            app(Spatie\BladeX\ContextStack::class)->read(),
                            {$componentAttributeString}
                        )
                    )->toArray()
                )";
        }

        return "@component(
           '{$bladeXComponent->bladeViewName}',
           array_merge(app(Spatie\BladeX\ContextStack::class)->read(), {$componentAttributeString}))";
    }

    protected function componentEndString(BladeXComponent $bladeXComponent): string
    {
        if ($bladeXComponent->bladeViewName === 'bladex::context') {
            return "@php(app(Spatie\BladeX\ContextStack::class)->pop())";
        }

        return ' @endcomponent';
    }

    protected function getAttributesFromAttributeString(string $attributeString): array
    {
        $attributeString = $this->parseBindAttributes($attributeString);

        $pattern = '/(?<attribute>[\w:-]+)(=(?<value>(\"[^\"]+\"|\\\'[^\\\']+\\\'|[^\s>]+)))?/';

        if (! preg_match_all($pattern, $attributeString, $matches, PREG_SET_ORDER)) {
            return [];
        }

        return collect($matches)->mapWithKeys(function ($match) {
            $attribute = camel_case($match['attribute']);
            $value = $match['value'] ?? null;

            if (is_null($value)) {
                $value = 'true';
                $attribute = str_start($attribute, 'bind:');
            }

            if (starts_with($value, ['"', '\''])) {
                $value = substr($value, 1, -1);
            }

            if (! starts_with($attribute, 'bind:')) {
                $value = str_replace("'", "\\'", $value);
                $value = "'{$value}'";
            }

            if (starts_with($attribute, 'bind:')) {
                $attribute = str_after($attribute, 'bind:');
            }

            return [$attribute => $value];
        })->toArray();
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

    protected function parseBindAttributes(string $html): string
    {
        return preg_replace("/\s+:([\w-]+)=/m", ' bind:$1=', $html);
    }

    protected function attributesToString(array $attributes): string
    {
        return collect($attributes)
            ->map(function (string $value, string $attribute) {
                return "'{$attribute}' => {$value}";
            })
            ->implode(',');
    }
}

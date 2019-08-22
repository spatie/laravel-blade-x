<?php

namespace Spatie\BladeX;

use Illuminate\Support\Str;

class Compiler
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
            $this->bladeX->registeredComponents(),
            [$this, 'parseComponentHtml'],
            $viewContents
        );
    }

    protected function parseComponentHtml(string $viewContents, Component $component)
    {
        $viewContents = $this->parseSlots($viewContents);

        $viewContents = $this->parseSelfClosingTags($viewContents, $component);

        $viewContents = $this->parseOpeningTags($viewContents, $component);

        $viewContents = $this->parseClosingTags($viewContents, $component);

        return $viewContents;
    }

    protected function parseSelfClosingTags(string $viewContents, Component $component): string
    {
        $pattern = "/<\s*{$component->getTag()}\s*(?<attributes>(?:\s+[\w\-:]+(=(?:\\\"[^\\\"]+\\\"|\'[^\']+\'|[^\'\\\"=<>]+))?)*\s*)\/>/";

        return preg_replace_callback($pattern, function (array $matches) use ($component) {
            $attributes = $this->getAttributesFromAttributeString($matches['attributes']);

            return $this->componentString($component, $attributes);
        }, $viewContents);
    }

    protected function parseOpeningTags(string $viewContents, Component $component): string
    {
        $pattern = "/<\s*{$component->getTag()}(?<attributes>(?:\s+[\w\-:]+(=(?:\\\"[^\\\"]*\\\"|\'[^\']*\'|[^\'\\\"=<>]+))?)*\s*)(?<![\/=\-])>/";

        return preg_replace_callback($pattern, function (array $matches) use ($component) {
            $attributes = $this->getAttributesFromAttributeString($matches['attributes']);

            return $this->componentStartString($component, $attributes);
        }, $viewContents);
    }

    protected function parseClosingTags(string $viewContents, Component $component): string
    {
        $pattern = "/<\/\s*{$component->getTag()}\s*>/";

        return preg_replace($pattern, $this->componentEndString($component), $viewContents);
    }

    protected function componentString(Component $component, array $attributes = []): string
    {
        return $this->componentStartString($component, $attributes).$this->componentEndString($component);
    }

    protected function componentStartString(Component $component, array $attributes = []): string
    {
        $attributesString = $this->attributesToString($attributes);

        $componentAttributeString = "[{$attributesString}]";

        if ($component->view === 'bladex::context') {
            return " @php(app(Spatie\BladeX\ContextStack::class)->push({$componentAttributeString})) ";
        }

        if ($component->viewModel) {
            $componentAttributeString = "
                array_merge(
                    app(Spatie\BladeX\ContextStack::class)->read(),
                    {$componentAttributeString},
                    app(
                        '{$component->viewModel}',
                        array_merge(
                            app(Spatie\BladeX\ContextStack::class)->read(),
                            {$componentAttributeString}
                        )
                    )->toArray()
                )";
        }

        return " @component(
           '{$component->view}',
           array_merge(app(Spatie\BladeX\ContextStack::class)->read(),
           {$componentAttributeString})
        ) ";
    }

    protected function componentEndString(Component $component): string
    {
        if ($component->view === 'bladex::context') {
            return "@php(app(Spatie\BladeX\ContextStack::class)->pop())";
        }

        return ' @endcomponent ';
    }

    protected function getAttributesFromAttributeString(string $attributeString): array
    {
        $attributeString = $this->parseBindAttributes($attributeString);

        $pattern = '/(?<attribute>[\w:-]+)(=(?<value>(\"[^\"]+\"|\\\'[^\\\']+\\\'|[^\s>]+)))?/';

        if (! preg_match_all($pattern, $attributeString, $matches, PREG_SET_ORDER)) {
            return [];
        }

        return collect($matches)->mapWithKeys(function ($match) {
            $attribute = Str::camel($match['attribute']);
            $value = $match['value'] ?? null;

            if (is_null($value)) {
                $value = 'true';
                $attribute = Str::start($attribute, 'bind:');
            }

            $value = $this->stripQuotes($value);

            if (Str::startsWith($attribute, 'bind:')) {
                $attribute = Str::after($attribute, 'bind:');
            } else {
                $value = str_replace("'", "\\'", $value);
                $value = "'{$value}'";
            }

            return [$attribute => $value];
        })->toArray();
    }

    protected function parseSlots(string $viewContents): string
    {
        $openingPattern = '/<\s*slot\s+name=(?<name>(\"[^\"]+\"|\\\'[^\\\']+\\\'|[^\s>]+))\s*>/';
        $viewContents = preg_replace_callback($openingPattern, function ($matches) {
            $name = $this->stripQuotes($matches['name']);

            return " @slot('{$name}') ";
        }, $viewContents);

        $closingPattern = '/<\/\s*slot[^>]*>/';
        $viewContents = preg_replace($closingPattern, ' @endslot', $viewContents);

        return $viewContents;
    }

    protected function parseBindAttributes(string $attributeString): string
    {
        return preg_replace("/\s*:([\w-]+)=/m", ' bind:$1=', $attributeString);
    }

    protected function attributesToString(array $attributes): string
    {
        return collect($attributes)
            ->map(function (string $value, string $attribute) {
                return "'{$attribute}' => {$value}";
            })
            ->implode(',');
    }

    protected function stripQuotes(string $string): string
    {
        if (Str::startsWith($string, ['"', '\''])) {
            return substr($string, 1, -1);
        }

        return $string;
    }
}

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

    public function compile(string $view): string
    {
        foreach ($this->bladeX->getRegisteredComponents() as $componentName => $classOrView) {
            $pattern = '/<\s*'.$componentName.'[^>]*>((.|\n)*?)<\s*\/\s*'.$componentName.'>/m';

            $view = preg_replace_callback($pattern, function ($result) use ($classOrView) {
                [$component, $contents] = $result;

                $xml = new SimpleXMLElement($component);

                $data = '[';

                foreach ($xml->attributes() as $attribute => $value) {
                    $value = str_replace("'","\\'", $value);
                    $data .= "'{$attribute}' => '{$value}',";
                }

                $data .= ']';

                $pattern = '/<\s*slot[^>]*name=[\'"](.*)[\'"][^>]*>((.|\n)*?)<\s*\/\s*slot>/m';

                $contents = preg_replace_callback($pattern, function ($result) {
                    [$slot, $name, $contents] = $result;

                    return "@slot('{$name}'){$contents}@endslot";
                }, $contents);

                return "@component('{$classOrView}', {$data})".$contents."@endcomponent";
            }, $view);
        }

        return $view;
    }
}
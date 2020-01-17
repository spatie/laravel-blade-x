<?php

namespace Spatie\BladeX;

use Illuminate\Support\Str;

class Html
{
    public static function getAttributeString(array $attributes): string
    {
        $finalAttributes = [];

        foreach ($attributes as $attribute => $value) {
            if (is_string($value) || is_numeric($value)) {
                $finalAttributes[] = $attribute.'="'.$value.'"';
            } elseif ($value === true) {
                $finalAttributes[] = $attribute;
            } elseif ($value === false) {
                $finalAttributes[] = $attribute.'="false"';
            } elseif (is_object($value) && method_exists($value, '__toString')) {
                $finalAttributes[] = $attribute.'="'.$value->__toString().'"';
            }
        }

        return implode(' ', $finalAttributes);
    }

    public static function getAttributeStringForInheritedProperties(array $attributes): string
    {
        $attributes = array_filter($attributes, function ($key) {
            return strpos($key, 'inherited_') === 0;
        }, ARRAY_FILTER_USE_KEY);

        $finalAttributes = [];

        foreach ($attributes as $key => $value) {
            $key = Str::kebab(Str::after($key, 'inherited_'));

            $finalAttributes[$key] = $value;
        }

        return static::getAttributeString($finalAttributes);
    }
}

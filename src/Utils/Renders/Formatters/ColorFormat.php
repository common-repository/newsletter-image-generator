<?php

namespace Oaattia\WoocommerceGenerator\Utils\Renders\Formatters;

class ColorFormat implements Formatter
{
    /**
     * Check if the color is prefixed with #, if not put
     *
     * @param string $color
     *
     * @return string
     */
    public static function get($color)
    {
        // check if the color without # then add it
        if(!strpos( $color, '#')) {
            $color = ltrim($color, '#');
            return (string) sprintf('#%s', $color);
        }

        return (string) $color;
    }
}
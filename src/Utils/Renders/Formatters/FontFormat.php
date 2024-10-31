<?php

namespace Oaattia\WoocommerceGenerator\Utils\Renders\Formatters;

class FontFormat implements Formatter
{
    /**
     * Check if the fontsize is suffixed by px
     * remove it if it's already there
     *
     * @param string $fontSize
     *
     * @return string
     */
    public static function get($fontSize)
    {
        return (int) $fontSize;
    }
}
<?php

namespace Oaattia\WoocommerceGenerator\Utils\Renders\Formatters;

interface Formatter
{
    /**
     * @param string $string
     *
     * @return string
     */
    public static function get($string);
}
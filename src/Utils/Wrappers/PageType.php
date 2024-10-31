<?php

namespace Oaattia\WoocommerceGenerator\Utils\Wrappers;

class PageType
{
    /**
     * Get the current page type
     *
     * @return string
     */
    public function get()
    {
        $screen = get_current_screen();

        if($screen) {
            return $screen->id;
        }

        return '';
    }
}
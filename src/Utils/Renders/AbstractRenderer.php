<?php

namespace Oaattia\WoocommerceGenerator\Utils\Renders;

use Intervention\Image\Image;

abstract class AbstractRenderer
{
    const DRIVER = ['driver' => 'imagick'];

    /**
     * @var Image
     */
    protected $imageInstance;

    /**
     * @var array
     */
    protected $options = [];

    public function __construct($image, $options)
    {
        $this->imageInstance = $image;
        $this->options = $options;
    }

}
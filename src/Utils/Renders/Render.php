<?php

namespace Oaattia\WoocommerceGenerator\Utils\Renders;

use Intervention\Image\ImageManager;
use Oaattia\WoocommerceGenerator\Utils\FileSaver;

class Render
{
    /**
     * @var array
     *
     * Structure of the array of options must be
     *
     * Ex:
     * array(6) {
         ["topLeft"]=>
            array(3) {
            ["text"]=>
            string(17) "This is some text"
            ["textColor"]=>
            string(6) "FFFFFF"
            ["textSize"]=>
            string(2) "16"
            }
         ["left"]=>
            array(2) {
            ["textColor"]=>
            string(6) "FFFFFF"
            ["textSize"]=>
            string(2) "16"
            }
         ["bottomLeft"]=>
            array(2) {
            ["textColor"]=>
            string(6) "FFFFFF"
            ["textSize"]=>
            string(2) "16"
            }
         ["topRight"]=>
            array(2) {
            ["textColor"]=>
            string(6) "FFFFFF"
            ["textSize"]=>
            string(2) "16"
            }
         ["right"]=>
            array(2) {
            ["textColor"]=>
            string(6) "FFFFFF"
            ["textSize"]=>
            string(2) "16"
            }
         ["bottomRight"]=>
            array(2) {
            ["textColor"]=>
            string(6) "FFFFFF"
            ["textSize"]=>
            string(2) "16"
            }
        }
     */
    private $options = [];

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param int $productId
     *
     * @return string|null
     */
    public function createImageAndSave($productId)
    {
        $imageUrlBeforeRender = get_the_post_thumbnail_url($productId);

        if (!$imageUrlBeforeRender) {
            return null;
        }

        // Generate image
        $imageRender = new ImageRender(new ImageManager(['driver' => ImageRender::DRIVER]));
        $image = $imageRender->createImage($imageUrlBeforeRender, $this->options);

        // Save generated image
        $file = new FileSaver($image, basename($imageUrlBeforeRender));
        return $file->saveGeneratedImage($productId);
    }
}

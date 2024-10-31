<?php

namespace Oaattia\WoocommerceGenerator\Utils\Renders;

use Intervention\Image\ImageManager;
use Oaattia\WoocommerceGenerator\Utils\Renders\Elements\ImageRenderer;
use Oaattia\WoocommerceGenerator\Utils\Renders\Elements\TextRenderer;

class ImageRender
{
    const DRIVER = 'imagick';

    /**
     * @var ImageManager
     */
    private $manager;

    public function __construct(ImageManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param string $imageUrl
     *
     * @param array  $options
     *
     * @return \Intervention\Image\Image
     */
    public function createImage($imageUrl, array $options)
    {
        $image = $this->manager->make($imageUrl);

        $renders = [
            new TextRenderer($image, $options),
            new ImageRenderer($image, $options),
        ];

        /** @var Renderer $render */
        foreach ($renders as $render) {
            $render->prepareImage();
        }


        $imageWidth = isset($_GET['width']) ? intval($_GET['width']) : null;
        $imageHeight = isset($_GET['height']) ? intval($_GET['height']) : null;

        if($imageWidth || $imageHeight) {
            return $image->resize(
                $imageWidth ?: null,
                $imageHeight ?: null
            );
        }

        return $image;
    }
}
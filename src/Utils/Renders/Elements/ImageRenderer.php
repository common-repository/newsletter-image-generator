<?php

namespace Oaattia\WoocommerceGenerator\Utils\Renders\Elements;

use Intervention\Image\ImageManagerStatic;
use Oaattia\WoocommerceGenerator\Utils\Renders\AbstractRenderer;
use Oaattia\WoocommerceGenerator\Utils\Renders\Renderer;

class ImageRenderer extends AbstractRenderer implements Renderer
{
    public function prepareImage()
    {
        if($this->options) {
            foreach ($this->options as $position => $option) {
               if(
                   isset($option['imageUrl']) &&
                   !empty($option['imageUrl']) &&
                   is_array(getimagesize($option['imageUrl']))  // check if image is valid url image
               ) {
                   $sluggedPosition = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $position));

                   ImageManagerStatic::configure(self::DRIVER);

                   $imageData = file_get_contents($option['imageUrl']);

                   $imageBase4Encoded = base64_encode($imageData);

                   $imageToInsert = ImageManagerStatic::make($imageBase4Encoded);

                   $this->imageInstance->insert(
                       $imageToInsert,
                       $sluggedPosition,
                       (int) $option['imageXoffset'],
                       (int) $option['imageYoffset']
                   );
               }
            }
        }

    }
}
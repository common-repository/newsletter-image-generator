<?php

namespace Oaattia\WoocommerceGenerator\Utils\Renders\Elements;

use Intervention\Image\ImageManagerStatic;
use Intervention\Image\Imagick\Font;
use Oaattia\WoocommerceGenerator\Generator;
use Oaattia\WoocommerceGenerator\Utils\Renders\AbstractRenderer;
use Oaattia\WoocommerceGenerator\Utils\Renders\Formatters\ColorFormat;
use Oaattia\WoocommerceGenerator\Utils\Renders\Formatters\CustomText;
use Oaattia\WoocommerceGenerator\Utils\Renders\Formatters\FontFormat;
use Oaattia\WoocommerceGenerator\Utils\Renders\Renderer;

class TextRenderer extends AbstractRenderer implements Renderer
{
    public function prepareImage()
    {
        if($this->options) {
            foreach ($this->options as $position => $option) {
                // convert the camelCase position to slug-case string
                $sluggedPosition = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $position));

                $texts = $this->collectImagesTexts($option);

                foreach ($texts as $fieldName => $text) {
                    $font = new Font($text);
                    $font->valign('top');
                    $font->color(!empty($option[$fieldName . 'Color']) ? ColorFormat::get($option[$fieldName . 'Color']) : '#000000');
                    $font->file(generator::PLUGIN_DIRECTORY . '/fonts/Ubuntu-Bold.ttf');
                    $font->size(!empty($option[$fieldName . 'Size']) ? FontFormat::get($option[$fieldName . 'Size']) : 60);
                    $fontSize = $font->getBoxSize();
                    ImageManagerStatic::configure(self::DRIVER);
                    $imageText = ImageManagerStatic::canvas(
                        $fontSize['width'],
                        $fontSize['height'],
                        !empty($option[$fieldName . 'BackgroundColor']) ? (string) $option[$fieldName . 'BackgroundColor'] : null
                    );
                    $font->applyToImage($imageText, 0, 5);
                    $this->imageInstance->insert(
                        $imageText,
                        $sluggedPosition,
                        isset($option[$fieldName . 'Xoffset']) ? (int) $option[$fieldName . 'Xoffset'] : 0,
                        isset($option[$fieldName . 'Yoffset']) ? (int) $option[$fieldName . 'Yoffset'] : 0
                    );
                }
            }
        }

    }

    /**
     * @param $options
     *
     * @return array
     */
    private function collectImagesTexts($options)
    {
        $texts = [];

        $texts['textToShow'] = !empty($options['textToShow']) ? $options['textToShow'] : null;
        $texts['productTitle'] = !empty($options['productTitle']) ? $options['productTitle'] : null;
        $texts['productActivePrice'] = !empty($options['productActivePrice']) ? $options['productActivePrice']: null;
        $texts['productSalePrice'] = !empty($options['productSalePrice']) ? $options['productSalePrice']: null;
        $texts['productRegularPrice'] = !empty($options['productRegularPrice']) ? $options['productRegularPrice']: null;
        $texts['productCategory'] = !empty($options['productCategory']) ? $options['productCategory']: null;
        $texts['customText'] = !empty($options['customText']) ? CustomText::get($options): null;

        return $texts;
    }
}
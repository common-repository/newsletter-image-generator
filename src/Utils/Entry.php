<?php

namespace Oaattia\WoocommerceGenerator\Utils;

use Oaattia\WoocommerceGenerator\Utils\Renders\OptionsCollector;
use Oaattia\WoocommerceGenerator\Utils\Renders\Render;

//header('Content-Type: image/jpeg');

final class Entry
{
    private static $instance;

    public static function create()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Options parameters format should be like below
     * ```json
     * {
            "topLeft": {
                "showProductTitle": "yes",
                "textToShow": "asdasdadsa",
                "textColor": "FFFFFF",
                "textSize": "90",
                "hAlignment": "left",
                "vAlignment": "top"
            },
            "left": {
                "textToShow": "asdadasdasdads",
                "textColor": "000000",
                "textSize": "45",
                "hAlignment": "left",
                "vAlignment": "top"
            },
            "bottomLeft": {
                "textToShow": "",
                "textColor": "FFFFFF",
                "textSize": "",
                "hAlignment": "left",
                "vAlignment": "top"
            },
            "topRight": {
                "textToShow": "",
                "textColor": "FFFFFF",
                "textSize": "",
                "hAlignment": "left",
                "vAlignment": "top"
            },
            "right": {
                "textToShow": "",
                "textColor": "FFFFFF",
                "textSize": "",
                "hAlignment": "left",
                "vAlignment": "top"
            },
            "bottomRight": {
                "textToShow": "",
                "textColor": "FFFFFF",
                "textSize": "",
                "hAlignment": "left",
                "vAlignment": "top"
            }
      }
     * ```
     */
    public function renderImage()
    {
        $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uriSegments = explode('/', $uriPath);

        if(!isset($_GET['productId'], $uriSegments[1]) || $uriSegments[1] !== 'entry') {
            return;
        }

        $options = [];
        $productId = intval($_GET['productId']);

        // for purpose of fast testing we can get the options from the database better
        $optionsEncoded = isset($_GET['test'])
            ? base64_decode(get_option('imageHashedOptions'))
            : base64_decode($_GET['options']);

        if($optionsEncoded) {
            if(!$options = json_decode($optionsEncoded, true)) {
                $options = [];
            }
        }

        // Collect options first
        $optionCollector = new OptionsCollector($options);
        $render = new Render($optionCollector->getOptions());

        if($savedPath = $render->createImageAndSave($productId)) {
            $imageUrl = sprintf(get_site_url() . '/' . '%s', $savedPath);
            $this->createImage($imageUrl);
            exit;
        }

    }

    protected function __construct()
    {
        add_action('init', array($this, 'renderImage'));
    }

    private function __clone()
    {
    }

    /**
     * Create png image
     *
     * @param $imageUrl
     */
    private function createImage($imageUrl)
    {
        $imageContent = file_get_contents($imageUrl);
        $im = imagecreatefromstring($imageContent);
        header('Content-Type: image/png');
        imagepng($im);
        imagedestroy($im);
    }
}
<?php

namespace Oaattia\WoocommerceGenerator\Admin;

use Oaattia\WoocommerceGenerator\FormsBuilder\Fields;
use Oaattia\WoocommerceGenerator\Generator;
use Oaattia\WoocommerceGenerator\Utils\Renders\OptionsCollector;
use Oaattia\WoocommerceGenerator\Utils\Renders\Render;
use Oaattia\WoocommerceGenerator\Utils\Wrappers\PageType;


/**
 * we add this shit here because t
 */
require_once ABSPATH . 'wp-admin/includes/screen.php';

final class MetaBox
{
    private $page;

    private static $instance;

    const META_KEY_OPTIONS = '_image_generator_options';
    const META_KEY_IMAGE = '_image_generator_image';

    /**
     * @return self
     */
    public static function create()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->page = new PageType();

        add_filter('woocommerce_product_data_tabs', [$this, 'ImageGeneratorTab'], 100, 1);
        add_action('woocommerce_product_data_panels', [$this, 'ImageGeneratorFields']);
        add_action('wp_ajax_wp_generator_ajax_change', [$this, 'renderImage']);
        add_action('save_post', [$this, 'savePostMeta']);
    }

    public function ImageGeneratorTab($tabs)
    {
        $tabs['image_generator_plugin'] = [
            'label'     => 'Image Generator',
            'target'    => 'image_generator'
        ];

        return $tabs;
    }

    public function ImageGeneratorFields()
    {
        global $post;

        // let's check first if there is a image generated already for this product
        $imageUrl = get_the_post_thumbnail_url($post);

        $generatedImagePath = get_post_meta($post->ID, self::META_KEY_IMAGE, true);

        if($generatedImagePath) {
            $imageUrl = get_site_url() . '/' .  $generatedImagePath;
        }

        if ($imageUrl) {
            include 'Views/ImageGeneraetorMetaBox.php';
        } else {
            return _e(
                'No image found',
                'woo-newsletter-image-generator'
            );
        }
    }


    /**
     * here to save the data after doing some change to the image
     *
     * @param int $postId
     */
    public function savePostMeta($postId)
    {
        $fields = [];
        $screen = \get_current_screen();

        if ($screen && $screen->post_type === 'product') {
            foreach (Fields::POSITIONS as $position) {
                $newPosition = sanitize_text_field($_POST[$position]);
                if (!$newPosition) {
                    return;
                }
                $fields[$position] = array_filter($newPosition);
            }

            $options = array_filter($fields);

            $metaValues = array_merge(
                $options,
                ['productId' => $postId]
            );

            delete_post_meta($postId, self::META_KEY_OPTIONS);
            add_post_meta($postId, self::META_KEY_OPTIONS, $metaValues);
        }
    }

    /**
     * This used to render the image after saving the options for the image
     */
    public function renderImage()
    {
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            throw new \RuntimeException('Can\'t continue further, this is not ajax request.');
        }

        $inputFieldsValues = sanitize_text_field($_POST['inputFieldsValues']);
        $productId = sanitize_text_field($_POST['productId']);

        if(!$inputFieldsValues && !$productId) {
            throw new \RuntimeException('productId or fields values not there.');
        }

        parse_str($inputFieldsValues, $parseData);
        $options = array_filter(array_map('array_filter', $parseData));

        // Collect options first
        $optionCollector = new OptionsCollector($options);
        $options = $optionCollector->getOptions();

        // Render the image
        $render = new Render($options);
        if($savedPath = $render->createImageAndSave($productId)) {
            Generator::writeLog('Image for product ' .$productId . ' created successfully with path ' . $savedPath);
            wp_die($savedPath);
        }

        Generator::writeLog('Image for product ' .$productId . ' not created');
        wp_die();
    }
}

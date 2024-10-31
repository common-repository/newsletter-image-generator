<?php

namespace Oaattia\WoocommerceGenerator\Utils\Renders;

/**
 * This will be use to collect the options from the form
 * And return formatted render option to the renderer
 */
class OptionsCollector
{
    const DEFAULT_COLOR = '#000000';
    const DEFAULT_TEXT_SIZE = '16';

    /**
     * @var array
     */
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Extract the options to render the image
     *
     * @return array
     */
    public function getOptions()
    {
        if (empty($this->options)) {
           return [];
        }

        foreach ($this->options as $position => $option) {
            if (isset($option['showProductTitle']) && !empty(intval($_REQUEST['productId'])) && $option['showProductTitle'] === 'yes') {
                $product = wc_get_product(intval($_REQUEST['productId']));
                if($product) {
                    $this->options[$position]['productTitle'] = $product->get_name();
                }
            }

            // product active price
            if (isset($option['showProductActivePrice']) && !empty(intval($_REQUEST['productId'])) && $option['showProductActivePrice'] === 'yes') {
                $product = isset($product) ? $product : wc_get_product(intval($_REQUEST['productId']));
                if ($product) {
                    $this->options[$position]['productActivePrice'] = (string) wc_get_price_to_display($product, array('price' => $product->get_price())) . html_entity_decode(get_woocommerce_currency_symbol());
                }
            }

            if (isset($option['showProductSalePrice']) && !empty(intval($_REQUEST['productId'])) && $option['showProductSalePrice'] === 'yes') {
                $product = isset($product) ? $product : wc_get_product(intval($_REQUEST['productId']));
                if ($product) {
                    $this->options[$position]['productSalePrice'] = (string) wc_get_price_to_display($product, array('price' => $product->get_sale_price())) . html_entity_decode(get_woocommerce_currency_symbol());
                }
            }

            if (isset($option['showProductRegularPrice']) && !empty(intval($_REQUEST['productId'])) && $option['showProductRegularPrice'] === 'yes') {
                $product = isset($product) ? $product : wc_get_product(intval($_REQUEST['productId']));
                if ($product) {
                    $this->options[$position]['productRegularPrice'] = (string) wc_get_price_to_display($product, array('price' => $product->get_regular_price())) . html_entity_decode(get_woocommerce_currency_symbol());
                }
            }

            if (isset($option['showProductCategory']) && !empty(intval($_REQUEST['productId'])) && $option['showProductCategory'] === 'yes') {
                $this->options[$position]['productCategory'] = strip_tags(wc_get_product_category_list(intval($_REQUEST['productId'])));
            }

        }

        return $this->options;
    }

}
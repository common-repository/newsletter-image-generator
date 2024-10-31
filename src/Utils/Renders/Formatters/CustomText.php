<?php

namespace Oaattia\WoocommerceGenerator\Utils\Renders\Formatters;

class CustomText implements Formatter
{
    const STUBS = [
      'product_title',
      'product_active_price',
      'product_sale_price',
      'product_regular_price',
      'product_category',
    ];

    /**
     * @param array $texts
     *
     * @return string
     */
    public static function get($texts)
    {
        $text = $texts['customText'];

        $stubs = [];

        foreach (self::STUBS as $stub) {
            $stubs[sprintf('{{%s}}',$stub)] = self::getText($stub, $texts);
        }

        return strtr($text, $stubs);
    }

    /**
     * @param string $item
     * @param array $text
     *
     * @return string
     */
    private static function getText($item, $text)
    {
        $product = isset($product) ? $product : wc_get_product(intval($_REQUEST['productId']));

        switch ($item) {
            case 'product_title':
                if(!isset($text['productTitle'])) {
                    if($product) {
                        return $product->get_name();
                    } else {
                        return '';
                    }
                }

                return $text['productTitle'];
            case 'product_active_price':
                if(!isset($text['productActivePrice'])) {
                    if ($product) {
                        return (string) wc_get_price_to_display($product, array('price' => $product->get_price())) . html_entity_decode(get_woocommerce_currency_symbol());
                    } else {
                        return '';
                    }
                }

                return $text['productActivePrice'];

            case 'product_sale_price':
                if(!isset($text['productSalePrice'])) {
                    if ($product) {
                        return (string) wc_get_price_to_display($product, array('price' => $product->get_sale_price())) . html_entity_decode(get_woocommerce_currency_symbol());
                    } else {
                        return '';
                    }
                }

                return $text['productSalePrice'];

                break;
            case 'product_regular_price':
                if(!isset($text['productRegularPrice'])) {
                    if ($product) {
                        return (string) wc_get_price_to_display($product, array('price' => $product->get_regular_price())) . html_entity_decode(get_woocommerce_currency_symbol());
                    } else {
                        return '';
                    }
                }

                return $text['productRegularPrice'];

             case 'product_category':
                 if(!isset($text['productCategory'])) {
                     if ($product) {
                         return strip_tags(wc_get_product_category_list(intval($_REQUEST['productId'])));
                     } else {
                         return '';
                     }
                 }

                 return $text['productCategory'];

            default:
                $text = '';
        }

        return $text;
    }
}
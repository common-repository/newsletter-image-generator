<?php

namespace Oaattia\WoocommerceGenerator\FormsBuilder;

use Oaattia\WoocommerceGenerator\Admin\MetaBox;

/**
 * Class to generate the forms fields easily
 *
 * Class Form
 *
 * @package Oaattia\WoocommerceGenerator\FormBuilder
 */
class Form
{
    /**
     * @var array
     */
    private $elements;

    public function __construct($elements)
    {
        $this->elements = $elements;
    }

    /**
     * @return void
     */
    public function create()
    {
        if(isset($this->elements['sectionTitle'])) {
            echo '<h3 style="padding-left: 10px">'. __($this->elements['sectionTitle']) .'</h3>';
            unset($this->elements['sectionTitle']);
        }

        /**
         * Regenerate the values from the database
         * get the keys like leftTopField and append the input to it
         * Example: leftTopField[showProductTitle] = $value
         */
        global $post;
        $searchValues = [];
        $postMetaOptions = get_post_meta($post->ID, MetaBox::META_KEY_OPTIONS, true);
        if(is_array($postMetaOptions)) {
            foreach ($postMetaOptions as $key => $metaOption) {
                if(is_array($metaOption)) {
                    foreach ($metaOption as $keyNew => $itemValue) {
                        $searchValues[$key.'['.$keyNew.']'] = $itemValue;
                    }
                }
            }
        }
        echo "<div>";
        foreach ($this->elements as $name => $elements) {
            $label = $elements['title'];
            $id = md5(wc_strtolower($name));

            switch ($elements['type']) {
                case 'select':
                    woocommerce_wp_select(
                        [
                            'id'            => $id,
                            'value'         => isset($searchValues[$name]) ? $searchValues[$name] : '',
                            'name'          => $name,
                            'label'         => $label,
                            'options'       => $elements['options'],
                            'description'   => isset($elements['description']) ? __( $elements['description'], 'woocommerce' ) : '',
                        ]
                    );
                    break;
                case 'checkbox':
                    woocommerce_wp_checkbox(
                        [
                            'id'            => $id,
                            'value'         => isset($searchValues[$name]) ? $searchValues[$name] : '',
                            'name'          => $name,
                            'label'         => $label,
                            'description'   => isset($elements['description']) ? __( $elements['description'], 'woocommerce' ) : '',
                        ]
                    );
                    break;
                default:
                    woocommerce_wp_text_input(
                        [
                            'id'            => $id,
                            'name'          => $name,
                            'label'         => $label,
                            'class'         => isset($elements['class']) ? $elements['class'] : '',
                            'value'         => isset($searchValues[$name]) ? $searchValues[$name] : '',
                            'description'   => isset($elements['description']) ? __( $elements['description'], 'woocommerce' ) : '',
                        ]
                    );
                    break;
            }
        }
        echo '</div>';
    }
}
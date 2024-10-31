<?php

namespace Oaattia\WoocommerceGenerator\Admin\Settings;

use  Oaattia\WoocommerceGenerator\FormsBuilder\Fields ;
use  Oaattia\WoocommerceGenerator\Subscription ;
use  WC_Admin_Settings ;
use  WC_Settings_Page ;
final class ImageGeneratorSettings extends WC_Settings_Page
{
    public function __construct()
    {
        $this->id = 'woocommerce-newsletter-image-generator-settings';
        $this->label = __( 'Image Generator', 'woo-newsletter-image-generator' );
        parent::__construct();
    }
    
    public function get_sections()
    {
        $premiumPositions = [];
        $positions = array_merge( Fields::FREE_POSITIONS, $premiumPositions );
        $positionSections = [];
        foreach ( $positions as $title => $key ) {
            $positionSections[strtolower( str_replace( ' ', '_', $title ) )] = __( $title, 'woo-newsletter-image-generator' );
        }
        $sections = array(
            'image_hashed_string_page' => __( 'Image hashed string', 'woo-newsletter-image-generator' ),
        );
        return apply_filters( 'woocommerce_get_sections_' . $this->id, array_merge( $positionSections, $sections ) );
    }
    
    public function output()
    {
        global  $current_section ;
        $fields = Fields::create();
        $settings = $this->get_settings( $fields, ( $current_section ? $current_section : 'left_area_top' ) );
        WC_Admin_Settings::output_fields( $settings );
    }
    
    private function getOptionsPage( $fields, $currentSection = 'left_area_top' )
    {
        $options[] = array(
            'title' => __( 'Image Generator Settings', 'woo-newsletter-image-generator' ),
            'desc'  => __( 'Here you can add the settings for the image, and for every part of the image: like the left and right and top and left combinations.', 'woo-newsletter-image-generator' ),
            'type'  => 'title',
            'id'    => 'title',
        );
        $options[] = array(
            'type' => 'sectionend',
            'id'   => $this->id . '_options_settings',
        );
        /** @var Fields $fields */
        if ( $fields ) {
            foreach ( $fields->getFields() as $rows ) {
                foreach ( $rows as $id => $field ) {
                    if ( strtolower( str_replace( ' ', '_', $rows['sectionTitle'] ) ) === $currentSection ) {
                        
                        if ( !is_array( $field ) ) {
                            $options[] = array(
                                'title' => __( $field, 'woo-newsletter-image-generator' ),
                                'desc'  => __( 'Position: (' . strtolower( $field ) . ')', 'woo-newsletter-image-generator' ),
                                'type'  => 'title',
                                'id'    => $field,
                            );
                        } else {
                            $options[] = array(
                                'title'    => __( $field['title'], 'woo-newsletter-image-generator' ),
                                'type'     => $field['type'],
                                'autoload' => false,
                                'desc'     => ( isset( $field['desc'] ) ? __( $field['desc'], 'woo-newsletter-image-generator' ) : null ),
                                'desc_tip' => ( isset( $field['desc_tip'] ) ? $field['desc_tip'] : true ),
                                'css'      => ( isset( $field['css'] ) ? $field['css'] : '' ),
                                'id'       => $id,
                                'options'  => ( isset( $field['options'] ) && is_array( $field['options'] ) ? array_merge( [
                                '' => 'Choose',
                            ], $field['options'] ) : null ),
                            );
                        }
                    
                    }
                }
                $options[] = array(
                    'type' => 'sectionend',
                    'id'   => $this->id . '_options',
                );
            }
        }
        return $options;
    }
    
    private function getHashedStringPage()
    {
        $options[] = array(
            'title' => __( 'Hashed String', 'woo-newsletter-image-generator' ),
            'type'  => 'title',
        );
        $options[] = array(
            'title' => __( 'Options Hashed String', 'woo-newsletter-image-generator' ),
            'type'  => 'textarea',
            'id'    => 'imageHashedOptions',
            'css'   => 'min-height:200px;min-width:500px;',
        );
        $options[] = array(
            'type' => 'sectionend',
            'id'   => $this->id . '_options_hashing_page_settings',
        );
        return $options;
    }
    
    public function get_settings( $fields = null, $currentSection = 'left_area_top' )
    {
        
        if ( $currentSection === 'image_hashed_string_page' ) {
            $settings = apply_filters( 'woocommerce_' . $this->id . '_settings', $this->getHashedStringPage() );
        } else {
            $settings = apply_filters( 'woocommerce_' . $this->id . '_settings', $this->getOptionsPage( $fields, $currentSection ) );
        }
        
        return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $currentSection );
    }
    
    /**
     * Save settings.
     */
    public function save()
    {
        global  $current_section ;
        if ( $current_section === 'image_hashed_string_page' ) {
            return false;
        }
        $fields = Fields::create();
        $settings = $this->get_settings( $fields, ( $current_section ? $current_section : 'left_area_top' ) );
        WC_Admin_Settings::save_fields( $settings );
        $hashedString = $this->getOptionsHashedFields();
        update_option( 'imageHashedOptions', $hashedString );
    }
    
    private function getOptionsHashedFields()
    {
        global  $wpdb ;
        $options = [];
        $settings = [
            'topLeft',
            'left',
            'bottomLeft',
            'topRight',
            'right',
            'bottomRight'
        ];
        $allOptionsDb = $wpdb->get_results( "SELECT option_name, option_value FROM {$wpdb->options}", ARRAY_A );
        if ( !$allOptionsDb ) {
            return null;
        }
        foreach ( $allOptionsDb as $o ) {
            foreach ( $settings as $setting ) {
                if ( $o['option_name'] === $setting ) {
                    $options[$o['option_name']] = unserialize( $o['option_value'] );
                }
            }
        }
        return base64_encode( json_encode( $options ) );
    }

}
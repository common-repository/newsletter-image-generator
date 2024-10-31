<?php

namespace Oaattia\WoocommerceGenerator;

class Subscription
{
    public static function getSubscribtionInstance()
    {
        global  $woo_nig ;
        
        if ( !isset( $woo_nig ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $woo_nig = fs_dynamic_init( array(
                'id'             => '4840',
                'slug'           => 'woocommerce-newsletter-image-generator',
                'type'           => 'plugin',
                'public_key'     => 'pk_97f740bba020a69262dfed7e8ad60',
                'is_premium'     => false,
                'premium_suffix' => 'Premium',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'           => 'admin.php?page=wc-settings&tab=woocommerce-newsletter-image-generator-settings',
                'override_exact' => true,
                'support'        => false,
                'parent'         => array(
                'slug' => 'woocommerce',
            ),
            ),
                'is_live'        => true,
            ) );
        }
        
        return $woo_nig;
    }
    
    public static function check()
    {
        self::getSubscribtionInstance();
        do_action( 'woo_nig_loaded' );
    }

}
<?php

/**
 * Plugin Name: Newsletter Image Generator
 * Description: This plugin allow you as a shop owner to create images for the products with custom text or images so you can create an email campaign or newsletter with the price and prices.
 * Author: Osama Abdelrahman
 * Version: 1.0.0
 * Text Domain: woo-newsletter-image-generator
 * Domain Path: /languages/
 *
 * WC tested up to: 3.3
 * WC tested down to: 3.3
 *
 * WC requires at least: 3.0.0
 * My Minimum Wordpress Version: 4.9
 * My Minimum PHP Version: 5.6
 */

namespace Oaattia\WoocommerceGenerator;

use Oaattia\WoocommerceGenerator\Admin\Settings\ImageGeneratorSettings;
use Oaattia\WoocommerceGenerator\Utils\Entry;
use Oaattia\WoocommerceGenerator\Utils\PluginRequirement\Notifications;
use Oaattia\WoocommerceGenerator\Utils\PluginRequirement\PluginRequirements;

require_once __DIR__ . '/extras/autoload.php';

class Generator
{
    const PLUGIN_DIRECTORY = __DIR__;

    public function __construct()
    {
        Entry::create();

        Subscription::check();

        add_filter('woocommerce_get_settings_pages', array($this, 'loadAdminSettingsPage'), 1000, 1);

        add_action('admin_enqueue_scripts', [$this,'scripts']);

        add_action('admin_menu', [$this, 'subMenu'], 99);
    }

    public function subMenu()
    {
        add_submenu_page(
            'woocommerce',
            __('Image Generator', 'woo-newsletter-image-generator'),
            __('Image Generator', 'woo-newsletter-image-generator'),
            'manage_options',
            'admin.php?page=wc-settings&tab=woocommerce-newsletter-image-generator-settings');
    }


    public function scripts()
    {
        global $post;
        $screen = get_current_screen();

        if(
            $screen
            && $screen->id === 'woocommerce_page_wc-settings'
            && !empty($_GET['tab'])
            && $_GET['tab'] === 'woocommerce-newsletter-image-generator-settings'
        ) {
            wp_enqueue_script('woo_image_generator_script_main', plugins_url('/media/js/main.js', __FILE__));
        }

        if ($screen && !empty($post) && $screen->post_type === 'product') {
            wp_enqueue_script('woo_image_generator_meta_box_script', plugins_url('../../media/js/metabox.js', __FILE__), ['jquery', 'jquery-ui-accordion']);
            wp_enqueue_script('woo_image_generator_meta_box_script_color_picker', plugins_url('../../media/js/jscolor.js', __FILE__));
            wp_enqueue_style('woo_image_generator_style_css', plugins_url('../../media/css/style.css', __FILE__));

            // pass value to js from here
            wp_localize_script(
                'woo_image_generator_meta_box_script',
                'woo_image_object',
                [
                    'admin_url'  => admin_url('admin-ajax.php'),
                    'product_id' => $post->ID,
                    'site_url' => get_site_url() . '/'
                ]
            );
        }
    }

    public function loadAdminSettingsPage()
    {
        new ImageGeneratorSettings();
    }

    /**
     * write log for debugging
     */
    public static function writeLog($log)
    {
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        }
    }
}


$checks = PluginRequirements::isOk();

$messages = null;
foreach ($checks as $check) {
    /** @var Notifications $check */
    if (!$check->isValid()) {
        $messages .= '<li>' . $check->getMessage() . '</li>';
    }
}

if ($messages) {
    add_action(
        'admin_notices',
        function () use ($messages) {
            echo "<div id='message' class='updated warning is-dismissible'>
			    		<h4>This plugin have some requirements below to work</h4>
		    			<ul>
		    			    $messages
		    			</ul>
                </div>";
            unset($_GET['activate']);
            deactivate_plugins(plugin_basename(__FILE__));   // deactivate the current plugin
        }
    );
}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true)) {
    new Generator();
}

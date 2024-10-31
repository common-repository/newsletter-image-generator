<?php

namespace Oaattia\WoocommerceGenerator\Utils\PluginRequirement;

class PluginRequirements extends AbstractPluginRequirements
{
    const PLUGIN_FILE = '/../../../newsletter-image-generator.php';

    /**
     * @var array
     */
    private $headers;

    private function __construct()
    {
        $this->headers = $this->getHeaderData([
            'PHPversion'        => 'My Minimum PHP Version',
            'wordpressVersion'  => 'My Minimum Wordpress Version',
            'wcRequiredVersion' => 'WC requires at least',
        ]);
    }

    /**
     * Some checking for the plugin
     */
    public static function isOk()
    {
        $class = new self();

        yield $class->isPHPversionOk();

        yield $class->isWordpressVersionOk();

        yield $class->isWCVersionIsOk();

        yield $class->isImagickIsEnable();
    }

    /**
     * Check for the php version if it's greater than php version assigned
     *
     * @return Notifications
     */
    private function isPHPversionOk()
    {
        $isOk = (bool) version_compare(PHP_VERSION, $phpVersion = $this->getHeaderByKey('PHPversion'), '>=');

        return $this->getCheckNotificationResponse($isOk, sprintf('Minimum required php version: %s', $phpVersion));
    }

    /**
     * Check if the wordpress plugin provided is ok in the header of the plugin
     *
     * @return Notifications
     */
    private function isWordpressVersionOk()
    {
        $currentWordpressVersion = (float) get_bloginfo('version');

        $isOk = $currentWordpressVersion >= (float) $wordpressVersion = $this->getHeaderByKey('wordpressVersion');

        return $this->getCheckNotificationResponse($isOk, sprintf('Minimum required Wordpress version: %s', $wordpressVersion));
    }

    /**
     * Check if wordpress version is ok
     *
     * @return Notifications
     */
    private function isWCVersionIsOk()
    {
        $wcDbVersion = (float) get_option('woocommerce_version');

        $isOk = $wcDbVersion >= (float) $wcVersion = $this->getHeaderByKey('wcRequiredVersion');

        return $this->getCheckNotificationResponse($isOk, sprintf('Minimum required WC version: %s', $wcVersion));
    }

    /**
     * Check if imagick extension already there
     *
     * @return Notifications
     */
    private function isImagickIsEnable()
    {
        $isOk = extension_loaded('imagick') && class_exists('Imagick');

        return $this->getCheckNotificationResponse($isOk, 'Plugin require imagick extension');
    }

    /**
     * Get the data from the plugin header
     *
     * @param array $headers
     *
     * @return array
     */
    private function getHeaderData($headers)
    {
        return get_file_data(__DIR__ .self::PLUGIN_FILE, $headers);
    }

    /**
     * Get the value by the key
     *
     * @param string $key
     *
     * @return mixed|null
     */
    private function getHeaderByKey($key)
    {
        return isset($this->headers[$key]) ? $this->headers[$key] : null;
    }
}

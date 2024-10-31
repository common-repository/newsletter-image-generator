<?php

namespace Oaattia\WoocommerceGenerator\Utils\PluginRequirement;

abstract class AbstractPluginRequirements
{
    /**
     * Get the check response for the plugin
     * consist of the value of the check (true or false)
     * and the notification message response
     *
     * @param string $checkResponse
     * @param string $message
     *
     * @return Notifications
     */
    protected function getCheckNotificationResponse($checkResponse, $message)
    {
        if (!$checkResponse) {
            return new Notifications($checkResponse, __($message, 'woo-newsletter-image-generator'));
        }

        return new Notifications($checkResponse);
    }
}
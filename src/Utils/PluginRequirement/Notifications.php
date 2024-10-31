<?php

namespace Oaattia\WoocommerceGenerator\Utils\PluginRequirement;

class Notifications
{
    /**
     * @var bool
     */
    private $isValid;

    /**
     * @var null|string
     */
    private $message;

    public function __construct($isValid, $message = null)
    {
        $this->isValid = $isValid;
        $this->message = $message;
    }

    public function isValid()
    {
        return $this->isValid;
    }

    public function getMessage()
    {
        return $this->message;
    }

}
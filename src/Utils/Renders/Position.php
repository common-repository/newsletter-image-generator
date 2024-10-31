<?php

namespace Oaattia\WoocommerceGenerator\Utils\Renders;

class Position
{
    private $x;
    private $y;
    const SPACE_FROM_LEFT = 3;
    const OFFSET_X = 20;
    const OFFSET_Y = 20;

    /**
     * @param string     $position
     *
     * @param        int $width
     * @param        int $height
     */
    public function __construct($position, $width, $height)
    {
        switch ($position) {
            case 'topLeft':
                $this->setX((int) round(($width * self::SPACE_FROM_LEFT) / 100));
                $this->setY((int) round(($height * 10) / 100));
                break;

            case 'left':
                $this->setX((int) round(($width * self::SPACE_FROM_LEFT) / 100));
                $this->setY((int) round(($height * 54) / 100));
                break;

            case 'bottomLeft':
                $this->setX((int) round(($width * self::SPACE_FROM_LEFT) / 100));
                $this->setY((int) round(($height * 98) / 100));
                break;

        }
    }

    public function getX()
    {
        return $this->x;
    }

    public function setX($x)
    {
        $this->x = $x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function setY($y)
    {
        $this->y = $y;
    }
}
<?php


namespace Vehica\Components;


/**
 * Class CardLabel
 * @package Vehica\Components
 */
class CardLabel implements \JsonSerializable
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $color;

    /**
     * @var string
     */
    private $backgroundColor;

    /**
     * CardLabel constructor.
     * @param string $text
     * @param string $color
     * @param string $backgroundColor
     */
    public function __construct($text, $color = '', $backgroundColor = '')
    {
        $this->text = $text;

        $this->color = $color;

        $this->backgroundColor = $backgroundColor;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @return string[]
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'text' => $this->getText(),
            'color' => $this->getColor(),
            'backgroundColor' => $this->getBackgroundColor(),
        ];
    }

}
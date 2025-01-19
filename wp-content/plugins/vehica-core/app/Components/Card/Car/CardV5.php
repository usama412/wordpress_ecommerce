<?php


namespace Vehica\Components\Card\Car;


/**
 * Class CardV5
 * @package Vehica\Components\Card\Car
 */
class CardV5 extends CardV3
{
    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'card_v5';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return Card::TYPE_V5;
    }

}
<?php


namespace Vehica\Components\Card\Car;


/**
 * Class CardV4
 * @package Vehica\Components\Card\Car
 */
class CardV4 extends CardV2
{
    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'card_v4';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return Card::TYPE_V4;
    }

    public function loadBigCardTemplate()
    {
        get_template_part('templates/card/car/card_big_v4');
    }

}
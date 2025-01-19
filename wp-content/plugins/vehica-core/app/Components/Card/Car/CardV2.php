<?php


namespace Vehica\Components\Card\Car;


/**
 * Class CardV2
 *
 * @package Vehica\Components\Card\Car
 */
class CardV2 extends CardV1
{
    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'card_v2';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return Card::TYPE_V2;
    }

    public function loadBigCardTemplate()
    {
        get_template_part('templates/card/car/card_big_v2');
    }

}
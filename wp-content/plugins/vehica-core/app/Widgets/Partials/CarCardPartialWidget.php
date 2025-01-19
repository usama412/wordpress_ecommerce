<?php
/** @noinspection TypoSafeNamingInspection */


namespace Vehica\Widgets\Partials;


use Elementor\Controls_Manager;
use Vehica\Components\Card\Car\Card;
use Vehica\Components\Card\Car\CardV1;
use Vehica\Components\Card\Car\CardV2;
use Vehica\Components\Card\Car\CardV3;
use Vehica\Components\Card\Car\CardV4;
use Vehica\Components\Card\Car\CardV5;

/**
 * Trait CarCardPartialWidget
 *
 * @package Vehica\Widgets\Partials
 */
trait CarCardPartialWidget
{
    use WidgetPartial;

    /**
     * @return CardV1
     */
    public function getCardV1()
    {
        return CardV1::create($this->getCardV1Config());
    }

    /**
     * @return CardV2
     */
    public function getCardV2()
    {
        return CardV2::create($this->getCardV1Config());
    }

    /**
     * @return CardV3
     */
    public function getCardV3()
    {
        return CardV3::create($this->getCardV3Config());
    }

    /**
     * @return CardV4
     */
    public function getCardV4()
    {
        return CardV4::create($this->getCardV4Config());
    }

    /**
     * @return CardV5
     */
    public function getCardV5()
    {
        return CardV5::create($this->getCardV5Config());
    }

    /**
     * @return array
     */
    public function getCardV1Config()
    {
        return [
            'type' => Card::TYPE_V1,
            'showLabels' => $this->showCardLabels()
        ];
    }

    /**
     * @return array
     */
    public function getCardV2Config()
    {
        return [
            'type' => Card::TYPE_V2,
            'showLabels' => $this->showCardLabels()
        ];
    }

    /**
     * @return array
     */
    public function getCardV3Config()
    {
        return [
            'type' => Card::TYPE_V3,
            'showLabels' => $this->showCardLabels()
        ];
    }

    /**
     * @return array
     */
    public function getCardV4Config()
    {
        return [
            'type' => Card::TYPE_V4,
            'showLabels' => $this->showCardLabels()
        ];
    }

    /**
     * @return array
     */
    public function getCardV5Config()
    {
        return [
            'type' => Card::TYPE_V5,
            'showLabels' => $this->showCardLabels()
        ];
    }

    protected function addShowCardLabelsControl()
    {
        $this->add_control(
            'show_card_labels',
            [
                'label' => esc_html__('Display Card Labels', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    protected function showCardLabels()
    {
        $show = $this->get_settings_for_display('show_card_labels');
        return !empty($show);
    }

}
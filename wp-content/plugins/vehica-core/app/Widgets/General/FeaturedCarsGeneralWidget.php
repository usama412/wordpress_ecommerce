<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Vehica\Components\Card\Car\Card;
use Vehica\Components\Card\Car\CardV1;
use Vehica\Components\FeaturedCars;
use Vehica\Core\Collection;
use Vehica\Widgets\Partials\CarTabsPartialWidget;
use Vehica\Widgets\Partials\CarCardPartialWidget;

/**
 * Class FeaturedCarsGeneralWidget
 *
 * @package Vehica\Widgets\General
 */
class FeaturedCarsGeneralWidget extends GeneralWidget
{
    use CarCardPartialWidget;
    use CarTabsPartialWidget;

    const NAME = 'vehica_featured_cars_general_widget';
    const TEMPLATE = 'general/featured_cars';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Featured Listings', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->addContentControls();

        $this->addStyleControls();
    }

    protected function addStyleControls()
    {
        $this->start_controls_section(
            'heading_small',
            [
                'label' => esc_html__('Top Heading', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'heading_small_color',
            [
                'label' => esc_html__('Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-tabs-top-v1__heading-small' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_small_typo',
                'label' => esc_html__('Typography', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-tabs-top-v1__heading-small'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'heading_big',
            [
                'label' => esc_html__('Main Heading', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'heading_big_color',
            [
                'label' => esc_html__('Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-tabs-top-v1__heading-big' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_big_typo',
                'label' => esc_html__('Typography', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-tabs-top-v1__heading-big'
            ]
        );

        $this->end_controls_section();
    }

    protected function addContentControls()
    {
        $this->start_controls_section(
            self::NAME . '_content',
            [
                'label' => $this->get_title(),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addCardTypeControl();

        $this->addHeadingControls();

        $this->addOrderByControl();

        $this->addIncludeExcludedControl();

        $this->addTabsControls();

        $this->addSocialControl();

        $this->addFeaturedControl();

        $this->addShowCardLabelsControl();

        $this->addShowControl(
            'view_all_button',
            esc_html__('Display "View All Button"', 'vehica-core')
        );

        $this->end_controls_section();
    }

    private function addCardTypeControl()
    {
        $this->add_control(
            'card_type',
            [
                'label' => esc_html__('Card Type', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    Card::TYPE_V1 => esc_html__('V1', 'vehica-core'),
                    Card::TYPE_V2 => esc_html__('V2', 'vehica-core'),
                    Card::TYPE_V4 => esc_html__('V4', 'vehica-core'),
                ],
                'default' => Card::TYPE_V1
            ]
        );
    }

    /**
     * @return string
     */
    public function getCardType()
    {
        $cardType = (string)$this->get_settings_for_display('card_type');

        if (empty($cardType)) {
            return Card::TYPE_V1;
        }

        return $cardType;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return Card::create($this->getCardConfig());
    }

    /**
     * @return array
     */
    public function getCardConfig()
    {
        $type = $this->getCardType();

        if ($type === Card::TYPE_V2) {
            return $this->getCardV2Config();
        }

        if ($type === Card::TYPE_V4) {
            return $this->getCardV4Config();
        }

        return $this->getCardV1Config();
    }

    private function addSocialControl()
    {
        $this->add_control(
            'vehica_show_social',
            [
                'label' => esc_html__('Show Social Icons', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '1',
                'return_value' => '1'
            ]
        );
    }

    private function addHeadingControls()
    {
        $this->add_control(
            'vehica_heading_1',
            [
                'label' => esc_html__('Heading 1', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Handy picked', 'vehica-core')
            ]
        );

        $this->add_control(
            'vehica_heading_2',
            [
                'label' => esc_html__('Heading 2', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Featured Cars', 'vehica-core')
            ]
        );
    }

    /**
     * @return bool
     */
    public function hasFirstHeading()
    {
        return $this->getFirstHeading() !== '';
    }

    public function getFirstHeading()
    {
        return (string)$this->get_settings_for_display('vehica_heading_1');
    }

    /**
     * @return bool
     */
    public function hasSecondHeading()
    {
        return $this->getSecondHeading() !== '';
    }

    public function getSecondHeading()
    {
        return (string)$this->get_settings_for_display('vehica_heading_2');
    }

    /**
     * @return FeaturedCars
     */
    public function getFeaturedCars()
    {
        return new FeaturedCars($this->getCars());
    }

    /**
     * @return int
     */
    public function getCarsNumber()
    {
        return 5;
    }

    /**
     * @return bool
     */
    private function showSocialOption()
    {
        $show = $this->get_settings_for_display('vehica_show_social');

        return !empty($show);
    }

    /**
     * @return bool
     */
    public function showSocials()
    {
        return $this->showSocialOption() && (
                !empty(vehicaApp('facebook_url'))
                || !empty(vehicaApp('youtube_url'))
                || !empty(vehicaApp('twitter_url'))
                || !empty(vehicaApp('instagram_url'))
                || !empty(vehicaApp('linkedin_url'))
            );
    }

    /**
     * @return Collection
     */
    protected function getCarFields()
    {
        return CardV1::getFields();
    }



}
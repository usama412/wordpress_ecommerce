<?php


namespace Vehica\Widgets\General;


use DateTime;
use Elementor\Controls_Manager;
use Exception;

/**
 * Class ComingSoonGeneralWidget
 * @package Vehica\Widgets\General
 */
class ComingSoonGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_coming_soon_general_widget';
    const TEMPLATE = 'general/coming_soon';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Coming Soon', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'vehica_date',
            [
                'label' => esc_html__('Date', 'vehica-core'),
                'type' => Controls_Manager::DATE_TIME
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->get_settings_for_display('vehica_date');
    }

    /**
     * @return \DateInterval|false
     */
    private function getDifference()
    {
        try {
            $nowDate = new DateTime();
            $finalDate = new DateTime($this->getDate());
        } catch (Exception $e) {
            return false;
        }

        return $nowDate->diff($finalDate);
    }

    /**
     * @return int
     */
    public function getDays()
    {
        $difference = $this->getDifference();

        if (!$difference) {
            return 0;
        }

        return $difference->days;
    }

    /**
     * @return int
     */
    public function getHours()
    {
        $difference = $this->getDifference();

        if (!$difference) {
            return 0;
        }

        return $difference->h;
    }

    /**
     * @return int
     */
    public function getMinutes()
    {
        $difference = $this->getDifference();

        if (!$difference) {
            return 0;
        }

        return $difference->i;
    }

}
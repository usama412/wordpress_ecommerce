<?php

namespace Vehica\Widgets\Car\Single;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Vehica\Widgets\Partials\ContactFormWidgetPartial;

/**
 * Class ContactFormSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class ContactFormSingleCarWidget extends SingleCarWidget
{
    use ContactFormWidgetPartial;

    const NAME = 'vehica_contact_form_single_car_widget';
    const TEMPLATE = 'car/single/contact_form';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Contact Form (Car)', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME . '_content',
            [
                'label' => esc_html__('Contact Form (Car)', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addContactFormControl();

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

    public function displayContactForm()
    {
        global $vehicaCar;
        if (!$vehicaCar) {
            return;
        }

        $contactFormId = $this->getContactFormId();
        if ($contactFormId) {
            the_post();
            echo do_shortcode('[contact-form-7 id="' . $contactFormId . '"][/contact-form-7]');
        }
    }

}
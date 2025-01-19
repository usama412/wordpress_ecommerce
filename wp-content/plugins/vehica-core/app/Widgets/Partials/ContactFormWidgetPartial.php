<?php

namespace Vehica\Widgets\Partials;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;

/**
 * Trait ContactFormElementPartial
 * @package Vehica\Widgets\Partials
 */
trait ContactFormWidgetPartial
{

    protected function addContactFormControl()
    {
        $contactFormsList = vehicaApp('contact_forms_list');
        $this->add_control(
            'vehica_contact_form_field',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Contact form', 'vehica-core'),
                'label_block' => 1,
                'options' => $contactFormsList,
                'default' => !empty($contactFormsList) ? key($contactFormsList) : ''
            ]
        );
    }

    /**
     * @return int|false
     */
    protected function getContactFormId()
    {
        $contactFormId = (int)$this->get_settings_for_display('vehica_contact_form_field');

        if (!$contactFormId) {
            return false;
        }

        return $contactFormId;
    }

}
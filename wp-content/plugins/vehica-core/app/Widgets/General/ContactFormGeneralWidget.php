<?php

namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\User\User;
use Vehica\Widgets\Partials\ContactFormWidgetPartial;

/**
 * Class ContactFormGeneralWidget
 * @package Vehica\Widgets\General
 */
class ContactFormGeneralWidget extends GeneralWidget
{
    use ContactFormWidgetPartial;

    const NAME = 'vehica_contact_form_general_widget';
    const TEMPLATE = 'general/contact_form';
    const FORM = 'vehica_form';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Contact Form', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->addContentControls();
    }

    protected function addContentControls()
    {
        $this->start_controls_section(
            'vehica_form_content',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addContactFormControl();

        $this->end_controls_section();
    }

    protected function addContactFormControl()
    {
        if (vehicaApp('contact_forms')->count() === 0) {
            $this->add_control(
                'vehica_form_not_exist',
                [
                    'label' => esc_html__('You have to create form first', 'vehica-core'),
                    'type' => Controls_Manager::HEADING
                ]
            );

            return;
        }

        $forms = vehicaApp('contact_forms_list');

        $this->add_control(
            self::FORM,
            [
                'label' => esc_html__('Form', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $forms,
                'default' => key($forms)
            ]
        );
    }

    /**
     * @return int
     */
    protected function getFormId()
    {
        return (int)$this->get_settings_for_display(self::FORM);
    }

    /**
     * @return bool
     */
    public function hasForm()
    {
        return !empty($this->getFormId());
    }

    public function displayForm()
    {
        if (is_singular(Car::POST_TYPE)) {
            the_post();
        } elseif (is_author()) {
            global $vehicaUser;
            if ($vehicaUser) {
                /* @var User $vehicaUser */
                echo do_shortcode('[contact-form-7 vehica-user-id="' . $vehicaUser->getId()
                    . '" id="' . $this->getFormId() . '"][/contact-form-7]');
                return;
            }
        }

        echo do_shortcode('[contact-form-7 id="' . $this->getFormId() . '"][/contact-form-7]');
    }

}
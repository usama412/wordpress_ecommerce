<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\User\User;

/**
 * Class ContactOwnerSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class ContactOwnerSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_contact_owner_single_car_widget';
    const TEMPLATE = 'shared/contact_owner';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Contact Owner', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'general',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'type',
            [
                'label' => esc_html__('Type', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->getOptions(),
                'default' => 'global'
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @return string[]
     */
    private function getOptions()
    {
        $options = [
            'global' => esc_html__('Global', 'vehica-core'),
        ];

        if (vehicaApp('settings_config')->isMessageSystemEnabled()) {
            $options['messages'] = esc_html__('Private messages', 'vehica-core');
        }

        return $options + vehicaApp('contact_forms_list');
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

    /**
     * @return int|string
     */
    private function getType()
    {
        $type = $this->get_settings_for_display('type');

        if (empty($type) || $type === 'global') {
            if (vehicaApp('settings_config')->isMessageSystemEnabled()) {
                return 'messages';
            }

            return 6201;
        }

        if ($type === 'messages') {
            return 'messages';
        }

        return (int)$type;
    }

    /**
     * @return bool
     */
    public function isMessagesType()
    {
        return $this->getType() === 'messages';
    }

    /**
     * @return bool
     */
    public function isContactFormType()
    {
        return apply_filters('vehica/contactType/form', !$this->isMessagesType());
    }

    /**
     * @return int|string
     */
    private function getFormId()
    {
        return apply_filters('vehica/contactForm', $this->getType());
    }

    /**
     * @return bool
     */
    public function hasForm()
    {
        return !empty($this->getFormId());
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        $car = $this->getCar();
        if (!$car) {
            return 0;
        }

        return $car->getUserId();
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

        echo do_shortcode('[contact-form-7 vehica-user-id="' . $this->getUserId()
            . '" id="' . $this->getFormId() . '"][/contact-form-7]');
    }

}
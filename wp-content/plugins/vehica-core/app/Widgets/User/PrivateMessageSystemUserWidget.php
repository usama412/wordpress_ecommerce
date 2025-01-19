<?php


namespace Vehica\Widgets\User;


use Elementor\Controls_Manager;

/**
 * Class PrivateMessageSystemUserWidget
 * @package Vehica\Widgets\User
 */
class PrivateMessageSystemUserWidget extends UserWidget
{
    const NAME = 'vehica_private_message_system_user_widget';
    const TEMPLATE = 'shared/private_message_system';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Private Message System', 'vehica-core');
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

        if (!vehicaApp('settings_config')->isMessageSystemEnabled()) {
            $this->add_control(
                'warning',
                [
                    'raw' => '<strong>' . esc_html__('Please note!', 'vehica-core') . '</strong> ' . esc_html__('You need to enable first option: Vehica Panel > User Panel > Private Message System', 'vehica-core'),
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'render_type' => 'ui',
                ]
            );
        } else {
            $this->add_control(
                'vehica_no_options_info',
                [
                    'label' => esc_html__('No Settings', 'vehica-core'),
                    'type' => Controls_Manager::HEADING
                ]
            );
        }

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        $user = $this->getUser();
        if (!$user) {
            return 0;
        }

        return $user->getId();
    }

}
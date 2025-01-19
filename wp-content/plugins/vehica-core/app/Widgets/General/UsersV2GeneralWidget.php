<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Vehica\Model\User\User;
use Vehica\Widgets\Partials\Users\QueryUsersPartialWidget;

/**
 * Class UsersV2GeneralWidget
 * @package Vehica\Widgets\General
 */
class UsersV2GeneralWidget extends GeneralWidget
{
    use QueryUsersPartialWidget;

    const NAME = 'vehica_users_v2_general_widget';
    const TEMPLATE = 'general/users_v2';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Users V2', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('Users V2', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addTextControl();

        $this->addShowSocialIconsControl();

        $this->addQueryUsersControls();

        $this->end_controls_section();
    }

    private function addShowSocialIconsControl()
    {
        $this->add_control(
            'users_show_social_icons',
            [
                'label' => esc_html__('Display Social Icons', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showSocialIcons()
    {
        return !empty($this->get_settings_for_display('users_show_social_icons'));
    }

    private function addTextControl()
    {
        $this->add_control(
            'vehica_text',
            [
                'label' => esc_html__('Text', 'vehica-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<h3>As for Passepartout, he thought Mr. Fogg’s manoeuvre simply glorious. The captain had said</h3>“between eleven and twelve knots,”<br><br>And the Henrietta confirmed his prediction. How the adventure ended will be seen anon. Aouda was anxious, though she said nothing.'
            ]
        );
    }

    /**
     * @return bool
     */
    public function hasText()
    {
        return $this->getText() !== '';
    }

    /**
     * @return string
     */
    public function getText()
    {
        return (string)$this->get_settings_for_display('vehica_text');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function showPhone(User $user)
    {
        return $user->hasPhone() && !$user->hidePhone();
    }

}
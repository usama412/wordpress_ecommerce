<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Vehica\Model\Post\Page;
use Vehica\Model\User\User;
use Vehica\Widgets\Controls\SelectRemoteControl;
use Vehica\Widgets\Partials\SwiperPartialWidget;
use Vehica\Widgets\Partials\Users\QueryUsersPartialWidget;

/**
 * Class UsersGeneralWidget
 *
 * @package Vehica\Widgets\General
 */
class UsersGeneralWidget extends GeneralWidget
{
    use QueryUsersPartialWidget;
    use SwiperPartialWidget;

    const NAME = 'vehica_users_general_widget';
    const TEMPLATE = 'general/users';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Users Carousel', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->addContentControls();

        $this->addStyleSections();
    }

    private function addStyleSections()
    {
        $this->addCardStyleSection();

        $this->addUserNameStyleSection();

        $this->addUserPositionStyleSection();

        $this->addUserEmailStyleSection();

        $this->addUserPhoneStyleSection();
    }

    private function addUserNameStyleSection()
    {
        $this->start_controls_section(
            self::NAME . '_name',
            [
                'label' => esc_html__('Name', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextTypographyControl('user_name', '.vehica-user-card__heading a');

        $this->addTextColorControl('user_name', '.vehica-user-card__heading');

        $this->end_controls_section();
    }

    private function addUserPositionStyleSection()
    {
        $this->start_controls_section(
            self::NAME . '_position',
            [
                'label' => esc_html__('Position', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextTypographyControl('user_position', '.vehica-user-card__subheading');

        $this->addTextColorControl('user_position', '.vehica-user-card__subheading');

        $this->end_controls_section();
    }

    private function addUserEmailStyleSection()
    {
        $this->start_controls_section(
            self::NAME . '_email',
            [
                'label' => esc_html__('E-Mail', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextTypographyControl('user_email', '.vehica-user-card__email');

        $this->addTextColorControl('user_email', '.vehica-user-card__email');

        $this->end_controls_section();
    }

    private function addUserPhoneStyleSection()
    {
        $this->start_controls_section(
            self::NAME . '_phone',
            [
                'label' => esc_html__('Phone', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextTypographyControl('user_phone', '.vehica-user-card__phone');

        $this->addTextColorControl('user_phone', '.vehica-user-card__phone');

        $this->end_controls_section();
    }

    private function addCardStyleSection()
    {
        $this->start_controls_section(
            'vehica_users_card_style',
            [
                'label' => esc_html__('Card', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addBackgroundColorControl('user_card', '.vehica-user-card__content');

        $this->addPaddingControl('user_card', '.vehica-user-card__content');

        $this->addBackgroundColorControl(
            'user_card_separator',
            '.vehica-user-card__separator',
            esc_html__('Separator Color', 'vehica-core')
        );

        $this->addShowControl('phone', esc_html__('Display Phone', 'vehica-core'));

        $this->addShowControl('email', esc_html__('Display Email', 'vehica-core'));

        $this->addShowControl('phone_icon', esc_html__('Display Phone Icon', 'vehica-core'));

        $this->addShowControl('email_icon', esc_html__('Display Email Icon', 'vehica-core'));

        $this->end_controls_section();
    }

    private function addContentControls()
    {
        $this->start_controls_section(
            self::NAME . '_content',
            [
                'label' => esc_html__('Users', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addHeadingControl();

        $this->addTextControl();

        $this->addButtonControls();

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

    private function addButtonControls()
    {
        $this->add_control(
            'vehica_show_button',
            [
                'label' => esc_html__('Display Button', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '1',
                'return_value' => '1'
            ]
        );

        $this->add_control(
            'vehica_button_page',
            [
                'label' => esc_html__('Button Page', 'vehica-core'),
                'type' => SelectRemoteControl::TYPE,
                'source' => Page::getApiEndpoint(),
                'condition' => [
                    'vehica_show_button' => '1'
                ]
            ]
        );

        $this->add_control(
            'vehica_button_label',
            [
                'label' => esc_html__('Label', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('learn_more_string'),
                'condition' => [
                    'vehica_show_button' => '1'
                ]
            ]
        );
    }

    /**
     * @return bool
     */
    public function showButton()
    {
        $show = $this->get_settings_for_display('vehica_show_button');

        return !empty($show);
    }

    /**
     * @return string
     */
    public function getButtonUrl()
    {
        $pageId = (int)$this->get_settings_for_display('vehica_button_page');
        $link = get_the_permalink($pageId);

        if (is_wp_error($link)) {
            return '';
        }

        return $link;
    }

    /**
     * @return string
     */
    public function getButtonLabel()
    {
        $label = (string)$this->get_settings_for_display('vehica_button_label');

        if (empty($label)) {
            return vehicaApp('learn_more_string');
        }

        return $label;
    }

    private function addHeadingControl()
    {
        $this->add_control(
            'vehica_heading',
            [
                'label' => esc_html__('Heading', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Our team', 'vehica-core')
            ]
        );
    }

    private function addTextControl()
    {
        $this->add_control(
            'vehica_text',
            [
                'label' => esc_html__('Text', 'vehica-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<ul>
                    <li>' . esc_html__('very smart', 'vehica-core') . '</li>
                    <li>' . esc_html__('highly motivated', 'vehica-core') . '</li>
                    <li>' . esc_html__('quality oriented', 'vehica-core') . '</li>
                    <li>' . esc_html__('good intentions', 'vehica-core') . '</li>
                    <li>' . esc_html__('waiting for you!', 'vehica-core') . '</li>
                </ul>'
            ]
        );
    }

    /**
     * @return string
     */
    public function getHeading()
    {
        return (string)$this->get_settings_for_display('vehica_heading');
    }

    /**
     * @return string
     */
    public function getText()
    {
        return (string)$this->get_settings_for_display('vehica_text');
    }

    /**
     * @return bool
     */
    public function hasText()
    {
        return $this->getText() !== '';
    }

    /**
     * @return \int[][]
     */
    public function getBreakpoints()
    {
        return [
            [
                'width' => 1199,
                'number' => 4
            ],
            [
                'width' => 1023,
                'number' => 3
            ],
            [
                'width' => 629,
                'number' => 2
            ],
            [
                'width' => 0,
                'number' => 1
            ],
        ];
    }

    /**
     * @param User $user
     * @return bool
     */
    public function showPhone(User $user)
    {
        return $this->showElement('phone_icon') && $user->hasPhone() && !$user->hidePhone();
    }

}
<?php

namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Vehica\Components\Menu\Menu;

/**
 * Class MenuGeneralWidget
 * @package Vehica\Widgets\General
 */
class MenuGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_menu_general_widget';
    const TEMPLATE = 'general/menu/menu';
    const MENU = 'vehica_menu';
    const STYLE = 'vehica_menu_style';
    const STYLE_1 = 'vehica_menu_style_1';
    const STYLE_3 = 'vehica_menu_style_3';
    const LOGO_TYPE = 'vehica_logo_type';
    const LOGO_TYPE_STANDARD = 'standard';
    const LOGO_TYPE_INVERSE = 'inverse';
    const STICKY_LOGO_TYPE = 'vehica_sticky_logo_type';
    const STICKY_LOGO_TYPE_SAME = 'same';
    const STICKY_LOGO_TYPE_STANDARD = 'standard';
    const STICKY_LOGO_TYPE_INVERSE = 'inverse';
    const SHOW_TOP_BAR = 'vehica_show_top_bar';
    const SHOW_SUBMIT_BUTTON = 'vehica_show_submit_button';
    const SHOW_ACCOUNT_DETAILS = 'vehica_show_account_details';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Main Menu', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->addContentControls();

        $this->addStyleSections();
    }

    private function addStyleSections()
    {
        $this->addDesktopMenuStyleControls();

        $this->addDesktopStickyMenuStyleControls();

        $this->addDesktopSubMenuStyleControls();

        $this->addMobileMenuClosedStyleControls();

        $this->addMobileMenuOpenStyleControls();

        $this->addButtonDesktopStyleControls();

        $this->addButtonMobileStyleControls();
    }

    private function addButtonMobileStyleControls()
    {
        $this->start_controls_section(
            'button_mobile_style',
            [
                'label' => esc_html__('Button CTA Mobile', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'button_mobile_color',
            [
                'label' => esc_html__('Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__open__top__submit-button .vehica-button' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'button_mobile_color_hover',
            [
                'label' => esc_html__('Color Hover', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__open__top__submit-button .vehica-button:hover' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'button_mobile_background',
            [
                'label' => esc_html__('Background Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__open__top__submit-button .vehica-button' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'button_mobile_background_hover',
            [
                'label' => esc_html__('Background Color Hover', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__open__top__submit-button .vehica-button:hover' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'button_mobile_icon',
            [
                'label' => esc_html__('Icon', 'vehica-core'),
                'type' => Controls_Manager::ICONS
            ]
        );

        $this->add_control(
            'button_mobile_border_heading',
            [
                'label' => esc_html__('Border', 'vehica-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_mobile_border',
                'label' => esc_html__('Border', 'vehica-core'),
                'selector' => '.vehica-mobile-menu__open__top__submit-button .vehica-button'
            ]
        );

        $this->add_responsive_control(
            'button_mobile_border_radius',
            [
                'label' => esc_html__('Border Radius', 'vehica-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__open__top__submit-button .vehica-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'button_mobile_border_heading_hover',
            [
                'label' => esc_html__('Border Hover', 'vehica-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_mobile_border_hover',
                'label' => esc_html__('Border Hover', 'vehica-core'),
                'selector' => '.vehica-mobile-menu__open__top__submit-button .vehica-button:hover'
            ]
        );

        $this->add_responsive_control(
            'button_mobile_border_radius_hover',
            [
                'label' => esc_html__('Border Radius Hover', 'vehica-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__open__top__submit-button .vehica-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();
    }

    private function addButtonDesktopStyleControls()
    {
        $this->start_controls_section(
            'button_desktop_style',
            [
                'label' => esc_html__('Button CTA Desktop', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => esc_html__('Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-button.vehica-button--menu-submit' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'button_color_hover',
            [
                'label' => esc_html__('Color Hover', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-button.vehica-button--menu-submit:hover' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__('Background Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-button.vehica-button--menu-submit' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'button_background_color_hover',
            [
                'label' => esc_html__('Background Color Hover', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-button.vehica-button--menu-submit:hover' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => esc_html__('Icon', 'vehica-core'),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'button_border_heading',
            [
                'label' => esc_html__('Border', 'vehica-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'label' => esc_html__('Border', 'vehica-core'),
                'selector' => '.vehica-button.vehica-button--menu-submit'
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'vehica-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-button.vehica-button--menu-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'button_border_heading_hover',
            [
                'label' => esc_html__('Border Hover', 'vehica-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border_hover',
                'label' => esc_html__('Border Hover', 'vehica-core'),
                'selector' => '.vehica-button.vehica-button--menu-submit:hover'
            ]
        );

        $this->add_responsive_control(
            'button_border_radius_hover',
            [
                'label' => esc_html__('Border Radius Hover', 'vehica-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-button.vehica-button--menu-submit:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();
    }

    private function addDesktopStickyMenuStyleControls()
    {
        $this->start_controls_section(
            self::NAME . '_desktop_menu_sticky',
            [
                'label' => esc_html__('Desktop Sticky Menu', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'menu_desktop_sticky_bg_color',
            [
                'label' => esc_html__('Background Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.vehica-menu-sticky-active {{WRAPPER}} .vehica-menu__desktop .vehica-menu__wrapper' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sticky_menu_link_typography',
                'label' => esc_html__('Link Typography', 'vehica-core'),
                'selector' => '.vehica-menu-sticky-active {{WRAPPER}} .vehica-menu-item-depth-0 > *',
            ]
        );


        $this->add_control(
            'sticky_menu_link_color',
            [
                'label' => esc_html__('Link Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.vehica-menu-sticky-active {{WRAPPER}} .vehica-menu__desktop .vehica-menu > .menu-item > .vehica-menu__link' => 'color: {{VALUE}};',
                    '.vehica-menu-sticky-active {{WRAPPER}} .vehica-menu-desktop-login-register-link a' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'sticky_menu_dropdown_color',
            [
                'label' => esc_html__('Dropdown Arrow Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.vehica-menu-sticky-active {{WRAPPER}} .vehica-menu__wrapper .vehica-menu > .menu-item-has-children > .vehica-menu__link:after' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'sticky_menu_link_hover_color',
            [
                'label' => esc_html__('Link Hover Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.vehica-menu-sticky-active {{WRAPPER}} .vehica-menu__desktop .vehica-menu > .menu-item:hover > .vehica-menu__link' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'sticky_menu_dropdown_hover_color',
            [
                'label' => esc_html__('Dropdown Arrow Hover Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.vehica-menu-sticky-active {{WRAPPER}} .vehica-menu__wrapper .vehica-menu > .menu-item-has-children:hover > .vehica-menu__link:after' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'sticky_menu_user_icon_color',
            [
                'label' => esc_html__('User Icon Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.vehica-menu-sticky-active {{WRAPPER}} .vehica-menu-desktop-login-register-link__user-icon i' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();
    }

    private function addDesktopMenuStyleControls()
    {
        $this->start_controls_section(
            self::NAME . '_desktop_menu',
            [
                'label' => esc_html__('Desktop Menu', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'menu_desktop_bg_color',
            [
                'label' => esc_html__('Background Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu__desktop' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_link_typography',
                'label' => esc_html__('Link Typography', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-menu-item-depth-0 > *',
            ]
        );


        $this->add_control(
            'menu_link_color',
            [
                'label' => esc_html__('Link Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu__desktop .vehica-menu > .menu-item > .vehica-menu__link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .vehica-menu-desktop-login-register-link a' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'menu_dropdown_color',
            [
                'label' => esc_html__('Dropdown Arrow Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu__wrapper .vehica-menu > .menu-item-has-children > .vehica-menu__link:after' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'menu_line_hover_color',
            [
                'label' => esc_html__('"Hover Line Above Link" Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu-hover' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'menu_link_hover_color',
            [
                'label' => esc_html__('Link Hover Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu__desktop .vehica-menu > .menu-item:hover > .vehica-menu__link' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'menu_dropdown_hover_color',
            [
                'label' => esc_html__('Dropdown Arrow Hover Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu__wrapper .vehica-menu > .menu-item-has-children:hover > .vehica-menu__link:after' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'menu_user_icon_color',
            [
                'label' => esc_html__('User Icon Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu-desktop-login-register-link__user-icon i' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();
    }

    private function addDesktopSubMenuStyleControls()
    {
        $this->start_controls_section(
            self::NAME . '_desktop_submenu',
            [
                'label' => esc_html__('Desktop Submenu', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'submenu_bg_color',
            [
                'label' => esc_html__('Background Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu__desktop .vehica-submenu' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .vehica-menu__wrapper .vehica-menu > .menu-item > .vehica-submenu:before' => 'border-bottom-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'submenu_border_color',
            [
                'label' => esc_html__('Border Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu__desktop .vehica-submenu' => 'border-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submenu_shadow',
                'label' => esc_html__('Shadow', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-menu__desktop .vehica-submenu'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'submenu_link_typography',
                'label' => esc_html__('Link Typography', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-menu__desktop .vehica-submenu a'
            ]
        );

        $this->add_control(
            'submenu_link_color',
            [
                'label' => esc_html__('Link Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu__desktop .vehica-submenu a' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'submenu_link_hover_color',
            [
                'label' => esc_html__('Hover Link Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu__desktop .vehica-submenu a:hover' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'submenu_separator_control',
            [
                'label' => esc_html__('Separator Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu__wrapper .vehica-menu .vehica-submenu .vehica-menu__link' => 'border-bottom-color: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();
    }

    private function addMobileMenuClosedStyleControls()
    {
        $this->start_controls_section(
            self::NAME . '_mobile_menu_closed',
            [
                'label' => esc_html__('Mobile Menu Closed', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'mobile_menu_closed_bg_color',
            [
                'label' => esc_html__('Background Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__wrapper' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'mobile_menu_closed_hamburger_icon_color',
            [
                'label' => esc_html__('Hamburger Icon Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu-icon *' => 'fill: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'mobile_menu_closed_user_icon_color',
            [
                'label' => esc_html__('User Icon Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__wrapper .vehica-mobile-menu__login a' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();
    }

    private function addMobileMenuOpenStyleControls()
    {
        $this->start_controls_section(
            self::NAME . '_mobile_menu_open',
            [
                'label' => esc_html__('Mobile Menu Open', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'mobile_menu_open_top_bg_color',
            [
                'label' => esc_html__('Top Background Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__wrapper .vehica-mobile-menu__open .vehica-mobile-menu__open__top' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'mobile_menu_open_close_icon_color',
            [
                'label' => esc_html__('Close Icon Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__open__top__x svg *' => 'fill: {{VALUE}} !important;'
                ]
            ]
        );

        $this->add_control(
            'mobile_menu_open_bg_color',
            [
                'label' => esc_html__('Background Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__wrapper .vehica-mobile-menu__open' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'mobile_menu_open_link_typography',
                'label' => esc_html__('Link Typography', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-mobile-menu__wrapper .vehica-mobile-menu__open .menu-item .vehica-menu__link'
            ]
        );

        $this->add_control(
            'mobile_menu_open_link_color',
            [
                'label' => esc_html__('Link Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__wrapper .vehica-mobile-menu__open .menu-item .vehica-menu__link' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'mobile_menu_open_submenu_link_color',
            [
                'label' => esc_html__('Active Link Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-mobile-menu__wrapper .vehica-mobile-menu__open .menu-item.vehica-open > a' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @return array
     */
    protected function getMenuOptions()
    {
        return [
                '0' => esc_html__('Primary Menu', 'vehica-core'),
            ] + vehicaApp('menus')->toList() + [
                'hide' => esc_html__('Hide', 'vehica-core')
            ];
    }

    protected function addContentControls()
    {
        $this->start_controls_section(
            'vehica_menu_general',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addSelectMenuControl();

        $this->addMenuHeightControl();

        $this->addLogoHeightControl();

        $this->addStickyLogoHeightControl();

        $this->addShowTopBarControl();

        $this->addLogoControls();

        $this->addStyleControl();

        $this->addShowAccountDetailsControl();

        $this->addShowSubmitButtonControl();

        $this->end_controls_section();
    }

    private function addShowAccountDetailsControl()
    {
        if (!vehicaApp('show_menu_button')) {
            $this->add_control('show_account_heading',
                [
                    'label' => esc_html__('Info: Dashboard and Login / Register Link is hidden globally in the Vehica Panel - Basic Settings - Menu', 'vehica-core'),
                    'type' => Controls_Manager::HEADING,
                ]
            );
        }

        $this->add_control(
            self::SHOW_ACCOUNT_DETAILS,
            [
                'label' => esc_html__('Display "Dashboard" and "Login / Register" Link', 'vehica-core'),
                'label_block' => true,
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showAccountDetails()
    {
        $show = (int)$this->get_settings_for_display(self::SHOW_ACCOUNT_DETAILS);
        return !empty($show);
    }

    private function addShowSubmitButtonControl()
    {
        if (!vehicaApp('show_menu_button')) {
            $this->add_control('show_button_heading',
                [
                    'label' => esc_html__('Info: CTA Button is hidden globally in the Vehica Panel - Basic Settings - Menu', 'vehica-core'),
                    'type' => Controls_Manager::HEADING,
                ]
            );
        }

        $this->add_control(
            self::SHOW_SUBMIT_BUTTON,
            [
                'label' => esc_html__('Display CTA Button', 'vehica-core'),
                'label_block' => true,
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1',
            ]
        );
    }

    /**
     * @return bool
     */
    public function showSubmitButton()
    {
        $showSubmitButton = (int)$this->get_settings_for_display(self::SHOW_SUBMIT_BUTTON);
        return !empty($showSubmitButton) && vehicaApp('show_menu_button');
    }

    private function addMenuHeightControl()
    {
        $this->add_control(
            'vehica_menu_height',
            [
                'label' => esc_html__('Menu Height (px)', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => '70',
                'selectors' => [
                    '{{WRAPPER}} .vehica-menu__wrapper' => 'height: {{VALUE}}px;',
                    '{{WRAPPER}} .vehica-menu__desktop' => 'height: {{VALUE}}px;'
                ]
            ]
        );
    }

    private function addLogoHeightControl()
    {
        $this->add_responsive_control(
            'logo_max_height',
            [
                'label' => esc_html__('Logo Height (px)', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vehica-logo img' => 'max-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
                ],
                'frontend_available' => true,
            ]
        );
    }

    private function addStickyLogoHeightControl()
    {
        $this->add_responsive_control(
            'sticky_logo_height',
            [
                'label' => esc_html__('Sticky Logo Height (px)', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '.vehica-menu-sticky-active {{WRAPPER}} .vehica-logo img' => 'height: {{SIZE}}{{UNIT}} !important; max-height: {{SIZE}}{{UNIT}} !important;'
                ],
                'frontend_available' => true,
            ]
        );
    }

    private function addStyleControl()
    {
        $this->add_control(
            self::STYLE,
            [
                'label' => esc_html__('Style', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    self::STYLE_1 => esc_html__('Standard', 'vehica-core'),
                    self::STYLE_3 => esc_html__('Transparent (fixed to top)', 'vehica-core'),
                ],
                'default' => self::STYLE_1
            ]
        );
    }

    private function addLogoControls()
    {
        $this->add_control(
            self::LOGO_TYPE,
            [
                'label' => esc_html__('Logo', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'default' => self::LOGO_TYPE_STANDARD,
                'options' => [
                    self::LOGO_TYPE_STANDARD => esc_html__('Standard', 'vehica-core'),
                    self::LOGO_TYPE_INVERSE => esc_html__('Inverse', 'vehica-core'),
                ],
            ]
        );

        $this->add_control(
            self::STICKY_LOGO_TYPE,
            [
                'label' => esc_html__('Logo (Sticky)', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'default' => self::STICKY_LOGO_TYPE_SAME,
                'options' => [
                    self::STICKY_LOGO_TYPE_SAME => esc_html__('The same', 'vehica-core'),
                    self::STICKY_LOGO_TYPE_STANDARD => esc_html__('Standard', 'vehica-core'),
                    self::STICKY_LOGO_TYPE_INVERSE => esc_html__('Inverse', 'vehica-core'),
                ],
            ]
        );
    }

    private function addShowTopBarControl()
    {
        $this->add_control(
            self::SHOW_TOP_BAR,
            [
                'label' => esc_html__('Display Top bar (desktop only)', 'vehica-core'),
                'label_block' => true,
                'type' => Controls_Manager::SWITCHER,
                'default' => '0',
                'return_value' => '1'
            ]
        );
    }

    private function addSelectMenuControl()
    {
        $this->add_control(
            self::MENU,
            [
                'label' => esc_html__('Menu', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->getMenuOptions(),
                'default' => '0'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showTopBar()
    {
        $show = $this->get_settings_for_display(self::SHOW_TOP_BAR);

        return !empty($show);
    }

    protected function render()
    {
        $this->addMenuWrapperAttributes();

        $this->loadTemplate();
    }

    protected function addMenuWrapperAttributes()
    {
        $menuWrapperClasses = ['vehica-menu-wrapper', 'vehica-app'];

        if ($this->isFirstStyle()) {
            $menuWrapperClasses[] = 'vehica-menu-wrapper--regular';
        }

        if ($this->isThirdStyle()) {
            $menuWrapperClasses[] = 'vehica-menu-wrapper--transparent';
        }

        /** @noinspection UnusedFunctionResultInspection */
        $this->add_render_attribute('menu_wrapper', 'class', implode(' ', $menuWrapperClasses));
    }

    /**
     * @return bool
     */
    public function hasMenu()
    {
        return $this->getMenu() !== false;
    }

    public function displayMenu($id = 'vehica-menu')
    {
        $menu = $this->getMenu();
        if ($menu) {
            $menu->display($id);
        }
    }

    /**
     * @return Menu|false
     */
    protected function getMenu()
    {
        $menuId = $this->get_settings_for_display(self::MENU);

        if ($menuId === 'hide') {
            return false;
        }

        $menuId = (int)$menuId;
        if (empty($menuId)) {
            return wp_get_nav_menu_object(vehicaApp('primary_menu_id')) ? new Menu(vehicaApp('primary_menu_id')) : false;
        }

        return new Menu($menuId);
    }

    /**
     * @return string
     */
    public function getStyle()
    {
        return (string)$this->get_settings_for_display(self::STYLE);
    }

    /**
     * @return bool
     */
    public function isFirstStyle()
    {
        return $this->getStyle() === self::STYLE_1;
    }

    /**
     * @return bool
     */
    public function isThirdStyle()
    {
        return $this->getStyle() === self::STYLE_3;
    }

    /**
     * @return bool
     */
    public function hasLogo()
    {
        $logoUrl = $this->getLogoUrl();

        return !empty($logoUrl);
    }

    /**
     * @return string|false
     */
    public function getLogoUrl()
    {
        $logoType = (string)$this->get_settings_for_display(self::LOGO_TYPE);

        if ($logoType === self::LOGO_TYPE_STANDARD) {
            return vehicaApp('logo_url');
        }

        return vehicaApp('inverse_logo_url');
    }

    /**
     * @return bool
     */
    public function hasStickyLogo()
    {
        return vehicaApp('sticky_menu') && !empty($this->getStickyLogoUrl());
    }

    /**
     * @return string
     */
    public function getStickyLogoUrl()
    {
        $logoType = (string)$this->get_settings_for_display(self::STICKY_LOGO_TYPE);

        if ($logoType === self::STICKY_LOGO_TYPE_SAME) {
            return $this->getLogoUrl();
        }

        if ($logoType === self::STICKY_LOGO_TYPE_STANDARD) {
            return vehicaApp('logo_url');
        }

        return vehicaApp('inverse_logo_url');
    }

    /**
     * @return bool
     */
    public function showLogo()
    {
        return $this->hasLogo() || $this->hasStickyLogo();
    }

    /**
     * @return bool
     */
    public function isTransparent()
    {
        return $this->isThirdStyle();
    }

    /**
     * @return string
     */
    public function getClass()
    {
        $class = ['vehica-header'];

        if ($this->showSubmitButton()) {
            $class[] = 'vehica-header--with-submit-button';
        } else {
            $class[] = 'vehica-header--no-submit-button';
        }

        if ($this->showAccountDetails()) {
            $class[] = 'vehica-header--with-dashboard-link';
        } else {
            $class[] = 'vehica-header--no-dashboard-link';
        }

        return implode(' ', $class);
    }

    /**
     * @return bool
     */
    public function hasButtonCustomIcon()
    {
        return $this->getButtonCustomIcon() !== '';
    }

    /**
     * @return string
     */
    public function getButtonCustomIcon()
    {
        $icon = $this->get_settings_for_display('button_icon');

        if (empty($icon['value'])) {
            return '';
        }

        return $icon['value'];
    }

    /**
     * @return bool
     */
    public function hasButtonMobileCustomIcon()
    {
        return $this->getButtonMobileCustomIcon() !== '';
    }

    /**
     * @return string
     */
    public function getButtonMobileCustomIcon()
    {
        $icon = $this->get_settings_for_display('button_mobile_icon');

        if (empty($icon['value'])) {
            return '';
        }

        return $icon['value'];
    }

    /**
     * @return string
     */
    public function getSubmitButtonUrl()
    {
        return apply_filters('vehica/menu/submitButtonUrl', PanelGeneralWidget::getCreateCarPageUrl());
    }

    /**
     * @return bool
     */
    public function showAccount()
    {
        return vehicaApp('show_menu_account') && $this->showAccountDetails() && vehicaApp('settings_config')->hasLoginPage();
    }

}
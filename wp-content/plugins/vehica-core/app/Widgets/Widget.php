<?php

namespace Vehica\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

/**
 * Class Element
 * @package Vehica\Widgets
 */
abstract class Widget extends Widget_Base
{
    const NAME = 'vehica_element';
    const TEMPLATE = '';

    /**
     * @return string
     */
    public function get_name()
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-vehica eicon-vehica--general';
    }

    /**
     * @return void
     */
    protected function loadTemplate()
    {
        $this->prepareObject();

        get_template_part('templates/' . static::TEMPLATE);
    }

    protected function prepareObject()
    {
        $elementName = str_replace(' ', '', ucwords(str_replace('_', ' ', static::NAME)));
        if (!empty($elementName)) {
            $elementName[0] = strtolower($elementName[0]);
        }

        global $vehicaCurrentWidget;
        global ${$elementName};
        ${$elementName} = $vehicaCurrentWidget = $this;
    }

    /**
     * @param string $key
     * @param array|string $selectors
     * @param string $label
     * @param array $condition
     * @param array $settings
     */
    protected function addMarginControl($key, $selectors, $label = '', $condition = [], $settings = [])
    {
        $this->add_responsive_control(
            $key . '_margin',
            [
                'label' => $this->getLabel($label, esc_html__('Margin', 'vehica-core')),
                'type' => Controls_Manager::DIMENSIONS,
                'frontend_available' => true,
                'size_units' => ['px', '%', 'em'],
                'selectors' => $this->getSelectors(
                    $selectors,
                    'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ),
                'condition' => $condition
            ] + $settings
        );
    }

    /**
     * @param string $key
     * @param array|string $selectors
     * @param string $label
     * @param array $condition
     * @param array $additionalSettings
     */
    protected function addPaddingControl($key, $selectors, $label = '', $condition = [], $additionalSettings = [])
    {
        $this->add_responsive_control(
            $key . '_padding',
            [
                'label' => $this->getLabel($label, esc_html__('Padding', 'vehica-core')),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => $this->getSelectors(
                    $selectors,
                    'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ),
                'condition' => $condition
            ] + $additionalSettings
        );
    }

    /**
     * @param string $key
     * @param array|string $selectors
     * @param string $label
     * @param array $condition
     */
    protected function addBorderRadiusControl($key, $selectors, $label = '', $condition = [])
    {
        $this->add_responsive_control(
            $key . '_border_radius',
            [
                'label' => $this->getLabel($label, esc_html__('Border Radius', 'vehica-core')),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'frontend_available' => true,
                'selectors' => $this->getSelectors(
                    $selectors,
                    'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ),
                'condition' => $condition,
            ]
        );
    }

    /**
     * @param string $key
     * @param string $selector
     * @param string $label
     * @param array $condition
     */
    protected function addTextAlignControl($key, $selector, $label = '', $condition = [])
    {
        $this->add_responsive_control(
            $key . '_text_align',
            [
                'label' => $this->getLabel($label, esc_html__('Alignment', 'vehica-core')),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'vehica-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'vehica-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'vehica-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'vehica-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $selector => 'text-align: {{VALUE}};',
                ],
                'condition' => $condition,
                'frontend_available' => true,
            ]
        );
    }

    /**
     * @param string $key
     * @param string $selector
     * @param array $settings
     * @param array $condition
     */
    protected function addTextTypographyControl($key, $selector, $settings = [], $condition = [])
    {
        $args = [
            'name' => $key . '_text_typography',
            'label' => esc_html__('Typography', 'vehica-core'),
            'selector' => $this->applyWrapperToSelector($selector),
            'fields_options' => $settings,
            'condition' => $condition,
        ];

        $this->add_group_control(Group_Control_Typography::get_type(), $args);
    }

    /**
     * @param string $key
     * @param string|array $selectors
     * @param string $label
     * @param array $condition
     */
    protected function addTextColorControl($key, $selectors, $label = '', $condition = [])
    {
        $this->add_control(
            $key . '_text_color',
            [
                'label' => $this->getLabel($label, esc_html__('Text Color', 'vehica-core')),
                'type' => Controls_Manager::COLOR,
                'selectors' => $this->getSelectors($selectors, 'color: {{VALUE}}'),
                'condition' => $condition
            ]
        );
    }

    /**
     * @param string $key
     * @param array|string $selectors
     * @param string $label
     * @param array $condition
     */
    protected function addBackgroundColorControl($key, $selectors, $label = '', $condition = [])
    {
        $this->add_control(
            $key . '_background_color',
            [
                'label' => $this->getLabel($label, esc_html__('Background Color', 'vehica-core')),
                'type' => Controls_Manager::COLOR,
                'selectors' => $this->getSelectors($selectors, 'background-color: {{VALUE}}!important;'),
                'condition' => $condition
            ]
        );
    }

    /**
     * @param string $key
     * @param array|string $selectors
     * @param string $label
     * @param array $condition
     */
    protected function addBorderColorControl($key, $selectors, $label = '', $condition = [])
    {
        $this->add_control(
            $key . '_border_color',
            [
                'label' => $this->getLabel($label, esc_html__('Border Color', 'vehica-core')),
                'type' => Controls_Manager::COLOR,
                'selectors' => $this->getSelectors($selectors, 'border-color: {{VALUE}};'),
                'condition' => $condition
            ]
        );
    }

    /**
     * @param string $label
     * @param string $defaultLabel
     * @return string
     */
    private function getLabel($label, $defaultLabel)
    {
        return empty($label) ? $defaultLabel : $label;
    }

    /**
     * @param string $selector
     * @return string
     */
    private function applyWrapperToSelector($selector)
    {
        if (strpos($selector, '{{WRAPPER}}') === false) {
            return '{{WRAPPER}} ' . $selector;
        }

        return $selector;
    }

    /**
     * @param array|string $selectors
     * @param string $css
     * @return array
     */
    private function getSelectors($selectors, $css)
    {
        if (!is_array($selectors)) {
            $selectors = [$selectors];
        }

        $finalSelectors = [];
        foreach ($selectors as $selector) {
            $finalSelectors[$this->applyWrapperToSelector($selector)] = $css;
        }

        return $finalSelectors;
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            static::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'vehica_no_options_info',
            [
                'label' => esc_html__('No Settings', 'vehica-core'),
                'type' => Controls_Manager::HEADING
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $this->loadTemplate();
    }

    /**
     * @param string $key
     * @return string
     */
    public function getImagePadding($key = 'vehica_image_size')
    {
        $sizes = vehicaApp('image_sizes');
        $imageSize = $this->getImageType($key);

        if ($imageSize === 'custom') {
            $dimensions = $this->get_settings_for_display($key . '_custom_dimension');
            return (((int)$dimensions['height'] / (int)$dimensions['width']) * 100) . '%';
        }

        if (!isset($sizes[$imageSize])) {
            return '75%';
        }

        return (($sizes[$imageSize]['height'] / $sizes[$imageSize]['width']) * 100) . '%';
    }

    /**
     * @param string $key
     * @return string
     */
    public function getImageSize($key = 'vehica_image_size')
    {
        $imageType = $this->getImageType($key);

        if ($imageType === 'custom') {
            return 'full';
        }

        return $imageType;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getImageType($key = 'vehica_image_size')
    {
        return (string)$this->get_settings_for_display($key . '_size');
    }

    /**
     * @param string $default
     * @param string $key
     * @param string $label
     */
    protected function addImageSizeControl($default, $key = 'vehica_image_size', $label = '')
    {
        if (empty($label)) {
            $label = esc_html__('Image Thumbnail', 'vehica-core');
        }

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => $key,
                'label' => $label,
                'default' => $default
            ]
        );
    }

    /**
     * @param string $key
     * @param string $label
     * @param string $default
     */
    public function addShowControl($key, $label, $default = '1')
    {
        $this->add_control(
            $key . '_show',
            [
                'label' => $label,
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => $default
            ]
        );
    }

    /**
     * @param string $key
     * @return bool
     */
    public function showElement($key)
    {
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $key = (int)$this->get_settings_for_display($key . '_show');
        return !empty($key);
    }

}
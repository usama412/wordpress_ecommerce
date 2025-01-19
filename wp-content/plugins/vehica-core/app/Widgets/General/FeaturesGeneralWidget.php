<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Class FeaturesGeneralWidget
 * @package Vehica\Widgets\General
 */
class FeaturesGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_features_general_widget';
    const TEMPLATE = 'general/features';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Features', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'features_section',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->addIconControl();

        $this->addHeadingControl();

        $this->addSubHeadingControl();

        $this->addFeaturesControl();

        $this->end_controls_section();

        $this->addStyleSections();
    }

    private function addStyleSections()
    {
        $this->addSectionHeadingStyleControls();

        $this->addSectionSubheadingStyleControls();

        $this->addFeatureHeadingStyleControls();

        $this->addFeatureTextStyleControls();
    }

    private function addSectionHeadingStyleControls()
    {
        $this->start_controls_section(
            'section_heading_style',
            [
                'label' => esc_html__('Section Heading', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->addTextColorControl(
            'section_heading',
            '.vehica-heading__title'
        );

        $this->addTextTypographyControl(
            'section_heading',
            '.vehica-heading__title'
        );

        $this->end_controls_section();
    }

    private function addSectionSubheadingStyleControls()
    {
        $this->start_controls_section(
            'section_subheading_style',
            [
                'label' => esc_html__('Section Subheading', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->addTextColorControl(
            'section_subheading',
            '.vehica-heading__text'
        );

        $this->addTextTypographyControl(
            'section_subheading',
            '.vehica-heading__text'
        );

        $this->end_controls_section();
    }

    private function addFeatureHeadingStyleControls()
    {
        $this->start_controls_section(
            'feature_heading_style',
            [
                'label' => esc_html__('Feature Heading', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->addTextColorControl(
            'feature_heading',
            '.vehica-features__label'
        );

        $this->addTextTypographyControl(
            'feature_heading',
            '.vehica-features__label'
        );

        $this->end_controls_section();
    }

    private function addFeatureTextStyleControls()
    {
        $this->start_controls_section(
            'feature_text_style',
            [
                'label' => esc_html__('Feature Text', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->addTextColorControl(
            'feature_text',
            '.vehica-features__text'
        );

        $this->addTextTypographyControl(
            'feature_text',
            '.vehica-features__text'
        );

        $this->end_controls_section();
    }

    private function addFeaturesControl()
    {
        $repeater = new Repeater();

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'vehica-core'),
                'type' => Controls_Manager::ICONS
            ]
        );

        $repeater->add_control(
            'label',
            [
                'label' => esc_html__('Label', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'text',
            [
                'label' => esc_html__('Text', 'vehica-core'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $this->add_control(
            'features',
            [
                'label' => esc_html__('Features', 'vehica-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );
    }

    /**
     * @return array
     */
    public function getFeatures()
    {
        return $this->get_settings_for_display('features');
    }

    /**
     * @return string
     */
    public function getHeading()
    {
        $heading = (string)$this->get_settings_for_display('heading');

        if (empty($heading)) {
            return vehicaApp('testimonials_string');
        }

        return $heading;
    }

    private function addHeadingControl()
    {
        $this->add_control(
            'heading',
            [
                'label' => esc_html__('Heading', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Shop, Finance and Buy Your Car', 'vehica-core'),
            ]
        );
    }

    private function addSubHeadingControl()
    {
        $this->add_control(
            'subheading',
            [
                'label' => esc_html__('Subheading', 'vehica-core'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '<span>Visit one of the largest</span> used car dealerships<br> in the New York. Visit us today.',
            ]
        );
    }

    /**
     * @return string
     */
    public function getSubheading()
    {
        return (string)$this->get_settings_for_display('subheading');
    }

    public function hasSubheading()
    {
        return !empty(trim($this->getSubheading()));
    }

    public function addIconControl()
    {
        $this->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'vehica-core'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => ''
                ]
            ]
        );
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        $icon = $this->get_settings_for_display('icon');

        if (empty($icon['value'])) {
            return '';
        }

        return $icon['value'];
    }

    /**
     * @return bool
     */
    public function hasIcon()
    {
        return !empty($this->getIcon());
    }

}
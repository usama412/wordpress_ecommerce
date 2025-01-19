<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Core\Collection;
use Vehica\Model\Post\Car;

/**
 * Class FeaturesSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class FeaturesSingleCarWidget extends SingleCarWidget
{
    use FeaturesPartial;

    const NAME = 'vehica_features_single_car_widget';
    const TEMPLATE = 'car/single/features';

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
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addFeatureControls();

        $this->addTextColorControl(
            'vehica_features',
            '.vehica-car-feature'
        );

        $this->addTextTypographyControl(
            'vehica_features',
            '.vehica-car-feature'
        );

        $this->add_responsive_control(
            'vehica_dot_size',
            [
                'label' => esc_html__('Dot Size', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}}  .vehica-car-feature i' => 'font-size: {{SIZE}}{{UNIT}};'
                ],
                'frontend_available' => true,
            ]
        );

        $this->addTextColorControl('dot', '.vehica-car-feature i');

        $this->end_controls_section();
    }

    /**
     * @param Car $car
     *
     * @return Collection
     */
    public function getFeatures(Car $car)
    {
        $features = Collection::make();

        foreach ($this->getFields() as $field) {
            /* @var SimpleTextAttribute $field */
            $features = $features->merge($field->getSimpleTextValues($car));
        }

        return $features;
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}
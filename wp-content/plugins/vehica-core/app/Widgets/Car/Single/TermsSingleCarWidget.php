<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;

/**
 * Class TermsSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class TermsSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_terms_single_car_widget';
    const TEMPLATE = 'car/single/terms';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Terms', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'type' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addTaxonomyControl();

        $this->end_controls_section();
    }

    private function addTaxonomyControl()
    {
        $list = vehicaApp('taxonomies_key_list');

        $this->add_control(
            'vehica_taxonomy',
            [
                'label' => esc_html__('Taxonomy', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $list,
                'default' => !empty($list) ? key($list) : null
            ]
        );
    }

    /**
     * @return Taxonomy|false
     */
    public function getTaxonomy()
    {
        $taxonomyKey = $this->get_settings_for_display('vehica_taxonomy');

        if (empty($taxonomyKey)) {
            return false;
        }

        return vehicaApp('taxonomies')->find(static function ($taxonomy) use ($taxonomyKey) {
            /* @var Taxonomy $taxonomy */
            return $taxonomy->getKey() === $taxonomyKey;
        });
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}
<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Model\Post\Field\EmbedField;

/**
 * Class EmbedSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class EmbedSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_embed_single_car_widget';
    const TEMPLATE = 'car/single/embed';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Embed Field', 'vehica-core');
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

        $this->addEmbedFieldControl();

        $this->addHideTitleControl();

        $this->end_controls_section();
    }

    private function addHideTitleControl()
    {
        $this->add_control(
            'vehica_show_title',
            [
                'label' => esc_html__('Display Title', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showTitle()
    {
        $show = (int)$this->get_settings_for_display('vehica_show_title');
        return !empty($show);
    }

    private function addEmbedFieldControl()
    {
        $list = vehicaApp('embed_field_key_list');

        $this->add_control(
            'vehica_embed_field',
            [
                'label' => esc_html__('Embed Field', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $list,
                'default' => !empty($list) ? key($list) : null
            ]
        );
    }

    /**
     * @return EmbedField|false
     */
    public function getEmbedField()
    {
        $embedFieldKey = $this->get_settings_for_display('vehica_embed_field');
        return vehicaApp('embed_fields')->find(static function ($embedField) use ($embedFieldKey) {
            /* @var EmbedField $embedField */
            return $embedField->getKey() === $embedFieldKey;
        });
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }
}
<?php


namespace Vehica\Managers;


use Vehica\Core\Collection;
use Vehica\Core\Manager;
use Vehica\Model\Post\Field\Field;

/**
 * Class ElementorScriptsManager
 * @package Vehica\Managers
 */
class ElementorScriptsManager extends Manager
{

    public function boot()
    {
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'load']);
    }

    private function loadCss()
    {
        wp_enqueue_style('vehica-elementor', vehicaApp('url') . 'assets/css/elementor.css', [], vehicaApp('version'));
    }

    public function load()
    {
        $this->loadCss();

        $this->prepareJsData();
    }

    private function prepareJsData()
    {
        wp_localize_script('vehica-elementor', 'McElementor', [
            'fields' => $this->getFields(),
            'sortByOptions' => $this->getSortByOptions(),
        ]);
    }

    /**
     * @return array
     */
    private function getSortByOptions()
    {
        return Collection::make(vehicaApp('sort_by_options'))->map(static function ($sortByName, $sortByKey) {
            return [
                'name' => $sortByName,
                'key' => $sortByKey
            ];
        })->all();
    }

    /**
     * @return array
     */
    private function getFields()
    {
        $fields = vehicaApp('usable_car_fields')->map(static function ($field) {
            /* @var Field $field */
            return [
                'id' => $field->getId(),
                'key' => $field->getKey(),
                'name' => $field->getName()
            ];
        })->all();

        $fields[] = [
            'key' => 'name',
            'name' => esc_html__('Name', 'vehica-core')
        ];

        return $fields;
    }

}
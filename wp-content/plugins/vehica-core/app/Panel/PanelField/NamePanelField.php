<?php


namespace Vehica\Panel\PanelField;


use Vehica\Model\Post\Car;
use WP_Error;

/**
 * Class NamePanelField
 *
 * @package Vehica\Panel\PanelField
 */
class NamePanelField extends PanelField
{
    const KEY = 'vehica_name';

    /**
     * @return string
     */
    public function getKey()
    {
        return self::KEY;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return vehicaApp('listing_title_string');
    }

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'name';
    }

    /**
     * @param Car $car
     * @param array $data
     *
     * @return int|void|WP_Error
     */
    public function update(Car $car, $data = [])
    {
        $title = isset($data['name']) ? $data['name'] : '';

        return $car->setTitle($title);
    }

    /**
     * @return bool
     */
    public function isSingleValue()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return vehicaApp('auto_title_fields')->isEmpty();
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function validate(array $data)
    {
        return !empty($data['name']);
    }

}
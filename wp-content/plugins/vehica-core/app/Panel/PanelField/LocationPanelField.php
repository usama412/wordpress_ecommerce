<?php


namespace Vehica\Panel\PanelField;


use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\LocationField;

/**
 * Class LocationPanelField
 * @package Vehica\Panel\PanelField
 */
class LocationPanelField extends CustomFieldPanelField
{
    /**
     * @var LocationField
     */
    protected $field;

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'location';
    }

    /**
     * @param Car $car
     * @param array $data
     */
    public function update(Car $car, $data = [])
    {
        $this->field->save($car, $this->getValue($data));
    }

    /**
     * @return bool
     */
    public function isSingleValue()
    {
        return false;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validate(array $data)
    {
        $attributeData = $this->getAttributeData($data);

        if (!$attributeData || empty($attributeData['value'])) {
            return false;
        }

        return !empty($attributeData['value']['address']);
    }

    /**
     * @param array $data
     * @return array|string
     */
    private function getValue($data)
    {
        $attributeData = $this->getAttributeData($data);

        if (!$attributeData || !isset($attributeData['value'])) {
            return '';
        }

        return [
            'address' => (string)$attributeData['value']['address'],
            'position' => [
                'lat' => (float)$attributeData['value']['position']['lat'],
                'lng' => (float)$attributeData['value']['position']['lng'],
            ]
        ];
    }

    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();

        $data['countries'] = $this->field->getCountryRestrictions();
        $data['inputType'] = $this->field->getInputType();

        return $data;
    }

}
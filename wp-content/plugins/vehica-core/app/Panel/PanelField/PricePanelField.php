<?php


namespace Vehica\Panel\PanelField;


use Vehica\Model\Post\Car;

/**
 * Class PricePanelField
 *
 * @package Vehica\Panel\PanelField
 */
class PricePanelField extends CustomFieldPanelField
{
    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'price';
    }

    /**
     * @param Car   $car
     * @param array $data
     */
    public function update(Car $car, $data = [])
    {
        $this->field->save($car, $this->getValues($data));
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function getValues($data)
    {
        $attributeData = $this->getAttributeData($data);

        if ( ! $attributeData || ! isset($attributeData['value']) || ! is_array($attributeData['value'])) {
            return [];
        }

        return $attributeData['value'];
    }

    /**
     * @return bool
     */
    public function isSingleValue()
    {
        return true;
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function validate(array $data)
    {
        $attributeData = $this->getAttributeData($data);

        return ! ( ! $attributeData || ! isset($attributeData['value']) || ! is_array($attributeData['value']));
    }

}
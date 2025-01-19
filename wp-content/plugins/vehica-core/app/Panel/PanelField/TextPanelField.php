<?php


namespace Vehica\Panel\PanelField;


use Vehica\Model\Post\Car;

/**
 * Class TextPanelField
 *
 * @package Vehica\Panel\PanelField
 */
class TextPanelField extends CustomFieldPanelField
{
    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'text';
    }

    /**
     * @param Car $car
     * @param array $data
     */
    public function update(Car $car, $data = [])
    {
        $car->setMeta($this->getKey(), $this->getValue($data));
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function getValue($data)
    {
        $attributeData = $this->getAttributeData($data);

        if (!$attributeData || !isset($attributeData['value'])) {
            return '';
        }

        return (string)$attributeData['value'];
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

        return !(!$attributeData || empty($attributeData['value']));
    }

}
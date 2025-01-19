<?php


namespace Vehica\Panel\PanelField;


use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\EmbedField;

/**
 * Class EmbedPanelField
 *
 * @package Vehica\Panel\PanelField
 */
class EmbedPanelField extends CustomFieldPanelField
{
    /**
     * @var EmbedField
     */
    protected $field;

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'embed';
    }

    /**
     * @param Car   $car
     * @param array $data
     */
    public function update(Car $car, $data = [])
    {
        $car->setMeta($this->getKey(), $this->getValue($data));
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function getValue($data)
    {
        $attributeData = $this->getAttributeData($data);

        if (
            ! $attributeData
            || ! is_array($attributeData['value'])
            || ! isset($attributeData['value']['embed'], $attributeData['value']['url'])
        ) {
            return [
                EmbedField::URL   => '',
                EmbedField::EMBED => '',
            ];
        }

        return $attributeData['value'];
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
     *
     * @return bool
     */
    public function validate(array $data)
    {
        if ( ! $this->isRequired()) {
            return true;
        }

        $attributeData = $this->getAttributeData($data);
        if ( ! $attributeData) {
            return false;
        }

        if (
            ! is_array($attributeData['value'])
            || ! isset($attributeData['value']['embed'], $attributeData['value']['url'])
        ) {
            return false;
        }

        return true;
    }

}
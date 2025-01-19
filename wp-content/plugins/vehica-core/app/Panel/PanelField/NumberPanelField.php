<?php /** @noinspection ContractViolationInspection */


namespace Vehica\Panel\PanelField;


use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\NumberField;

/**
 * Class NumberPanelField
 *
 * @package Vehica\Panel\PanelField
 */
class NumberPanelField extends CustomFieldPanelField
{
    /**
     * @var NumberField
     */
    protected $field;

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'number';
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
     * @param array $data
     *
     * @return float|string
     */
    private function getValue($data)
    {
        $attributeData = $this->getAttributeData($data);

        if (!$attributeData || !isset($attributeData['value'])) {
            return '';
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

        return !(!$attributeData || !isset($attributeData['value']));
    }

    /**
     * @return float|int
     */
    public function getStepValue()
    {
        $decimalPlaces = $this->field->getDecimalPlaces();

        if (empty($decimalPlaces)) {
            return 1;
        }

        return 1 / ($decimalPlaces * 10);
    }

}
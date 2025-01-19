<?php /** @noinspection ContractViolationInspection */


namespace Vehica\Panel\PanelField;


use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\DateTimeField;

/**
 * Class DateTimePanelField
 * @package Vehica\Panel\PanelField
 */
class DateTimePanelField extends CustomFieldPanelField
{
    /**
     * @var DateTimeField
     */
    protected $field;

    protected function getTemplate()
    {
        return 'date_time';
    }

    /**
     * @param Car $car
     * @param array $data
     */
    public function update(Car $car, $data = [])
    {
        $attributeData = $this->getAttributeData($data);
        if (!$attributeData || !isset($attributeData['value'])) {
            return;
        }

        $value = $attributeData['value'];

        if ($this->field->isRange()) {
            if (!is_array($value)) {
                return;
            }

            if (count($value) > 0) {
                $car->setMeta($this->getKey() . '_from', $this->getValue($value[0]));
            }

            if (count($value) > 1) {
                $car->setMeta($this->getKey() . '_to', $this->getValue($value[1]));
            }
        } else {
            $car->setMeta($this->getKey(), $this->getValue($value));
        }
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
     * @return bool
     */
    public function validate(array $data)
    {
        return true;
    }

    /**
     * @param array $value
     *
     * @return string
     */
    private function getValue($value)
    {
        $type = $this->field->getValueType();

        if ($type === DateTimeField::TYPE_DATE_TIME) {
            if (!isset($value['date'], $value['time'])) {
                return '';
            }

            return $value['date'] . ' ' . $value['time'];
        }

        if ($type === DateTimeField::TYPE_DATE) {
            if (!isset($value['date'])) {
                return '';
            }

            return $value['date'];
        }

        if ($type === DateTimeField::TYPE_TIME) {
            if (!isset($value['time'])) {
                return '';
            }
            return $value['time'];
        }

        return '';
    }

    /**
     * @return array
     */
    public function getStrings()
    {
        return DateTimeField::getStrings();
    }

}
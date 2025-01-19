<?php /** @noinspection ContractViolationInspection */

/** @noinspection PhpUndefinedClassInspection */


namespace Vehica\Panel\PanelField;


use JsonSerializable;
use Vehica\Model\Post\Car;

/**
 * Class PanelField
 *
 * @package Vehica\Panel\PanelField
 */
abstract class PanelField implements JsonSerializable
{
    /**
     * @var Car|null
     */
    protected $car;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * PanelField constructor.
     *
     * @param array $config
     * @param null $car
     */
    public function __construct($config = [], $car = null)
    {
        $this->config = $config;
        $this->car = $car;
    }

    /**
     * @return string
     */
    abstract public function getKey();

    /**
     * @return string
     */
    abstract public function getLabel();

    /**
     * @return string
     */
    abstract protected function getTemplate();

    public function loadTemplate()
    {
        global $vehicaPanelField;
        $vehicaPanelField = $this;
        get_template_part('templates/general/panel/field/' . $this->getTemplate());
    }

    /**
     * @param Car $car
     * @param array $data
     *
     * @return void
     */
    abstract public function update(Car $car, $data = []);

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'key' => $this->getKey(),
            'label' => $this->getLabel(),
            'config' => $this->config
        ];
    }

    /**
     * @return bool
     */
    abstract public function isSingleValue();

    /**
     * @return bool
     */
    abstract public function isRequired();

    /**
     * @param array $data
     *
     * @return bool
     */
    abstract public function validate(array $data);

}
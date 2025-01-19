<?php /** @noinspection ContractViolationInspection */


namespace Vehica\Search\Field;


use Vehica\Core\Collection;
use Vehica\Model\Post\Field\LocationField;

/**
 * Class LocationSearchField
 * @package Vehica\Search\Field
 */
class LocationSearchField extends SearchField
{
    const TYPE = 'location';
    const PLACEHOLDER = 'location_placeholder';
    const SHOW_MY_LOCATION_BUTTON = 'location_show_my_location_button';
    const SHOW_RADIUS = 'location_show_radius';
    const RADIUS_PLACEHOLDER = 'location_radius_placeholder';
    const RADIUS_UNITS = 'location_radius_units';
    const RADIUS_VALUES = 'location_radius_values';
    const DEFAULT_RADIUS = 'location_default_radius';
    const ASK_FOR_LOCATION = 'location_ask_for_location';

    /**
     * @var LocationField
     */
    protected $searchable;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->searchable->getKey();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->searchable->getId();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->searchable->getName();
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        if (!empty($this->config[self::PLACEHOLDER])) {
            return $this->config[self::PLACEHOLDER];
        }

        return $this->getName();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * @return array
     */
    public function getJsonData()
    {
        return [
            'id' => $this->searchable->getId(),
            'rewrite' => $this->searchable->getRewrite(),
            'searchTypes' => [$this->searchable->getSearchTypes()],
            'countries' => $this->searchable->getCountryRestrictions(),
        ];
    }

    /**
     * @return bool
     */
    public function showRadiusControl()
    {
        return !empty($this->config[self::SHOW_RADIUS]);
    }

    /**
     * @return string
     */
    public function getRadiusUnit()
    {
        if (empty($this->config[self::RADIUS_UNITS])) {
            return 'miles';
        }

        return $this->config[self::RADIUS_UNITS];
    }

    /**
     * @return int[]
     */
    private function getRadiusValues()
    {
        if (empty($this->config[self::RADIUS_VALUES])) {
            return $this->getDefaultRadiusValues();
        }

        $values = explode(',', $this->config[self::RADIUS_VALUES]);
        if (empty($values) || !is_array($values)) {
            return $this->getDefaultRadiusValues();
        }

        return Collection::make($values)->map(static function ($value) {
            return (int)$value;
        })->all();
    }

    /**
     * @return array
     */
    public function getRadiusOptions()
    {
        $unit = $this->getRadiusUnit();

        return Collection::make($this->getRadiusValues())->map(function ($value) use ($unit) {
            return [
                'label' => '< ' . $value . ' ' . $unit,
                'value' => $this->calcRadiusValue($value, $unit),
            ];
        })->all();
    }

    /**
     * @return array|false
     */
    public function getDefaultRadiusOption()
    {
        $unit = $this->getRadiusUnit();
        $value = $this->getDefaultRadiusValue();

        if (empty($value)) {
            return false;
        }

        return [
            'label' => '< ' . $value . ' ' . $unit,
            'value' => $this->calcRadiusValue($value, $unit),
        ];
    }

    /**
     * @return int|string
     */
    private function getDefaultRadiusValue()
    {
        if (!isset($this->config[self::DEFAULT_RADIUS])) {
            return '';
        }

        $value = $this->config[self::DEFAULT_RADIUS];
        if ($value === '') {
            return '';
        }

        return (int)$value;
    }

    /**
     * @param int $value
     * @param string $unit
     * @return int
     */
    private function calcRadiusValue($value, $unit)
    {
        if ($unit === 'km') {
            return $value * 1000;
        }

        return $value * 1609;
    }

    /**
     * @return int[]
     */
    private function getDefaultRadiusValues()
    {
        return [10, 20, 30, 40, 50, 75, 100, 150, 200, 250, 500];
    }

    /**
     * @return bool
     */
    public function showMyLocationButton()
    {
        return !empty($this->config[self::SHOW_MY_LOCATION_BUTTON]);
    }

    /**
     * @param int $show
     */
    public function setShowMyLocationButton($show)
    {
        $this->config[self::SHOW_MY_LOCATION_BUTTON] = $show;
    }

    /**
     * @return string
     */
    public function getRadiusPlaceholder()
    {
        if (empty($this->config[self::RADIUS_PLACEHOLDER])) {
            return vehicaApp('distance_string');
        }

        return $this->config[self::RADIUS_PLACEHOLDER];
    }

    /**
     * @return bool
     */
    public function askForLocation()
    {
        return !empty($this->config[self::ASK_FOR_LOCATION]);
    }

}
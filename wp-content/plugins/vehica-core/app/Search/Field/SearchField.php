<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Search\Field;

if (!defined('ABSPATH')) {
    exit;
}

use JsonSerializable;
use Vehica\Search\Searchable;

/**
 * Class SearchField
 * @package Vehica\Search\Field
 */
abstract class SearchField implements JsonSerializable
{
    const FIELD = 'search_field';
    const CONTROL = 'control';

    /**
     * @var Searchable
     */
    protected $searchable;

    /**
     * @var array
     */
    protected $config;

    /**
     * SearchField constructor.
     * @param Searchable $searchable
     * @param array $config
     */
    public function __construct(array $config, $searchable = null)
    {
        $this->searchable = $searchable;
        $this->config = $config;
    }

    /**
     * @return string
     */
    abstract public function getKey();

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return string
     */
    public function getClass()
    {
        if (!isset($this->config['_id'])) {
            return '';
        }

        return 'elementor-repeater-item-' . $this->config['_id'];
    }

    /**
     * @return bool
     */
    public function isTaxonomy()
    {
        return $this instanceof TaxonomySearchField;
    }

    /**
     * @return bool
     */
    public function isNumberField()
    {
        return $this instanceof NumberSearchField && !$this instanceof PriceSearchField;
    }

    /**
     * @return bool
     */
    public function isPriceField()
    {
        return $this instanceof PriceSearchField;
    }

    /**
     * @return bool
     */
    public function isTextField()
    {
        return $this instanceof TextSearchField;
    }

    /**
     * @return bool
     */
    public function isDateField()
    {
        return $this instanceof DateSearchField;
    }

    /**
     * @return bool
     */
    public function isLocationField()
    {
        return $this instanceof LocationSearchField;
    }

    /**
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
                'name' => $this->getName(),
                'key' => $this->getKey(),
            ] + $this->getJsonData();
    }

    /**
     * @return array
     */
    protected function getJsonData()
    {
        return [];
    }

}
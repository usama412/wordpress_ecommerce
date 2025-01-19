<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Term\Relation;

if (!defined('ABSPATH')) {
    exit;
}

use JsonSerializable;
use Vehica\Model\Term\Term;

/**
 * Class Relation
 * @package Vehica\Model\Term\Relation
 */
abstract class Relation implements JsonSerializable
{
    const DEFAULT_VALUE = 1;
    const NOT_CHECKED = 0;

    /**
     * @var Term
     */
    protected $term;

    /**
     * Relation constructor.
     * @param Term $term
     */
    public function __construct(Term $term)
    {
        $this->term = $term;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return 'vehica_' . $this->term->getId() . '_' . $this->getParamKey();
    }

    /**
     * @param int $value
     */
    public function setValue($value)
    {
        $value = (int)$value;
        $this->term->setMeta($this->getKey(), $value);
    }

    /**
     * @return bool
     */
    public function isChecked()
    {
        $value = $this->term->getMeta($this->getKey());

        if ($value === null || $value === '') {
            return true;
        }

        return (int)$value;
    }

    /**
     * @return Term
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'key' => $this->getKey(),
            'value' => $this->isChecked(),
            'termId' => $this->term->getId(),
            'paramKey' => $this->getParamKey()
        ];
    }

    /**
     * @return string
     */
    abstract public function getParamKey();

    /**
     * @return string
     */
    abstract public function getName();

}
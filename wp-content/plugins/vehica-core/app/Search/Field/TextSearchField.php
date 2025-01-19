<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Search\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Model\Post\Field\TextField;
use Vehica\Search\SearchControl;

/**
 * Class TextSearchField
 * @package Vehica\Search\Field
 */
class TextSearchField extends SearchField
{
    const TYPE = 'text';
    const PLACEHOLDER = 'text_placeholder';

    /**
     * @var TextField
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
     * @return string
     */
    public function getName()
    {
        return $this->searchable->getName();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
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
     * @return bool
     */
    public function hasPlaceholder()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getControl()
    {
        return SearchControl::TYPE_INPUT;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->searchable->getId();
    }

    /**
     * @return array
     */
    public function getJsonData()
    {
        return [
            'id' => $this->searchable->getId(),
            'rewrite' => $this->searchable->getRewrite(),
        ];
    }

}
<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Term\Relation;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Model\Post\Field\Field;
use Vehica\Model\Term\Term;

/**
 * Class FieldRelation
 * @package Vehica\Model\Term\Relation
 */
class FieldRelation extends Relation
{
    /**
     * @var Field
     */
    private $field;

    /**
     * FieldRelation constructor.
     * @param Term $term
     * @param Field $field
     * @noinspection InterfacesAsConstructorDependenciesInspection
     */
    public function __construct(Term $term, Field $field)
    {
        parent::__construct($term);

        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getParamKey()
    {
        return Field::POST_TYPE . '_' . $this->field->getId();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->field->getName();
    }

    /**
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return bool
     */
    public function isFieldRequired()
    {
        return $this->field->isRequired();
    }

    /**
     * @return bool
     */
    public function isChecked()
    {
        if ($this->field->isRequired()) {
            return true;
        }

        return parent::isChecked();
    }

}
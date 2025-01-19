<?php /** @noinspection ContractViolationInspection */


namespace Vehica\Panel\PanelField;


use Vehica\Core\Collection;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;

/**
 * Class TaxonomyPanelField
 *
 * @package Vehica\Panel\PanelField
 */
class TaxonomyPanelField extends CustomFieldPanelField
{
    /**
     * @var Taxonomy
     */
    protected $field;

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'taxonomy';
    }

    /**
     * @return Collection
     */
    public function getTerms()
    {
        $terms = $this->field->getPanelTerms();
        if ($terms->isEmpty()) {
            if ($this->field->hasParentTaxonomy()) {
                return Collection::make();
            }

            $terms = $this->field->getTerms();
        }

        return apply_filters('vehica/panel/field/' . $this->field->getId() . '/terms', $terms)
            ->map(static function ($term) {
                $data = [
                    'id' => $term->getId(),
                    'key' => $term->getKey(),
                    'name' => $term->getName(),
                    'parentTerm' => false,
                    'taxonomyKey' => $term->getTaxonomyKey(),
                ];

                if ($term->hasParentTerm()) {
                    $data['parentTerm'] = $term->getParentTermIds();
                }

                $taxonomy = $term->getTaxonomy();
                if ($taxonomy && $taxonomy->isFieldsDependencyEnabled()) {
                    $data['relations'] = $term->getRelations();
                }

                return $data;
            });
    }

    /**
     * @param Car $car
     * @param array $data
     */
    public function update(Car $car, $data = [])
    {
        wp_set_post_terms($car->getId(), $this->getValue($data), $this->getKey());
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function getValue($data)
    {
        $attributeData = $this->getAttributeData($data);

        if (!$attributeData || !isset($attributeData['value']) || !is_array($attributeData['value'])) {
            return [];
        }

        return Collection::make($attributeData['value'])
            ->map(static function ($term) {
                if (isset($term['id']) && !empty($term['id'])) {
                    $id = (int)$term['id'];
                    if ($id > 0) {
                        return $id;
                    }
                }

                return $term['name'];
            })
            ->all();
    }

    /**
     * @return bool
     */
    public function isSingleValue()
    {
        return !$this->field->allowMultiple();
    }

    public function loadTemplate()
    {
        if ($this->isSingleValue()) {
            parent::loadTemplate();

            return;
        }

        global $vehicaPanelField;
        $vehicaPanelField = $this;
        get_template_part('templates/general/panel/field/' . $this->getTemplate() . '_multi');
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function validate(array $data)
    {
        $attributeData = $this->getAttributeData($data);

        return !(
            !$attributeData
            || empty($attributeData['value'])
            || !is_array($attributeData['value'])
        );
    }

}
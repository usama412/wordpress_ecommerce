<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Search\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Term;
use Vehica\Search\SearchControl;
use WP_Term;

/**
 * Class TaxonomySearchField
 * @package Vehica\Search\Field
 */
class TaxonomySearchField extends SearchField
{
    const TYPE = 'taxonomy';
    const CONTROL = 'taxonomy_control';
    const SHOW_ALL_TERMS = 'terms_show_all';
    const TERMS_INIT_LIMIT = 'terms_init_limit';
    const TERMS_INIT_LIMIT_ALL = 0;
    const TERMS_ORDER = 'terms_order';
    const TERMS_ORDER_NAME = 'terms_order_name';
    const TERMS_ORDER_COUNT = 'terms_order_count';
    const TERMS_ORDER_DISABLE = 'terms_order_disable';
    const SHOW_TERMS_COUNT = 'show_term_count';
    const PLACEHOLDER = 'placeholder';
    const WHEN_TERM_HAS_NO_CARS = 'when_term_has_no_cars';
    const DISABLE_TERM = 'disable';
    const HIDE_TERM = 'hide';
    const SEARCHABLE = 'searchable';

    /**
     * @var Taxonomy
     */
    protected $searchable;

    /**
     * @var Collection|bool
     */
    protected $terms = false;

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
    public function getTermsOrder()
    {
        if (!isset($this->config[self::TERMS_ORDER])) {
            return self::TERMS_ORDER_NAME;
        }
        return $this->config[self::TERMS_ORDER];
    }

    private function prepareTerms()
    {
        $terms = $this->getSelectedTerms();
        if ($terms->isEmpty()) {
            $terms = $this->getAllTerms();
        }

        if ($this->disableSort()) {
            $this->terms = $terms->map(static function ($term) {
                return new Term($term);
            });
            return;
        }

        $this->terms = $terms->sort(static function ($aTerm, $bTerm) {
            if ($aTerm->count > $bTerm->count) {
                return -1;
            }
            return $bTerm->count > $aTerm->count ? 1 : 0;
        })->map(static function ($wpTerm) {
            return new Term($wpTerm);
        });
    }

    /**
     * @return Collection
     */
    public function getTerms()
    {
        if ($this->terms === false) {
            $this->prepareTerms();
        }

        return $this->terms;
    }

    /**
     * @return Term[]
     */
    public function getTermsList()
    {
        return Collection::make(apply_filters('vehica/search/field/' . $this->getId() . '/terms', $this->getTerms()->all()))
            ->filter(static function ($term) {
                return $term !== false;
            })
            ->all();
    }

    /**
     * @return bool
     */
    public function disableSort()
    {
        return apply_filters(
            'vehica/search/field/' . $this->getId() . '/disableSort',
            $this->getTermsOrder() === self::TERMS_ORDER_DISABLE
        );
    }

    /**
     * @return int
     */
    public function getVisibleTermsNumber()
    {
        $count = $this->getTermsCount();
        if ($this->isRadioControl()) {
            $count = ++$count;
        }

        if (!$this->limitNumberOfVisibleTermsAtStart()) {
            return $count;
        }

        $numberOfVisibleAtStart = $this->getNumberOfVisibleTermsAtStart();
        if ($count < $numberOfVisibleAtStart) {
            return $count;
        }

        return $this->isRadioControl() ? ++$numberOfVisibleAtStart : $numberOfVisibleAtStart;
    }

    /**
     * @return Collection
     */
    private function getAllTerms()
    {
        if ($this->searchable->hasParentTaxonomy()) {
            return Collection::make();
        }

        $terms = get_terms(apply_filters('vehica/search/allTerms/args', [
            'taxonomy' => $this->searchable->getKey(),
            'hide_empty' => false,
        ]));

        if (!is_array($terms)) {
            return Collection::make();
        }

        return Collection::make($terms)->filter(static function ($term) {
            return !in_array($term->term_id, vehicaApp('terms_excluded_from_search'), true);
        });
    }

    /**
     * @return Collection
     */
    private function getSelectedTerms()
    {
        $key = 'terms_in_' . $this->searchable->getKey();
        $key2 = 'terms_in_ids_' . $this->searchable->getKey();

        if (!empty($this->config[$key]) && is_array($this->config[$key])) {
            $termIds = $this->config[$key];
        } elseif (!empty($this->config[$key2])) {
            $termIds = Collection::make(explode(',', $this->config[$key2]))->map(static function ($id) {
                return trim($id);
            })->all();
        } else {
            return Collection::make();
        }

        $terms = get_terms([
            'taxonomy' => $this->searchable->getKey(),
            'hide_empty' => false,
            'include' => $termIds
        ]);

        if (!is_array($terms)) {
            return Collection::make();
        }

        $terms = Collection::make($terms);

        return Collection::make($termIds)->map(static function ($termId) use ($terms) {
            return $terms->find(static function ($term) use ($termId) {
                /* @var WP_Term $term */
                return $term->term_id === (int)$termId;
            });
        })->filter(static function ($term) {
            return $term !== false;
        });
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * @return bool
     */
    public function searchable()
    {
        return !empty($this->config[self::SEARCHABLE]);
    }

    /**
     * @return array
     */
    public static function getControlsList()
    {
        return [
            SearchControl::TYPE_SELECT => esc_html__('Select', 'vehica-core'),
            SearchControl::TYPE_MULTI_SELECT => esc_html__('Select Multiple', 'vehica-core'),
            SearchControl::TYPE_CHECKBOX => esc_html__('Popup Checkbox', 'vehica-core'),
        ];
    }

    /**
     * @return string
     */
    public static function getDefaultControl()
    {
        return SearchControl::TYPE_SELECT;
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
    protected function getJsonData()
    {
        return [
            'id' => $this->searchable->getId(),
            'parent' => $this->searchable->getParentTaxonomyIds(),
            'rewrite' => $this->searchable->getRewrite(),
        ];
    }

    /**
     * @param string $default
     * @return string
     */
    public function getPlaceholder($default = '')
    {
        if (empty($this->config[self::PLACEHOLDER])) {
            if (!empty($default)) {
                return $default;
            }

            return $this->getName();
        }

        return $this->config[self::PLACEHOLDER];
    }

    /**
     * @return bool
     */
    public function isClearSelectionButtonEnabled()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function limitNumberOfVisibleTermsAtStart()
    {
        if (!$this->isCheckboxControl() && !$this->isRadioControl()) {
            return false;
        }

        if (!isset($this->config[self::SHOW_ALL_TERMS])) {
            return false;
        }

        return empty($this->config[self::SHOW_ALL_TERMS])
            && $this->getTermsCount() >= $this->getNumberOfVisibleTermsAtStart();
    }

    /**
     * @return int
     */
    public function getNumberOfVisibleTermsAtStart()
    {
        if (!isset($this->config[self::TERMS_INIT_LIMIT])) {
            return self::TERMS_INIT_LIMIT_ALL;
        }

        return (int)$this->config[self::TERMS_INIT_LIMIT];
    }

    /**
     * @return string
     */
    public function getShowMoreTermsText()
    {
        return vehicaApp('more_string');
    }

    /**
     * @return bool
     */
    public function showMoreTermsText()
    {
        if (!$this->limitNumberOfVisibleTermsAtStart()) {
            return false;
        }

        return $this->getTermsCount() > $this->getNumberOfVisibleTermsAtStart();
    }

    /**
     * @return int
     */
    protected function getTermsCount()
    {
        if ($this->whenTermEmpty() === self::DISABLE_TERM) {
            return $this->getTerms()->count();
        }

        return $this->getTerms()->filter(static function ($term) {
            /* @var Term $term */
            return $term->getCount() > 0;
        })->count();
    }

    /**
     * @return int
     */
    public function getMoreTermsNumber()
    {
        return $this->getTermsCount() - $this->getNumberOfVisibleTermsAtStart();
    }

    /**
     * @return string
     */
    public function getShowLessTermsText()
    {
        return vehicaApp('less_string');
    }

    /**
     * @return bool
     */
    public function showTermCount()
    {
        if (!isset($this->config[self::SHOW_TERMS_COUNT])) {
            return true;
        }

        return !empty($this->config[self::SHOW_TERMS_COUNT]);
    }

    /**
     * @return string
     */
    public function whenTermEmpty()
    {
        if (!isset($this->config[self::WHEN_TERM_HAS_NO_CARS])) {
            return self::DISABLE_TERM;
        }

        return $this->config[self::WHEN_TERM_HAS_NO_CARS];
    }

    /**
     * @return bool
     */
    public function sortByCount()
    {
        return $this->getTermsOrder() === self::TERMS_ORDER_COUNT;
    }


    /**
     * @return bool
     */
    public function isCheckboxControl()
    {
        return isset($this->config[self::CONTROL]) && $this->config[self::CONTROL] === SearchControl::TYPE_CHECKBOX;
    }

    /**
     * @return bool
     */
    public function isSelectControl()
    {
        return isset($this->config[self::CONTROL]) && $this->config[self::CONTROL] === SearchControl::TYPE_SELECT;
    }

    /**
     * @return bool
     */
    public function isMultiSelectControl()
    {
        return isset($this->config[self::CONTROL]) && $this->config[self::CONTROL] === SearchControl::TYPE_MULTI_SELECT;
    }

    /**
     * @return bool
     */
    public function isRadioControl()
    {
        return isset($this->config[self::CONTROL]) && $this->config[self::CONTROL] === SearchControl::TYPE_RADIO;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return $this->searchable->hasParentTaxonomy();
    }

}
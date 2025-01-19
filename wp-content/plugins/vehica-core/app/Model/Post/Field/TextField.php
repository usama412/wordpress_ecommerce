<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Attribute\AttributeValue;
use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Attribute\SimpleTextValue;
use Vehica\Core\Collection;
use Vehica\Core\Field\Attribute;
use Vehica\Core\Model\Field\FieldsUser;
use Vehica\Core\Post\PostStatus;
use Vehica\Core\Rewrite\Rewritable;
use Vehica\Core\Rewrite\Rewrite;
use Vehica\Model\Post\Car;
use Vehica\Search\Field\SearchField;
use Vehica\Search\Field\TextSearchField;
use Vehica\Search\Searchable;
use Vehica\Search\SearchFilter;
use Vehica\Search\UrlModifier;
use WP_Query;

/**
 * Class TextCustomField
 */
class TextField extends Field implements SimpleTextAttribute, SearchFilter, Searchable, UrlModifier, RewritableField
{
    use Rewritable;

    const KEY = 'text';
    const COMPARE_LOGIC = 'vehica_compare_logic';
    const COMPARE_LOGIC_LIKE = 'vehica_compare_logic_like';
    const COMPARE_LOGIC_EQUAL = 'vehica_compare_logic_equal';

    /**
     * @return array
     */
    public function getAdditionalSettings()
    {
        return [
            Rewrite::REWRITE,
            self::COMPARE_LOGIC,
            self::PANEL_PLACEHOLDER,
        ];
    }

    /**
     * @param FieldsUser $fieldsUser
     * @param string $value
     */
    public function save(FieldsUser $fieldsUser, $value)
    {
        $fieldsUser->setMeta($this->getKey(), $value);
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return string
     */
    public function getValue(FieldsUser $fieldsUser)
    {
        if (!$this->isVisible()) {
            return '';
        }

        $value = $fieldsUser->getMeta($this->getKey());

        if (empty($value)) {
            return '';
        }

        return $value;
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return Attribute
     */
    public function getAttribute(FieldsUser $fieldsUser)
    {
        return new Attribute($this, $fieldsUser);
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return Collection
     */
    public function getSimpleTextValues(FieldsUser $fieldsUser)
    {
        $value = $this->getValue($fieldsUser);

        if (empty($value)) {
            return Collection::make();
        }

        $displayValue = $this->getDisplayValue($value);
        $simpleTextValue = new SimpleTextValue($displayValue);

        return Collection::make([$simpleTextValue]);
    }

    /**
     * @param array $config
     * @return SearchField|TextSearchField
     */
    public function getSearchField($config)
    {
        return new TextSearchField($config, $this);
    }

    /**
     * @return array
     */
    public function getSearchParamsFromUrl()
    {
        $rewrite = $this->getRewrite();

        if (empty($_GET[$rewrite])) {
            return [];
        }

        return [$rewrite => $_GET[$rewrite]];
    }

    /**
     * @param array $parameters
     * @return string|false
     */
    public function getArchiveUrlPartial($parameters)
    {
        $rewrite = $this->getRewrite();

        if (empty($parameters[$rewrite])) {
            return false;
        }

        return $rewrite . '=' . $parameters[$rewrite];
    }

    /**
     * @param array $parameters
     * @return array|false
     */
    public function getInitialSearchParams($parameters)
    {
        $rewrite = $this->getRewrite();
        if (!isset($parameters[$rewrite])) {
            return false;
        }

        return [
            [
                'id' => $this->getId(),
                'key' => $this->getKey(),
                'rewrite' => $rewrite,
                'name' => $this->getName(),
                'values' => [
                    [
                        'key' => $this->getKey(),
                        'name' => $this->getName() . ': ' . $parameters[$rewrite],
                        'value' => $parameters[$rewrite]
                    ]
                ]
            ]
        ];
    }

    /**
     * @param array $parameters
     * @return array|false
     */
    public function getSearchedCarsIds($parameters)
    {
        $rewrite = $this->getRewrite();

        if (empty($parameters[$rewrite])) {
            return false;
        }

        $query = new WP_Query([
            'post_type' => Car::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'posts_per_page' => '-1',
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => $this->getKey(),
                    'compare' => $this->isCompareLogicLike() ? 'LIKE' : '=',
                    'value' => $parameters[$rewrite]
                ]
            ]
        ]);

        return $query->posts;
    }

    /**
     * @param string $compareLogic
     */
    public function setCompareLogic($compareLogic)
    {
        if (empty($compareLogic)) {
            $compareLogic = self::COMPARE_LOGIC_LIKE;
        }
        $this->setMeta(self::COMPARE_LOGIC, $compareLogic);
    }

    /**
     * @return string
     */
    public function getCompareLogic()
    {
        $compareLogic = $this->getMeta(self::COMPARE_LOGIC);

        if (
            empty($compareLogic) ||
            ($compareLogic !== self::COMPARE_LOGIC_LIKE && $compareLogic !== self::COMPARE_LOGIC_EQUAL)
        ) {
            return self::COMPARE_LOGIC_LIKE;
        }

        return $compareLogic;
    }

    /**
     * @return bool
     */
    public function isCompareLogicLike()
    {
        return $this->getCompareLogic() === self::COMPARE_LOGIC_LIKE;
    }

    /**
     * @return bool
     */
    public function isCompareLogicEqual()
    {
        return $this->getCompareLogic() === self::COMPARE_LOGIC_EQUAL;
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return Collection
     */
    public function getAttributeValues(FieldsUser $fieldsUser)
    {
        $value = $this->getValue($fieldsUser);

        if (empty($value)) {
            return Collection::make();
        }

        return Collection::make([AttributeValue::make($value, $value)]);
    }

    /**
     * @return array
     */
    protected function getJsonData()
    {
        return [
            'rewrite' => $this->getRewrite()
        ];
    }

}
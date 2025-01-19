<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post\Field;

if (!defined('ABSPATH')) {
    exit;
}

use DateTime;
use Exception;
use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Attribute\SimpleTextValue;
use Vehica\Core\Collection;
use Vehica\Core\Field\Attribute;
use Vehica\Core\Model\Field\FieldsUser;
use Vehica\Core\Post\PostStatus;
use Vehica\Core\Rewrite\Rewritable;
use Vehica\Core\Rewrite\Rewrite;
use Vehica\Model\Post\Car;
use Vehica\Search\Field\DateSearchField;
use Vehica\Search\Field\SearchField;
use Vehica\Search\Searchable;
use Vehica\Search\SearchFilter;
use Vehica\Search\UrlModifier;
use WP_Query;

/**
 * Class DateTimeField
 * @package Vehica\CustomField\Fields
 */
class DateTimeField extends Field implements SimpleTextAttribute, RewritableField, Searchable, SearchFilter, UrlModifier
{
    use Rewritable;

    const KEY = 'date_time';
    const IS_RANGE = 'vehica_is_range';
    const VALUE_TYPE = 'vehica_value_type';
    const TYPE_DATE = 'date';
    const TYPE_TIME = 'time';
    const TYPE_DATE_TIME = 'datetime';
    const TYPE_YEAR = 'year';
    const TYPE_MONTH = 'month';

    /**
     * @return array
     */
    protected function getAdditionalSettings()
    {
        return [
            self::VALUE_TYPE,
            self::IS_RANGE,
            Rewrite::REWRITE,
        ];
    }

    /**
     * @return int
     */
    public function getWeekStart()
    {
        return (int)get_option('start_of_week');
    }

    /**
     * @param string $valueType
     */
    public function setValueType($valueType)
    {
        if (empty($valueType)) {
            $valueType = self::TYPE_DATE_TIME;
        }
        $this->setMeta(self::VALUE_TYPE, $valueType);
    }

    /**
     * @return string
     */
    public function getValueType()
    {
        $valueType = $this->getMeta(self::VALUE_TYPE);

        if (empty($valueType)) {
            return self::TYPE_DATE;
        }

        return $valueType;
    }

    /**
     * @param int $isRange
     */
    public function setIsRange($isRange)
    {
        $isRange = (int)$isRange;
        $this->setMeta(self::IS_RANGE, $isRange);
    }

    /**
     * @return bool
     */
    public function isRange()
    {
        $isRange = $this->getMeta(self::IS_RANGE);
        return !empty($isRange);
    }

    /**
     * @return array
     */
    protected function getJsonData()
    {
        return [
            'type' => $this->getType(),
            'isRange' => $this->isRange(),
            'valueType' => $this->getValueType(),
            'weekStart' => $this->getWeekStart(),
            'rewrite' => $this->getRewrite(),
            'placeholder' => $this->getPlaceholder(),
        ];
    }

    /**
     * @param FieldsUser $fieldsUser
     * @param string $value
     */
    public function save(FieldsUser $fieldsUser, $value)
    {
        if (!$this->isRange()) {
            $fieldsUser->setMeta($this->getKey(), $this->convertToSave($value));
            return;
        }

        if (isset($value[0], $value[1])) {
            $from = $this->convertToSave($value[0]);
            $to = $this->convertToSave($value[1]);
        } else {
            $from = '';
            $to = '';
        }

        $fieldsUser->setMeta($this->getKey() . '_from', $from);
        $fieldsUser->setMeta($this->getKey() . '_to', $to);
    }

    /**
     * @param string $value
     * @return string
     */
    private function convertToSave($value)
    {
        if (empty($value)) {
            return '';
        }

        try {
            $dateTime = new DateTime($value);
        } catch (Exception $e) {
            $dateTime = DateTime::createFromFormat('D M d Y H:i:s e+', $value);
        }

        return $dateTime !== false ? $dateTime->format($this->getFormat()) : '';
    }

    /**
     * @return string
     */
    public function getDisplayFormat()
    {
        $type = $this->getValueType();

        if ($type === self::TYPE_DATE) {
            return get_option('date_format');
        }

        if ($type === self::TYPE_TIME) {
            return get_option('time_format');
        }

        return get_option('date_format') . ' ' . get_option('time_format');
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        $type = $this->getValueType();
        if ($type === self::TYPE_DATE_TIME) {
            return 'Y-m-d H:i:s';
        }

        if ($type === self::TYPE_DATE) {
            return 'Y-m-d';
        }

        if ($type === self::TYPE_MONTH) {
            return 'm';
        }

        if ($type === self::TYPE_YEAR) {
            return 'Y';
        }

        if ($type === self::TYPE_TIME) {
            return 'H:i:s';
        }

        return 'Y-m-d H:i:s';
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
     * @return string|array
     */
    public function getValue(FieldsUser $fieldsUser)
    {
        if (!$this->isVisible()) {
            return $this->isRange() ? [] : '';
        }

        if ($this->isRange()) {
            return [
                $fieldsUser->getMeta($this->getKey() . '_from'),
                $fieldsUser->getMeta($this->getKey() . '_to')
            ];
        }

        return $fieldsUser->getMeta($this->getKey());
    }

    /**
     * @return array
     */
    public static function getTranslation()
    {
        return [
            'days' => [
                esc_html__('Sun', 'vehica-core'),
                esc_html__('Mon', 'vehica-core'),
                esc_html__('Tue', 'vehica-core'),
                esc_html__('Wed', 'vehica-core'),
                esc_html__('Thu', 'vehica-core'),
                esc_html__('Fri', 'vehica-core'),
                esc_html__('Sat', 'vehica-core'),
            ],
            'months' => [
                esc_html__('Jan', 'vehica-core'),
                esc_html__('Feb', 'vehica-core'),
                esc_html__('Mar', 'vehica-core'),
                esc_html__('Apr', 'vehica-core'),
                esc_html__('May', 'vehica-core'),
                esc_html__('Jun', 'vehica-core'),
                esc_html__('Jul', 'vehica-core'),
                esc_html__('Aug', 'vehica-core'),
                esc_html__('Sep', 'vehica-core'),
                esc_html__('Oct', 'vehica-core'),
                esc_html__('Nov', 'vehica-core'),
                esc_html__('Dec', 'vehica-core'),
            ],
            'pickers' => [
                esc_html__('next 7 days', 'vehica-core'),
                esc_html__('next 30 days', 'vehica-core'),
                esc_html__('previous 7 days', 'vehica-core'),
                esc_html__('previous 30 days', 'vehica-core'),
            ],
            'placeholder' => [
                'date' => esc_html__('Select Date', 'vehica-core'),
                'dateRange' => esc_html__('Select Date Range', 'vehica-core')
            ]
        ];
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
        if (empty($displayValue)) {
            return Collection::make();
        }

        $simpleTextValue = new SimpleTextValue($this->getDisplayValue($value));
        return Collection::make([$simpleTextValue]);
    }

    public function getDisplayValue($value)
    {
        if (!is_array($value)) {
            if (empty($value)) {
                return '';
            }

            try {
                return $this->getLocalizedValue($value);
            } catch (Exception $e) {
                return '';
            }
        }

        if (count($value) !== 2) {
            return '';
        }

        try {
            return $this->getLocalizedValue($value[0]) . ' - ' . $this->getLocalizedValue($value[1]);
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * @param string $value
     * @return string
     */
    private function getLocalizedValue($value)
    {
        try {
            $localizedValue = wp_date($this->getDisplayFormat(), (new DateTime($value))->getTimestamp());
        } catch (Exception $e) {
            $localizedValue = '';
        }

        if (!$localizedValue) {
            return '';
        }

        return $localizedValue;
    }

    /**
     * @return string
     */
    public static function getDateFormat()
    {
        $search = [
            'Y',
            'F',
            'j',
        ];

        $replace = [
            'yyyy',
            'mmmm',
            'dd',
        ];

        return str_replace($search, $replace, get_option('date_format'));
    }

    /**
     * @return string
     */
    public static function getTimeFormat()
    {
        $search = [
            'h',
            'g',
            'H',
            'G',
        ];

        $replace = [
            'hh',
            'h',
            'HH',
            'H',
        ];

        return str_replace($search, $replace, get_option('time_format'));
    }

    /**
     * @return string
     */
    public static function getStartOfWeek()
    {
        return get_option('start_of_week');
    }

    /**
     * @return string[]
     */
    public static function getMonths()
    {
        return [
            vehicaApp('january_string'),
            vehicaApp('february_string'),
            vehicaApp('march_string'),
            vehicaApp('april_string'),
            vehicaApp('may_string'),
            vehicaApp('june_string'),
            vehicaApp('july_string'),
            vehicaApp('august_string'),
            vehicaApp('september_string'),
            vehicaApp('october_string'),
            vehicaApp('november_string'),
            vehicaApp('december_string'),
        ];
    }

    /**
     * @return string[]
     */
    public static function getShortMonths()
    {
        return [
            vehicaApp('jan_string'),
            vehicaApp('feb_string'),
            vehicaApp('mar_string'),
            vehicaApp('apr_string'),
            vehicaApp('may_string'),
            vehicaApp('jun_string'),
            vehicaApp('jul_string'),
            vehicaApp('aug_string'),
            vehicaApp('sep_string'),
            vehicaApp('oct_string'),
            vehicaApp('nov_string'),
            vehicaApp('dec_string')
        ];
    }

    /**
     * @return string[]
     */
    public static function getDays()
    {
        return [
            vehicaApp('sunday_string'),
            vehicaApp('monday_string'),
            vehicaApp('tuesday_string'),
            vehicaApp('wednesday_string'),
            vehicaApp('thursday_string'),
            vehicaApp('friday_string'),
            vehicaApp('saturday_string'),
        ];
    }

    /**
     * @return string[]
     */
    public static function getShortDays()
    {
        return [
            vehicaApp('sun_string'),
            vehicaApp('mon_string'),
            vehicaApp('tue_string'),
            vehicaApp('wed_string'),
            vehicaApp('thu_string'),
            vehicaApp('fri_string'),
            vehicaApp('sat_string')
        ];
    }

    /**
     * @return string
     */
    public function getPrettyType()
    {
        return esc_html__('date', 'vehica-core');
    }

    /**
     * @param array $config
     * @return DateSearchField|SearchField
     */
    public function getSearchField($config)
    {
        return new DateSearchField($config, $this);
    }

    /**
     * @return array
     */
    public static function getStrings()
    {
        return [
            'days' => self::getDays(),
            'shortDays' => self::getShortDays(),
            'months' => self::getMonths(),
            'shortMonths' => self::getShortMonths(),
            'clear' => vehicaApp('clear_string'),
            'close' => vehicaApp('close_string'),
            'today' => vehicaApp('today_string'),
        ];
    }

    /**
     * @param array $parameters
     * @return array|false
     */
    public function getSearchedCarsIds($parameters)
    {
        $rewrite = $this->getRewrite();

        $rewriteFrom = $rewrite . vehicaApp('from_rewrite');
        $rewriteTo = $rewrite . vehicaApp('to_rewrite');

        if (empty($parameters[$rewriteFrom]) && empty($parameters[$rewriteTo])) {
            return false;
        }

        $metaQuery = [
            'relation' => 'AND',
        ];

        if (!empty($parameters[$rewriteFrom])) {
            $metaQuery[] = [
                'key' => $this->isRange() ? $this->getKey() . '_from' : $this->getKey(),
                'value' => $parameters[$rewriteFrom],
                'compare' => '>='
            ];

            if ($this->isRange()) {
                $metaQuery[] = [
                    'key' => $this->getKey() . '_from',
                    'compare' => 'EXISTS',
                ];
                $metaQuery[] = [
                    'key' => $this->getKey() . '_from',
                    'value' => '',
                    'compare' => '!=',
                ];
            }
        }

        if (!empty($parameters[$rewriteTo])) {
            $metaQuery[] = [
                'key' => $this->isRange() ? $this->getKey() . '_to' : $this->getKey(),
                'value' => $parameters[$rewriteTo],
                'compare' => '<='
            ];

            if ($this->isRange()) {
                $metaQuery[] = [
                    'key' => $this->getKey() . '_to',
                    'compare' => 'EXISTS',
                ];
                $metaQuery[] = [
                    'key' => $this->getKey() . '_to',
                    'value' => '',
                    'compare' => '!=',
                ];
            }
        }

        if (!empty($metaQuery) && !$this->isRange()) {
            $metaQuery[] = [
                'key' => $this->getKey(),
                'compare' => 'EXISTS',
            ];
            $metaQuery[] = [
                'key' => $this->getKey(),
                'value' => '',
                'compare' => '!=',
            ];
        }

        return (new WP_Query([
            'post_type' => Car::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'posts_per_page' => '-1',
            'fields' => 'ids',
            'meta_query' => $metaQuery
        ]))->posts;
    }

    public function getInitialSearchParams($parameters)
    {
        $params = [];

        foreach ([vehicaApp('from_rewrite'), vehicaApp('to_rewrite')] as $key) {
            $rewrite = $this->getRewrite() . $key;

            if (!empty($parameters[$rewrite])) {
                $params [] = [
                    'id' => $this->getId(),
                    'key' => $this->getKey() . $key,
                    'rewrite' => $rewrite,
                    'name' => $this->getName(),
                    'values' => [
                        [
                            'key' => $this->getKey() . $key,
                            'name' => $this->getName() . ': ' . $parameters[$rewrite],
                            'value' => $parameters[$rewrite]
                        ]
                    ]
                ];
            }
        }

        if (empty($params)) {
            return false;
        }

        return $params;
    }

    /**
     * @param array $parameters
     * @return false|string
     */
    public function getArchiveUrlPartial($parameters)
    {
        $params = [];

        foreach ([vehicaApp('from_rewrite'), vehicaApp('to_rewrite')] as $key) {
            $rewrite = $this->getRewrite() . $key;
            if (!empty($parameters[$rewrite])) {
                $params[] = $rewrite . '=' . $parameters[$rewrite];
            }
        }

        if (empty($params)) {
            return false;
        }

        /** @noinspection ImplodeMissUseInspection */
        return implode('&', $params);
    }

    /**
     * @return array
     */
    public function getSearchParamsFromUrl()
    {
        $params = [];

        foreach ([vehicaApp('from_rewrite'), vehicaApp('to_rewrite')] as $key) {
            $rewrite = $this->getRewrite() . $key;
            if (!empty($_GET[$rewrite])) {
                $params[$rewrite] = $_GET[$rewrite];
            }
        }

        return $params;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        $type = $this->getValueType();

        if ($type === self::TYPE_DATE) {
            return esc_html__('Select Date', 'vehica-core');
        }

        if ($type === self::TYPE_TIME) {
            return esc_html__('Select Time', 'vehica-core');
        }

        return esc_html__('Select Date and Time', 'vehica-core');
    }

}
<?php
/** @noinspection ClassReImplementsParentInterfaceInspection */

/** @noinspection ContractViolationInspection */

namespace Vehica\Field\Fields\Price;

if (!defined('ABSPATH')) {
    exit;
}

use JsonSerializable;
use Vehica\Model\Term\Term;

/**
 * Class Currency
 * @package Vehica\CustomField\Fields\Price
 */
class Currency extends Term implements JsonSerializable
{
    const KEY = 'vehica_currency';
    const NAME = 'vehica_name';
    const SIGN = 'vehica_sign';
    const SIGN_POSITION = 'vehica_sign_position';
    const SIGN_POSITION_BEFORE = 'before';
    const SIGN_POSITION_AFTER = 'after';
    const CURRENCIES = 'vehica_currencies';
    const DEFAULT_CURRENCY_ID = 'vehica_currency_default';
    const FORMAT = 'vehica_format';
    const FORMAT_1 = '###,###,###.##';
    const FORMAT_2 = '#########.##';
    const FORMAT_3 = '###.###.###,##';
    const FORMAT_4 = '### ### ###,## ';
    const FORMAT_5 = '###,###,###';
    const FORMAT_6 = '### ### ###.##';
    const FORMAT_7 = '###.###.###';
    const FORMAT_8 = '##,##,##,###.##';
    const FORMAT_9 = '### ### ###';
    const FORMAT_10 = '###\'###\'###';

    /**
     * @return bool
     */
    public function isInteger()
    {
        $format = $this->getFormat();

        if (in_array($format, [
            self::FORMAT_5,
            self::FORMAT_7,
            self::FORMAT_8,
            self::FORMAT_9,
            self::FORMAT_10,
        ], true)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isFloat()
    {
        return !$this->isInteger();
    }

    /**
     * @param string $price
     * @return float|int
     */
    public function parseValue($price)
    {
        if ($price === '') {
            return $price;
        }

        if ($this->isInteger()) {
            return (int)$price;
        }

        return (double)number_format((double)$price, $this->getDecimalPlaces(), '.', '');
    }

    /**
     * @param string $value
     * @return string
     */
    public function formatIndia($value)
    {
        if (strlen($value) < 4) {
            return $value;
        }

        $exRestUnits = '';
        $lastThree = substr($value, strlen($value) - 3, strlen($value));
        $restUnits = substr($value, 0, -3);
        $restUnits = (strlen($restUnits) % 2 === 1) ? '0' . $restUnits : $restUnits;
        foreach (str_split($restUnits, 2) as $i => $iValue) {
            if ($i === 0) {
                $exRestUnits .= (int)$iValue . ',';
            } else {
                $exRestUnits .= $iValue . ',';
            }
        }

        return apply_filters('vehica/price/indianFormat', $exRestUnits . $lastThree, $value);
    }

    /**
     * @return bool
     */
    public function isIndian()
    {
        return $this->getFormat() === self::FORMAT_8;
    }

    /**
     * @return int
     */
    public function getDecimalPlaces()
    {
        $format = $this->getFormat();

        if (in_array($format, [
            self::FORMAT_5,
            self::FORMAT_7,
            self::FORMAT_8,
            self::FORMAT_9,
            self::FORMAT_10,
        ], true)) {
            return apply_filters('vehica/price/decimalPlaces', 0);
        }

        return apply_filters('vehica/price/decimalPlaces', 2);
    }

    /**
     * @return string
     */
    public function getDecimalSeparator()
    {
        $format = $this->getFormat();

        if (in_array($format, [
            self::FORMAT_1,
            self::FORMAT_2,
            self::FORMAT_6,
        ], true)) {
            return apply_filters('vehica/price/decimalSeparator', '.');
        }

        if (in_array($format, [
            self::FORMAT_3,
            self::FORMAT_4,
        ], true)) {
            return apply_filters('vehica/price/decimalSeparator', ',');
        }

        return apply_filters('vehica/price/decimalSeparator', '');
    }

    /**
     * @return string
     */
    public function getThousandsSeparator()
    {
        $format = $this->getFormat();

        if (in_array($format, [
            self::FORMAT_1,
            self::FORMAT_5,
        ], true)) {
            return apply_filters('vehica/price/thousandsSeparator', ',');
        }

        if (in_array($format, [
            self::FORMAT_3,
            self::FORMAT_7,
        ], true)) {
            return apply_filters('vehica/price/thousandsSeparator', '.');
        }

        if (in_array($format, [
            self::FORMAT_4,
            self::FORMAT_6,
            self::FORMAT_9,
        ], true)) {
            return apply_filters('vehica/price/thousandsSeparator', ' ');
        }

        if ($format === self::FORMAT_10) {
            return apply_filters('vehica/price/thousandsSeparator', '\'');
        }

        return apply_filters('vehica/price/thousandsSeparator', '');
    }

    /**
     * @var array
     */
    protected $settings = [
        self::SIGN,
        self::SIGN_POSITION,
        self::NAME,
        self::FORMAT,
    ];
    /**
     *
     * 100,000,000
     * 100.000.000
     * 100 000 000
     * 100,000,000.00
     * 100.000.000,00
     * 100 000 000 00
     * 100000000.00
     * 10,00,00,000.00
     */

    /**
     * @return array
     */
    public static function getFormats()
    {
        return [
            self::FORMAT_5 => esc_html__('100,000,000', 'vehica-core'),
            self::FORMAT_7 => esc_html__('100.000.000', 'vehica-core'),
            self::FORMAT_10 => esc_html__('100\'000\'000', 'vehica-core'),
            self::FORMAT_9 => esc_html__('100 000 000', 'vehica-core'),
            self::FORMAT_1 => esc_html__('100,000,000.00', 'vehica-core'),
            self::FORMAT_3 => esc_html__('100.000.000,00', 'vehica-core'),
            self::FORMAT_4 => esc_html__('100 000 000,00', 'vehica-core'),
            self::FORMAT_6 => esc_html__('100 000 000.00', 'vehica-core'),
            self::FORMAT_2 => esc_html__('100000000.00', 'vehica-core'),
            self::FORMAT_8 => esc_html__('10,00,00,000', 'vehica-core'),
        ];
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->setMeta(self::FORMAT, $format);
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        $format = $this->getMeta(self::FORMAT);

        if (empty($format)) {
            return self::FORMAT_1;
        }

        return $format;
    }

    /**
     * @return string
     */
    public function getFormatDisplay()
    {
        $formats = self::getFormats();
        $format = $this->getFormat();

        if (!isset($formats[$format])) {
            return '';
        }

        return $formats[$format];
    }

    /**
     * @param string $sign
     */
    public function setSign($sign)
    {
        $this->setMeta(self::SIGN, $sign);
    }

    /**
     * @return string
     */
    public function getSign()
    {
        $sign = $this->getMeta(self::SIGN);

        if (empty($sign)) {
            return '$';
        }

        return $sign;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->setMeta(self::NAME, $name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        $name = $this->getMeta(self::NAME);

        if (empty($name)) {
            return esc_html__('USD', 'vehica-core');
        }

        return $name;
    }

    /**
     * @param string $signPosition
     */
    public function setSignPosition($signPosition)
    {
        $this->setMeta(self::SIGN_POSITION, $signPosition);
    }

    /**
     * @return string
     */
    public function getSignPosition()
    {
        $signPosition = $this->getMeta(self::SIGN_POSITION);

        if (empty($signPosition)) {
            return self::SIGN_POSITION_BEFORE;
        }

        return $signPosition;
    }

    /**
     * @return bool
     */
    public function isSignPositionBefore()
    {
        return $this->getSignPosition() === self::SIGN_POSITION_BEFORE;
    }

    /**
     * @return bool
     */
    public function isSignPositionAfter()
    {
        return $this->getSignPosition() === self::SIGN_POSITION_AFTER;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'key' => $this->getKey(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'sign' => $this->getSign(),
            'signPosition' => $this->getSignPosition(),
            'format' => $this->getFormat(),
            'displayFormat' => $this->getFormatDisplay(),
            'decimal_separator' => $this->getDecimalSeparator(),
            'thousands_separator' => $this->getThousandsSeparator(),
            'decimal_places' => $this->getDecimalPlaces(),
        ];
    }

    /**
     * @return Currency|false
     */
    public static function create()
    {
        $term = wp_create_term(
            'vehica_currency_' . mt_rand(1, 100000),
            self::CURRENCIES
        );

        if (is_wp_error($term)) {
            return false;
        }

        return self::getById($term['term_id']);
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function destroy($id)
    {
        return wp_delete_term($id, self::CURRENCIES) === true;
    }

    /**
     * @param $id
     */
    public static function setDefault($id)
    {
        $id = (int)$id;
        update_option(self::DEFAULT_CURRENCY_ID, $id);
    }

    /**
     * @return int
     */
    public static function getDefaultId()
    {
        return (int)get_option(self::DEFAULT_CURRENCY_ID);
    }

    /**
     * @return bool
     */
    public function isCurrent()
    {
        return $this->getId() === vehicaApp('current_currency')->getId();
    }

    public function setCurrent()
    {
        setcookie('vehicaCurrentCurrency', $this->getId(), time() + 60 * 60 * 30, '/');
    }

}
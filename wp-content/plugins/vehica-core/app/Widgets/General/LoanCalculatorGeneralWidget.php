<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Exception;
use Vehica\Field\Fields\Price\Currency;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Price\PriceField;

/**
 * Class LoanCalculatorGeneralWidget
 * @package Vehica\Widgets\General
 */
class LoanCalculatorGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_loan_calculator_general_widget';
    const TEMPLATE = 'general/loan_calculator';

    /**
     * LoanCalculatorGeneralWidget constructor.
     * @param array $data
     * @param null $args
     * @throws Exception
     */
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        wp_register_script('autoNumeric', vehicaApp('url') . 'assets/js/autoNumeric.min.js', [], '4.6');
    }

    public function get_script_depends()
    {
        return ['autoNumeric'];
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Loan Calculator', 'vehica-core');
    }

    /**
     * @return string
     */
    public function getCurrencySign()
    {
        $currency = vehicaApp('current_currency');

        if (!$currency instanceof Currency) {
            return '$';
        }

        return $currency->getSign();
    }

    /**
     * @return PriceField|false
     */
    public function getPriceField()
    {
        return vehicaApp('price_fields')->first(false);
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'initial_price',
            [
                'label' => esc_html__('Initial Price', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'initial_rate',
            [
                'label' => esc_html__('Initial Rate', 'vehica-core'),
                'description' => 'If you need dynamic initial rate (e.g. lower % for 24+ months) please read <a target="_blank" href="https://support.vehica.com/support/solutions/articles/101000377081">this article</a>.',
                'type' => Controls_Manager::TEXT,
                'default' => 5
            ]
        );

        $this->add_control(
            'initial_months',
            [
                'label' => esc_html__('Initial Months', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => 36,
            ]
        );

        $this->add_control(
            'initial_down_payment',
            [
                'label' => esc_html__('Initial Down Payment', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'enable_dynamic_down_payment',
            [
                'label' => esc_html__('Calculate the down payment from the listing price', 'vehica-core'),
                'label_block' => true,
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0',
            ]
        );

        $this->add_control(
            'round_to_integer',
            [
                'label' => esc_html__('Round numbers to integer', 'listivo-core'),
                'label_block' => true,
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0',
            ]
        );

        $this->add_control(
            'dynamic_down_payment',
            [
                'label' => esc_html__('Price percentage', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                    'size' => 10,
                ],
                'size_units' => ['%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'enable_dynamic_down_payment' => '1'
                ]
            ]
        );

        $this->add_control(
            'vehica_text_before',
            [
                'label' => esc_html__('Text before', 'vehica-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Use our loan calculator to calculate payments over the life of your loan. Enter your information to see how much your monthly payments could be. You can adjust length of loan, down payment and interest rate to see how those changes raise or lower your payments.',
                    'vehica-core')
            ]
        );

        $this->add_control(
            'vehica_text_after',
            [
                'label' => esc_html__('Text after', 'vehica-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Title and other fees and incentives are not included in this calculation, which is an estimate only. Monthly payment estimates are for informational purpose and do not represent a financing offer from the seller of this vehicle. Other taxes may apply.',
                    'vehica-core')
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @return string
     */
    public function getTextBefore()
    {
        return $this->get_settings_for_display('vehica_text_before');
    }

    /**
     * @return bool
     */
    public function hasTextBefore()
    {
        return $this->getTextBefore() !== '';
    }

    /**
     * @return string
     */
    public function getTextAfter()
    {
        return $this->get_settings_for_display('vehica_text_after');
    }

    /**
     * @return bool
     */
    public function hasTextAfter()
    {
        return $this->getTextAfter() !== '';
    }

    /**
     * @return false|float|int
     */
    private function getCarPrice()
    {
        if (isset($_GET['id'])) {
            $carId = (int)$_GET['id'];
        } elseif (is_singular(Car::POST_TYPE)) {
            global $post;

            if (!$post) {
                return false;
            }

            $carId = $post->ID;
        } else {
            return false;
        }

        $car = Car::getById($carId);
        if (!$car instanceof Car) {
            return false;
        }

        $priceField = vehicaApp('price_fields')->first();
        if (!$priceField instanceof PriceField) {
            return false;
        }

        return $priceField->getValueByCurrency($car);
    }

    /**
     * @return float|int|string
     */
    public function getInitialPrice()
    {
        $carPrice = $this->getCarPrice();
        if ($carPrice) {
            return $carPrice;
        }

        return $this->get_settings_for_display('initial_price');
    }

    /**
     * @return int
     */
    public function getInitialMonths()
    {
        return $this->get_settings_for_display('initial_months');
    }

    /**
     * @return int
     */
    public function getInitialRate()
    {
        return $this->get_settings_for_display('initial_rate');
    }

    /**
     * @return int
     */
    public function getInitialDownPayment()
    {
        if (!$this->isDynamicDownPaymentEnabled()) {
            return $this->get_settings_for_display('initial_down_payment');
        }

        $price = $this->getCarPrice();
        if (!$price) {
            return $this->get_settings_for_display('initial_down_payment');
        }

        $dynamicValue = $this->getDynamicDownPaymentValue();
        $initialDownPayment = $price * $dynamicValue;

        if (empty($initialDownPayment)) {
            return $this->get_settings_for_display('initial_down_payment');
        }

        return $initialDownPayment;
    }

    /**
     * @return bool
     */
    private function isDynamicDownPaymentEnabled()
    {
        return !empty($this->get_settings_for_display('enable_dynamic_down_payment'));
    }

    /**
     * @return int
     */
    private function getDynamicDownPaymentValue()
    {
        $value = $this->get_settings_for_display('dynamic_down_payment');
        return (int)$value['size'] / 100;
    }

    /**
     * @return bool
     */
    public function roundToInteger()
    {
        return !empty((int)$this->get_settings_for_display('round_to_integer'));
    }

}
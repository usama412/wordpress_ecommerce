<?php /** @noinspection TransitiveDependenciesUsageInspection */

/** @noinspection ContractViolationInspection */


namespace Vehica\Panel;


use Vehica\Core\BaseCurrency;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\BasePost;
use WC_Product;
use WP_Query;

/**
 * Class PaymentPackage
 *
 * @package Vehica\Panel
 */
class PaymentPackage extends BasePost
{
    const POST_TYPE = 'vehica_package';
    const NUMBER = 'vehica_number';
    const EXPIRE = 'vehica_expire';
    const FEATURED_EXPIRE = 'vehica_featured_expire';
    const PRICE = 'vehica_price';
    const DISPLAY_PRICE = 'vehica_display_price';
    const LABEL = 'vehica_label';
    const PRODUCT_ID = 'vehica_product_id';

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->setMeta(self::NUMBER, (int)$number);
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return (int)$this->getMeta(self::NUMBER);
    }

    /**
     * @param int $expire
     */
    public function setExpire($expire)
    {
        $this->setMeta(self::EXPIRE, (int)$expire);
    }

    /**
     * @return int
     */
    public function getExpire()
    {
        return (int)$this->getMeta(self::EXPIRE);
    }

    /**
     * @param int $featuredExpire
     */
    public function setFeaturedExpire($featuredExpire)
    {
        $this->setMeta(self::FEATURED_EXPIRE, (int)$featuredExpire);
    }

    /**
     * @return int
     */
    public function getFeaturedExpire()
    {
        return (int)$this->getMeta(self::FEATURED_EXPIRE);
    }


    /**
     * @param $price
     */
    public function setPrice($price)
    {
        $this->setMeta(self::PRICE, $price);
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return (float)$this->getMeta(self::PRICE);
    }

    /**
     * @return string
     */
    public function getDisplayPrice()
    {
        return (string)$this->getMeta(self::DISPLAY_PRICE);
    }

    /**
     * @param string $displayPrice
     */
    public function setDisplayPrice($displayPrice)
    {
        $this->setMeta(self::DISPLAY_PRICE, $displayPrice);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return (string)$this->getMeta(self::LABEL);
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->setMeta(self::LABEL, $label);
    }

    /**
     * @return int
     */
    public function getStripePrice()
    {
        $price = $this->getPrice();
        $currency = BaseCurrency::getSelected();
        if (empty($currency->decimal)) {
            return (int)$price;
        }

        return $price * 100;
    }

    /**
     * @return string
     */
    public function getPostTypeKey()
    {
        return self::POST_TYPE;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        if (isset($data['number'])) {
            $this->setNumber($data['number']);
        }

        if (isset($data['expire'])) {
            $this->setExpire($data['expire']);
        }

        if (isset($data['featuredExpire'])) {
            $this->setFeaturedExpire($data['featuredExpire']);
        }

        if (isset($data['price'])) {
            $this->setPrice($data['price']);
        }

        if (isset($data['displayPrice'])) {
            $this->setDisplayPrice($data['displayPrice']);
        }

        if (isset($data['name'])) {
            $this->setName($data['name']);
        }

        if (isset($data['label'])) {
            $this->setLabel($data['label']);
        }
    }

    /**
     * @return array
     */
    protected function getJsonData()
    {
        return [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'price' => $this->getPrice(),
            'displayPrice' => $this->getDisplayPrice(),
            'number' => $this->getNumber(),
            'expire' => $this->getExpire(),
            'featuredExpire' => $this->getFeaturedExpire(),
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        $name = parent::getName();

        if (empty($name)) {
            return sprintf(esc_html__('Payment Package #%d', 'vehica-core'), $this->getId());
        }

        return $name;
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return (int)$this->getMeta(self::PRODUCT_ID);
    }

    /**
     * @return WC_Product|false
     */
    public function getProduct()
    {
        $productId = $this->getProductId();

        if (empty($productId)) {
            return false;
        }

        return wc_get_product($productId);
    }

    /**
     * @return bool
     */
    public function isProductAssigned()
    {
        return $this->getProduct() !== false;
    }

    /**
     * @param int $productId
     */
    public function assignProduct($productId)
    {
        $productId = (int)$productId;

        $this->setMeta(self::PRODUCT_ID, $productId);
    }

    /**
     * @param int $productId
     * @return false|PaymentPackage
     */
    public static function getByAssignedProduct($productId)
    {
        $query = new WP_Query([
            'post_type' => self::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'posts_per_page' => -1,
            'meta_key' => self::PRODUCT_ID,
            'meta_value' => (string)$productId
        ]);

        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach ($query->posts as $post) {
            return new self($post);
        }

        return false;
    }

}
<?php /** @noinspection ContractViolationInspection */


namespace Vehica\Panel;


use JsonSerializable;

/**
 * Class Package
 *
 * @package Vehica\Panel
 */
class Package implements JsonSerializable
{
    const KEY = 'key';
    const NUMBER = 'number';
    const EXPIRE = 'expire';
    const FEATURED_EXPIRE = 'featured_expire';

    /**
     * @var int
     */
    private $number;

    /**
     * @var int
     */
    private $expire;

    /**
     * @var int
     */
    private $featuredExpire;

    /**
     * Package constructor.
     *
     * @param int $number
     * @param int $expire
     * @param int $featuredExpire
     */
    public function __construct($number, $expire, $featuredExpire)
    {
        $this->number = (int)$number;
        $this->expire = (int)$expire;
        $this->featuredExpire = (int)$featuredExpire;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = (int)$number;
    }

    public function decreaseNumber()
    {
        $this->setNumber($this->getNumber() - 1);
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return self::EXPIRE . '_' . $this->getExpire() . '_' . self::FEATURED_EXPIRE . '_' . $this->getFeaturedExpire();
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->number);
    }

    /**
     * @return int
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * @return int
     */
    public function getFeaturedExpire()
    {
        return $this->featuredExpire;
    }

    /**
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            self::KEY => $this->getKey(),
            self::NUMBER => $this->getNumber(),
            self::EXPIRE => $this->getExpire(),
            self::FEATURED_EXPIRE => $this->getFeaturedExpire(),
        ];
    }

    /**
     * @param array $package
     *
     * @return Package
     */
    public static function create($package)
    {
        return new self(
            $package[self::NUMBER],
            $package[self::EXPIRE],
            $package[self::FEATURED_EXPIRE]
        );
    }

    /**
     * @param Package $package
     *
     * @return $this
     */
    public function addPackage(Package $package)
    {
        if ($this->getKey() !== $package->getKey()) {
            return $this;
        }

        $this->number = $this->getNumber() + $package->getNumber();

        return $this;
    }

    /**
     * @return Package
     */
    public static function getFree()
    {
        return new self(
            1,
            vehicaApp('settings_config')->getFreeListingExpire(),
            vehicaApp('settings_config')->getFreeListingFeaturedExpire()
        );
    }

    /**
     * @return Package
     */
    public static function getFreeWhenRegister()
    {
        return new self(
            vehicaApp('settings_config')->getRegisterPackageNumber(),
            vehicaApp('settings_config')->getRegisterPackageExpire(),
            vehicaApp('settings_config')->getRegisterPackageFeaturedExpire()
        );
    }

}
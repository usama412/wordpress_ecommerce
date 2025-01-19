<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Core\Model;

if (!defined('ABSPATH')) {
    exit;
}

use JsonSerializable;
use Vehica\Core\Model\Interfaces\Listable;

/**
 * Class Model
 * @package Vehica\Core\Model
 */
abstract class Model implements JsonSerializable, Listable
{

    public $model;

    /**
     * Model constructor.
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param $model
     * @return static
     */
    public static function make($model)
    {
        return new static($model);
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return int
     */
    abstract public function getId();

    /**
     * @return string
     */
    public function getKey()
    {
        return vehicaApp('prefix') . $this->getId();
    }

    /**
     * @param string $key
     * @return static|false
     */
    public static function getByKey($key)
    {
        $id = (int)str_replace(vehicaApp('prefix'), '', $key);
        return static::getById($id);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    abstract public function setMeta($key, $value);

    /**
     * @param string $key
     * @param bool $isSingle
     * @return mixed
     */
    abstract public function getMeta($key, $isSingle = true);

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return string
     */
    abstract public function getSlug();

    /**
     * @return string
     */
    abstract public function getUrl();

    /**
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
                'id' => $this->getId(),
                'name' => $this->getName(),
                'slug' => $this->getSlug(),
                'url' => $this->getUrl()
            ] + $this->getJsonData();
    }

    /**
     * @return array
     */
    protected function getJsonData()
    {
        return [];
    }

}
<?php /** @noinspection ContractViolationInspection */

/** @noinspection PhpUndefinedClassInspection */

namespace Vehica\Core;

if (!defined('ABSPATH')) {
    exit;
}

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Vehica\Core\Model\Interfaces\Listable;

/**
 * Class Collection
 *
 * @package Vehica\Core
 */
class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Collection constructor.
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->items = $items;
    }

    /**
     * @param array $items
     *
     * @return Collection
     */
    public static function make($items = [])
    {
        return new static($items);
    }

    /**
     * @return ArrayIterator
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @param mixed $key
     *
     * @return bool
     *
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    /**
     * @param mixed $key
     * @param mixed $value
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($key, $value)
    {
        if ($key === null) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    /**
     * @param mixed $key
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    /**
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->items);
    }

    /**
     * @return array
     * @noinspection PhpUndefinedClassInspection
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_map(static function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            }

            return $value;
        }, $this->items);
    }

    /**
     * @return array
     */
    public function all()
    {
        return array_values($this->items);
    }

    /**
     * @param Callable $callback
     *
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * @param Callable|null $callback
     *
     * @return Collection
     */
    public function filter(callable $callback = null)
    {
        if ($callback) {
            return new static(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
        }

        return new static(array_filter($this->items));
    }

    /**
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function first($default = null)
    {
        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach ($this->items as $item) {
            return $item;
        }

        return $default;
    }

    /**
     * @param Callable|null $callback
     * @param mixed|null $default
     *
     * @return mixed|null
     * @noinspection LoopWhichDoesNotLoopInspection
     */
    public function last(callable $callback = null, $default = null)
    {
        if ($callback === null) {
            return empty($this->items) ? $default : end($this->items);
        }

        $items = array_reverse($this->items);
        if ($callback === null) {
            if (empty($items)) {
                return $default;
            }

            foreach ($items as $item) {
                return $item;
            }
        }

        foreach ($items as $key => $item) {
            return $callback($item, $key);
        }

        return $default;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * @return bool
     */
    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }

    /**
     * @return Collection
     */
    public function keys()
    {
        return new static(array_keys($this->items));
    }

    /**
     * @param Callable $callback
     *
     * @return Collection
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

    /**
     * @param $items
     *
     * @return Collection
     */
    public function merge($items)
    {
        if ($items instanceof self) {
            $items = $items->all();
        }

        return self::make(array_merge($this->items, $items));
    }

    /**
     * @param Callable|null $callback
     *
     * @return Collection
     */
    public function sort(callable $callback = null)
    {
        $items = $this->items;
        $callback
            ? uasort($items, $callback)
            : asort($items);

        return new static($items);
    }

    /**
     * @param callable $callback
     *
     * @return bool|mixed
     */
    public function find(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key)) {
                return $item;
            }
        }

        return false;
    }

    /**
     * @return Collection
     */
    public function values()
    {
        return new static(array_values($this->items));
    }

    /**
     * @param string $item
     *
     * @return bool
     */
    public function contain($item)
    {
        return in_array($item, $this->items, true);
    }

    /**
     * @param int $offset
     * @param int $length
     *
     * @return Collection
     */
    public function slice($offset = 0, $length = null)
    {
        return self::make(array_slice($this->items, $offset, $length));
    }

    /**
     * @param string $separator
     *
     * @return string
     */
    public function implode($separator = ', ')
    {
        return implode($separator, $this->items);
    }

    /**
     * @return array
     */
    public function toList()
    {
        $list = [];
        $this->each(static function ($item) use (&$list) {
            if ($item instanceof Listable) {
                $list[$item->getId()] = $item->getName();
            } else {
                $list[] = $item;
            }
        });

        asort($list);

        return $list;
    }

    /**
     * @return Collection
     */
    public function shuffle()
    {
        $items = $this->items;
        /** @noinspection NonSecureShuffleUsageInspection */
        shuffle($items);

        return self::make($items);
    }

    /**
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
     * @param int $number
     * @param int $offset
     *
     * @return Collection
     */
    public function take($number, $offset = 0)
    {
        return self::make(array_slice($this->items, $offset, $number));
    }

    /**
     * @param int $number
     *
     * @return Collection
     */
    public function skip($number)
    {
        $counter = 0;
        $items = [];

        foreach ($this->items as $item) {
            if ($counter >= $number) {
                $items[] = $item;
            }
            $counter++;
        }

        return self::make($items);
    }

    /**
     * @param mixed $item
     * @return $this
     */
    public function add($item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return $this
     */
    public function unique()
    {
        $this->items = array_unique($this->items);

        return $this;
    }

    /**
     * @return $this
     */
    public function reverse()
    {
        $this->items = array_reverse($this->items);

        return $this;
    }

}
<?php


namespace Vehica\Core;


/**
 * Class Demo
 * @package Vehica\Core
 */
class Demo
{
    const URL = 'url';
    const NAME = 'name';
    const KEY = 'key';
    const IMAGE = 'image';
    const MEDIA_SOURCE = 'media_source';

    /**
     * @var array
     */
    private $demo;

    /**
     * Demo constructor.
     * @param array $demo
     */
    public function __construct(array $demo)
    {
        $this->demo = $demo;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->demo[self::NAME];
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->demo[self::KEY];
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->demo[self::IMAGE];
    }

    /**
     * @return string
     */
    public function getMediaSource()
    {
        return $this->demo[self::MEDIA_SOURCE] . '/wp-content/uploads/';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->demo[self::URL];
    }

}
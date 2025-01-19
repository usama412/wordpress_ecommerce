<?php

namespace Vehica\Core;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ServiceProvider
 * @package Vehica\Core
 */
abstract class ServiceProvider
{
    /**
     * @var App
     */
    protected $app;

    /**
     * ServiceProvider constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    abstract public function register();

}
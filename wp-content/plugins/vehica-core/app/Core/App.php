<?php

namespace Vehica\Core;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Managers\AdminScriptsManager;
use Vehica\Managers\ConfigManager;
use Vehica\Managers\DemosManager;
use Vehica\Managers\ImageManager;
use Vehica\Providers\DemosServiceProvider;
use Vehica\Providers\FieldServiceProvider;
use Vehica\Providers\ImageServiceProvider;
use Vehica\Providers\UserServiceProvider;
use Pimple\Container;

/**
 * Class App
 * @package Vehica\Core
 */
class App
{
    const APP_STATUS = 'vehica_status';
    const APP_STATUS_DEMO_INSTALLATION = 'demo_installation';

    /**
     * @var static
     */
    protected static $instance;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @return App
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function bind($key, $value)
    {
        $this->container[$key] = $value;

        return $value;
    }

    /**
     * @param string $key
     * @param callable $callable
     */
    public function factory($key, callable $callable)
    {
        $this->container[$key] = $this->container->factory($callable);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->container[$key]);
    }

    /**
     * @param string $key
     * @param mixed $param1
     * @param mixed $param2
     * @return mixed
     */
    public function get($key, $param1 = null, $param2 = null)
    {
        if (isset($this->container[$key])) {
            return $this->container[$key];
        }

        if (strpos($key, 'taxonomy_') !== false) {
            return $this->bind($key, FieldServiceProvider::getTaxonomy($key));
        }

        if ($key === 'image_url') {
            $key = 'image_url_' . $param1 . '_' . $param2;
            if (isset($this->container[$key])) {
                return $this->container[$key];
            }
            return $this->bind($key, ImageServiceProvider::getUrl($param1, $param2));
        }

        if ($key === 'image_srcset') {
            $key = 'image_srcset_' . $param1 . '_' . $param2;
            if (isset($this->container[$key])) {
                return $this->container[$key];
            }
            return $this->bind($key, ImageServiceProvider::getSrcset($param1, $param2));
        }

        if (strpos($key, 'user_') !== false) {
            return $this->bind($key, UserServiceProvider::getUser($key));
        }

        return false;
    }

    /**
     * @param string $url
     * @param string $path
     */
    public function init($url, $path)
    {
        $this->container = new Container();
        $this->container['path'] = $path;
        $this->container['config_path'] = $path . '/config/';
        $this->container['views_path'] = $path . '/views/';
        $this->container['metaboxes_path'] = $path . '/views/metaboxes/';
        $this->container['admin_page_path'] = $path . '/views/pages/admin.php';
        $this->container['url'] = $url;

        $status = get_option(self::APP_STATUS);
        if ($status === self::APP_STATUS_DEMO_INSTALLATION) {
            $demosProvider = new DemosServiceProvider($this);
            $demosProvider->register();
            $demoManager = new DemosManager($this);
            $demoManager->boot();
            $adminScriptsManager = new AdminScriptsManager($this);
            $adminScriptsManager->boot();
            $imageProvider = new ImageServiceProvider($this);
            $imageProvider->register();
            $imageManager = new ImageManager($this);
            $imageManager->boot();
            return;
        }

        /** @noinspection PhpIncludeInspection */
        $config = require $this->container['path'] . '/config/app.php';

        Collection::make($config['providers'])->each(function ($providerClass) {
            /* @var ServiceProvider $provider */
            $provider = new $providerClass($this);
            $provider->register();
        });

        if ($this->container['configs']->isEmpty()) {
            $configsManager = new ConfigManager($this);
            $configsManager->createConfigPosts();

            wp_redirect(admin_url());
            exit;
        }

        Collection::make($config['managers'])->each(function ($managerClass) {
            /* @var $manager Manager */
            $manager = new $managerClass($this);
            $manager->boot();
        });
    }

}
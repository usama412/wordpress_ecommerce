<?php

if (!did_action('elementor/loaded')) {
    return;
}

/**
 * @param mixed $key
 * @param mixed $param1
 * @param mixed $param2
 * @return mixed|\Vehica\Core\App
 */
function vehicaApp($key = null, $param1 = null, $param2 = null)
{
    if ($key !== null) {
        return \Vehica\Core\App::getInstance()->get($key, $param1, $param2);
    }

    return \Vehica\Core\App::getInstance();
}

/**
 * @param mixed $value
 * @return mixed
 */
function vehica_filter($value)
{
    return $value;
}

vehicaApp()->init(
    plugin_dir_url(__DIR__),
    dirname(__DIR__) . '/'
);
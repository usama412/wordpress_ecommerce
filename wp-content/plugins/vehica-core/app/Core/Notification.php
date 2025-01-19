<?php


namespace Vehica\Core;


/**
 * Class Notification
 * @package Vehica\Core
 */
class Notification
{
    const MAIL_CONFIRMATION = 'mail_confirmation';
    const RESET_PASSWORD = 'reset_password';
    const WELCOME_USER = 'welcome_user';
    const CAR_APPROVED = 'car_approved';
    const CAR_DECLINED = 'car_declined';
    const CAR_PENDING = 'car_pending';
    const NEW_CAR_PENDING = 'new_car_pending'; // for admin

    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $message;

    /**
     * @var array
     */
    public $vars;

    /**
     * @var bool
     */
    public $optional;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var string
     */
    public $description;

    /**
     * Notification constructor.
     * @param string $key
     * @param string $label
     * @param string $title
     * @param string $message
     * @param array $vars
     * @param bool $optional
     * @param bool $enabled
     * @param string $description
     */
    public function __construct($key, $label, $title, $message, $vars = [], $optional = true, $enabled = false, $description = '')
    {
        $this->key = $key;
        $this->label = $label;
        $this->title = $title;
        $this->message = $message;
        $this->vars = $vars;
        $this->optional = $optional;
        $this->enabled = $enabled;
        $this->description = $description;
    }

    /**
     * @param array $data
     * @return Notification
     */
    public static function create($data)
    {
        return new self($data['key'], $data['label'], $data['title'], $data['message'], $data['vars'], $data['optional'], $data['enabled'], $data['description']);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        if (!$this->optional) {
            return true;
        }

        return $this->enabled;
    }

    /**
     * @param string $key
     * @return Notification|false
     */
    public static function getByKey($key)
    {
        return vehicaApp('notifications')->find(static function ($notification) use ($key) {
            /* @var Notification $notification */
            return $notification->key === $key;
        });
    }

}
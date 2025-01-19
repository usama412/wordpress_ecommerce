<?php


namespace Vehica\Providers;


use Vehica\Core\Collection;
use Vehica\Core\Notification;
use Vehica\Core\ServiceProvider;

/**
 * Class NotificationsServiceProvider
 * @package Vehica\Providers
 */
class NotificationsServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('notifications', static function () {
            $notifications = get_option('vehica_notifications');

            /** @noinspection PhpIncludeInspection */
            return Collection::make(require vehicaApp('path') . '/config/notifications.php')
                ->map(static function ($notification) use ($notifications) {
                    if (!isset($notifications[$notification['key']])) {
                        return Notification::create($notification);
                    }

                    $n = $notifications[$notification['key']];

                    if (!empty($n['title'])) {
                        $notification['title'] = $n['title'];
                    }

                    if (!empty($n['message'])) {
                        $notification['message'] = $n['message'];
                    }

                    if (isset($n['enabled'])) {
                        $notification['enabled'] = !empty($n['enabled']);
                    } else {
                        $notification['enabled'] = false;
                    }

                    return Notification::create($notification);
                })->filter(static function ($notification) {
                    return $notification !== false;
                });
        });
    }

}
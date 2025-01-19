<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Core\Notification;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Config\Setting;
use Vehica\Model\User\User;

/**
 * Class NotificationsManager
 * @package Vehica\Managers
 */
class NotificationsManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_panel_save_notifications', [$this, 'save']);

        add_action('vehica/notification/' . Notification::MAIL_CONFIRMATION, [$this, 'mailConfirmation']);

        add_action('vehica/notification/' . Notification::RESET_PASSWORD, [$this, 'resetPassword'], 10, 2);

        add_action('vehica/notification/' . Notification::CAR_APPROVED, [$this, 'carApproved']);

        add_action('vehica/notification/' . Notification::CAR_DECLINED, [$this, 'carDeclined']);

        add_action('vehica/notification/' . Notification::CAR_PENDING, [$this, 'carPending']);

        add_action('vehica/notification/' . Notification::NEW_CAR_PENDING, [$this, 'newCarPending']);

        add_action('vehica/notification/' . Notification::WELCOME_USER, [$this, 'welcomeUser']);

        add_filter('wp_mail_from', static function ($mail) {
            if (empty(vehicaApp('settings_config')->getSenderMail())) {
                return $mail;
            }

            return vehicaApp('settings_config')->getSenderMail();
        });

        add_filter('wp_mail_from_name', static function ($name) {
            if (empty(vehicaApp('settings_config')->getSenderName())) {
                return $name;
            }

            return vehicaApp('settings_config')->getSenderName();
        });
    }

    /**
     * @param User $user
     */
    public function welcomeUser(User $user)
    {
        $notification = Notification::getByKey(Notification::WELCOME_USER);
        if (!$notification || !$notification->isEnabled()) {
            return;
        }

        $title = str_replace('{userDisplayName}', $user->getName(), $notification->title);
        $message = str_replace('{userDisplayName}', $user->getName(), $notification->message);

        $this->sendNotification($user->getMail(), $title, $message);
    }

    /**
     * @param User $user
     */
    public function mailConfirmation(User $user)
    {
        $notification = Notification::getByKey(Notification::MAIL_CONFIRMATION);
        if (!$notification) {
            return;
        }

        $confirmationUrl = $user->getConfirmationUrl();

        $title = str_replace('{userDisplayName}', $user->getName(), $notification->title);

        $message = str_replace(
            ['{userDisplayName}', '{confirmationLink}'],
            [$user->getName(), '<a href="' . $confirmationUrl . '">' . $confirmationUrl . '</a>'],
            $notification->message
        );

        $this->sendNotification($user->getMail(), $title, $message);
    }

    /**
     * @param User $user
     * @param string $link
     */
    public function resetPassword(User $user, $link)
    {
        $notification = Notification::getByKey(Notification::RESET_PASSWORD);
        if (!$notification) {
            return;
        }

        $title = str_replace('{userDisplayName}', $user->getName(), $notification->title);
        $message = str_replace(
            ['{userDisplayName}', '{resetPasswordLink}'],
            [$user->getName(), '<a href="' . $link . '">' . $link . '</a>']
            , $notification->message
        );

        $this->sendNotification($user->getMail(), $title, $message);
    }

    /**
     * @param Car $car
     */
    public function carApproved(Car $car)
    {
        $notification = Notification::getByKey(Notification::CAR_APPROVED);
        if (!$notification || !$notification->isEnabled()) {
            return;
        }

        $user = $car->getUser();
        if (!$user) {
            return;
        }

        $search = ['{userDisplayName}', '{listingLink}', '{listingName}'];
        $replace = [$user->getName(), '<a href="' . $car->getUrl() . '">' . $car->getUrl() . '</a>', $car->getName()];

        $title = str_replace($search, $replace, $notification->title);
        $message = str_replace($search, $replace, $notification->message);

        $this->sendNotification($user->getMail(), $title, $message);
    }

    /**
     * @param Car $car
     * @noinspection DuplicatedCode
     */
    public function carDeclined(Car $car)
    {
        $notification = Notification::getByKey(Notification::CAR_DECLINED);
        if (!$notification || !$notification->isEnabled()) {
            return;
        }

        $user = $car->getUser();
        if (!$user) {
            return;
        }

        $search = ['{userDisplayName}', '{listingName}'];
        $replace = [$user->getName(), $car->getName()];

        $title = str_replace($search, $replace, $notification->title);
        $message = str_replace($search, $replace, $notification->message);

        $this->sendNotification($user->getMail(), $title, $message);
    }

    /**
     * @param Car $car
     * @noinspection DuplicatedCode
     */
    public function carPending(Car $car)
    {
        $notification = Notification::getByKey(Notification::CAR_PENDING);
        if (!$notification || !$notification->isEnabled()) {
            return;
        }

        $user = $car->getUser();
        if (!$user) {
            return;
        }

        $search = ['{userDisplayName}', '{listingName}'];
        $replace = [$user->getName(), $car->getName()];

        $title = str_replace($search, $replace, $notification->title);
        $message = str_replace($search, $replace, $notification->message);

        $this->sendNotification($user->getMail(), $title, $message);
    }

    /**
     * @param User $user
     * @return string
     */
    private function getUserDisplayNameWithLink(User $user)
    {
        if ($user->isPrivateRole()) {
            return $user->getName();
        }

        return '<a href="' . $user->getUrl() . '">' . $user->getName() . '</a>';
    }

    /**
     * @param Car $car
     */
    public function newCarPending(Car $car)
    {
        $notification = Notification::getByKey(Notification::NEW_CAR_PENDING);
        if (!$notification || !$notification->isEnabled()) {
            return;
        }

        $user = $car->getUser();
        if (!$user) {
            return;
        }

        $package = $car->getPendingPackage();

        $search = [
            '{userDisplayName}',
            '{userDisplayNameWithLink}',
            '{listingName}',
            '{expire}',
            '{featuredExpire}'
        ];

        $replace = [
            $user->getName(),
            $this->getUserDisplayNameWithLink($user),
            $car->getName(),
            $package ? $package->getExpire() : '',
            $package ? $package->getFeaturedExpire() : '',
        ];

        $title = str_replace($search, $replace, $notification->title);
        $message = str_replace($search, $replace, $notification->message);

        $this->sendNotification(get_bloginfo('admin_email'), $title, $message);
    }

    public function save()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (isset($_POST['notifications'])) {
            $this->saveNotifications($_POST['notifications']);
        }

        vehicaApp('settings_config')->update($_POST, Setting::getNotificationSettings());

        wp_redirect(admin_url('admin.php?page=vehica_panel_notifications'));
        exit;
    }

    /**
     * @param $title
     * @param $rawMessage
     * @param $mail
     * @noinspection HtmlRequiredLangAttribute
     */
    private function sendNotification($mail, $title, $rawMessage)
    {
        if (is_rtl()) {
            $message = '<html ' . get_language_attributes() . '><body style="text-align:right; direction:rtl;">' . $rawMessage . '</body></html>';
        } else {
            $message = '<html ' . get_language_attributes() . '><body>' . $rawMessage . '</body></html>';
        }

        $message = apply_filters('vehica/notifications/message', $message, $rawMessage);

        wp_mail($mail, $title, nl2br($message), [
            'Content-Type: text/html; charset=UTF-8'
        ]);
    }

    /**
     * @param array $notifications
     */
    private function saveNotifications($notifications)
    {
        update_option('vehica_notifications', stripslashes_deep($notifications));
    }

}
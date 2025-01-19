<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\User\User;
use WPCF7_ContactForm;

/**
 * Class ContactFormManager
 *
 * @package Vehica\Managers
 */
class ContactFormManager extends Manager
{

    public function boot()
    {
        add_action('wpcf7_before_send_mail', [$this, 'sendMail']);
        add_filter('shortcode_atts_wpcf7', [$this, 'params'], 10, 3);
    }

    /**
     * @param array $out
     * @param array $pairs
     * @param array $atts
     *
     * @return array
     * @noinspection PhpUnusedParameterInspection
     */
    public function params($out, $pairs, $atts)
    {
        $attr = 'vehica-user-id';

        if (isset($atts[$attr])) {
            $out[$attr] = $atts[$attr];
        }

        return $out;
    }

    /**
     * @param WPCF7_ContactForm $contactForm
     */
    public function sendMail(WPCF7_ContactForm $contactForm)
    {
        if (!isset($_POST['vehica-user-id'])) {
            return;
        }

        $userId = (int)$_POST['vehica-user-id'];
        $user = User::getById($userId);

        if (!$user) {
            return;
        }

        $properties = $contactForm->get_properties();
        $properties['mail']['recipient'] = $user->getMail();

        $contactForm->set_properties($properties);
    }

}
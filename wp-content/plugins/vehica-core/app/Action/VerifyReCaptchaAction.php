<?php


namespace Vehica\Action;


use ReCaptcha\ReCaptcha;
use ReCaptcha\RequestMethod\CurlPost;

/**
 * Class VerifyReCaptchaAction
 * @package Vehica\Action
 */
class VerifyReCaptchaAction
{
    /**
     * @param string $action
     * @param string $token
     * @return bool
     */
    public static function verify($action, $token)
    {
        if (function_exists('curl_version')) {
            return (new ReCaptcha(vehicaApp('settings_config')->getRecaptchaSecret(), new CurlPost()))
                ->setExpectedAction($action)
                ->setScoreThreshold(apply_filters('vehica/reCaptcha/score', 0.5))
                ->verify($token)
                ->isSuccess();
        }

        return (new ReCaptcha(vehicaApp('settings_config')->getRecaptchaSecret()))
            ->setExpectedAction($action)
            ->setScoreThreshold(apply_filters('vehica/reCaptcha/score', 0.5))
            ->verify($token)
            ->isSuccess();
    }

}
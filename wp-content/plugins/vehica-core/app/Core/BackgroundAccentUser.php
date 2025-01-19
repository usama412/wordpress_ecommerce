<?php


namespace Vehica\Core;


/**
 * Interface BackgroundAccentUser
 * @package Vehica\Core
 */
interface BackgroundAccentUser
{
    /**
     * @return string
     */
    public function getBackgroundAccent();

    /**
     * @return bool
     */
    public function hasBackgroundAccent();

}
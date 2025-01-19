<?php


namespace Vehica\Model\User;


use Vehica\Core\Collection;

/**
 * Class UserRole
 * @package Vehica\Model\User
 */
class UserRole
{
    /**
     * @return Collection
     */
    public static function get()
    {
        global $wp_roles;

        return Collection::make($wp_roles->roles)->map(static function ($role) {
            return $role;
        });
    }

}
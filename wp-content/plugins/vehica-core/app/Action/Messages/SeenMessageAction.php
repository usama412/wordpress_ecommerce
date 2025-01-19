<?php


namespace Vehica\Action\Messages;


use Vehica\Managers\MessagesManager;

/**
 * Class SeenMessageAction
 * @package Vehica\Action\Messages
 */
class SeenMessageAction
{
    /**
     * @param int $id
     */
    public static function seen($id)
    {
        global $wpdb;

        $wpdb->update(
            MessagesManager::getTableName(),
            ['seen' => 1],
            ['id' => $id],
            ['id' => '%d']
        );
    }

}
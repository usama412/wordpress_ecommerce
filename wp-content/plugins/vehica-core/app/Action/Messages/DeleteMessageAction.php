<?php


namespace Vehica\Action\Messages;


use Vehica\Managers\MessagesManager;

/**
 * Class DeleteMessageAction
 * @package Vehica\Action\Messages
 */
class DeleteMessageAction
{
    /**
     * @param int $id
     * @return bool|int
     */
    public static function delete($id)
    {
        global $wpdb;

        return $wpdb->delete(
            MessagesManager::getTableName(),
            ['id' => $id],
            ['%d']
        );
    }

}
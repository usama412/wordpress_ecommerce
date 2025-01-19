<?php


namespace Vehica\Action\Messages;


use Vehica\Managers\MessagesManager;

/**
 * Class CreateMessageAction
 * @package Vehica\Action\Messages
 */
class CreateMessageAction
{
    /**
     * @param int $userFrom
     * @param int $userTo
     * @param string $message
     * @param string $createdAt
     * @param int $seen
     * @return false
     */
    public static function create($userFrom, $userTo, $message, $createdAt = '', $seen = 0)
    {
        if (empty($message)) {
            return false;
        }

        global $wpdb;

        if (empty($createdAt)) {
            $createdAt = date('Y-m-d H:i:s');
        }

        return $wpdb->insert(MessagesManager::getTableName(), [
            'user_from' => $userFrom,
            'user_to' => $userTo,
            'message' => nl2br($message),
            'seen' => $seen,
            'created_at' => $createdAt,
        ]);
    }

}
<?php /** @noinspection ContractViolationInspection */


namespace Vehica\Chat;


use DateTime;
use Exception;
use JsonSerializable;
use Vehica\Core\Collection;
use Vehica\Managers\MessagesManager;
use Vehica\Model\User\User;

/**
 * Class Conversation
 * @package Vehica\Chat
 */
class Conversation implements JsonSerializable
{
    /**
     * @var int
     */
    private $userId;

    /**
     * Conversation constructor.
     * @param  int  $userId
     */
    public function __construct($userId)
    {
        $this->userId = (int) $userId;
    }

    /**
     * @param  int  $limit
     * @param  int  $offset
     * @return Collection
     * @noinspection SqlNoDataSourceInspection
     */
    public function getMessages($limit = 200, $offset = 0)
    {
        global $wpdb;

        $tableName = MessagesManager::getTableName();
        $currentUserId = get_current_user_id();

        $results = $wpdb->get_results("
            SELECT * FROM {$tableName} 
                WHERE `user_from` = {$this->userId} AND `user_to` = {$currentUserId}
                    OR `user_from` = {$currentUserId} AND `user_to` = {$this->userId}
                ORDER BY `id` DESC
                LIMIT {$limit}
                OFFSET {$offset}
            ",
            ARRAY_A
        );

        if (!is_array($results)) {
            Collection::make();
        }


        $messages = Collection::make($results)->map(static function ($message) {
            return Message::make($message);
        })->reverse();

        try {
            $date = new DateTime(date('Y-m-d H:i:s'));
            foreach ($messages as $message) {
                /* @var Message $message */
                $date2 = new DateTime($message->getCreatedAt());
                if ($date->diff($date2)->i > 15) {
                    $date = $date2;
                    $message->setShowDate(true);
                } else {
                    $message->setShowDate(false);
                }
            }
        } catch (Exception $e) {
            return $messages;
        }

        return $messages;
    }

    /**
     * @return int
     * @noinspection SqlNoDataSourceInspection
     * @noinspection SqlDialectInspection
     */
    public function getCount()
    {
        global $wpdb;

        $tableName = MessagesManager::getTableName();
        $currentUserId = get_current_user_id();

        $results = $wpdb->get_results("
            SELECT COUNT(*) as count FROM {$tableName} 
                WHERE `user_from` = {$this->userId} AND `user_to` = {$currentUserId}
                    OR `user_from` = {$currentUserId} AND `user_to` = {$this->userId}
            ",
            ARRAY_A
        );

        if (empty($results)) {
            return 0;
        }

        return (int) $results[0]['count'];
    }

    /**
     * @param  int  $userId
     * @return Conversation
     */
    public static function make($userId)
    {
        return new self($userId);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $messages = $this->getMessages();
        $user = User::getById($this->userId);

        if (!$user) {
            return [];
        }

        return [
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'image' => $user->getImageUrl('vehica_100_100'),
                'url' => $user->getUrl(),
            ],
            'count' => $this->getCount(),
            'seen' => $this->seen(),
            'intro' => $messages->isNotEmpty() ? $messages->last() : false,
        ];
    }

    /**
     * @return bool
     */
    public function seen()
    {
        return !$this->getMessages()->find(function ($message) {
            /* @var Message $message */
            return !$message->seen() && $message->getUserFromId() === $this->userId;
        });
    }

    /**
     * @param  int  $userId
     * @return Collection
     * @noinspection SqlDialectInspection
     * @noinspection SqlNoDataSourceInspection
     */
    public static function getUserIds($userId)
    {
        global $wpdb;

        $tableName = MessagesManager::getTableName();

        $results = $wpdb->get_results("
            SELECT `user_from`, `user_to` FROM {$tableName} 
                WHERE `user_from` = {$userId} OR `user_to` = {$userId}
                ORDER BY `id` DESC
            ",
            ARRAY_A
        );

        $userIds = Collection::make();

        if (!empty($_GET[vehicaApp('user_rewrite')])) {
            $userIds[] = (int) $_GET[vehicaApp('user_rewrite')];
        }

        if (!empty($_POST['user'])) {
            $userIds[] = (int) $_POST['user'];
        }

        return $userIds->merge(Collection::make($results)->map(static function ($results) use ($userId) {
            return (int) $results['user_from'] === $userId ? (int) $results['user_to'] : (int) $results['user_from'];
        }))->unique()->filter(static function ($userId) {
            return !empty($userId) && get_userdata($userId);
        });
    }

    /**
     * @param  int  $userFrom
     * @param  int  $userTo
     */
    public static function setSeen($userFrom, $userTo)
    {
        global $wpdb;

        $tableName = MessagesManager::getTableName();

        $wpdb->update($tableName, ['seen' => 1], ['user_from' => $userFrom, 'user_to' => $userTo]);
    }

}
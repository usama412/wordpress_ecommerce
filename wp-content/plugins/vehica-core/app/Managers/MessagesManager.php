<?php


namespace Vehica\Managers;


use Vehica\Action\Messages\CreateMessageAction;
use Vehica\Chat\Conversation;
use Vehica\Core\Manager;
use Vehica\Model\User\User;

/**
 * Class MessagesManager
 * @package Vehica\Managers
 */
class MessagesManager extends Manager
{

    public function boot()
    {
        add_action('admin_init', [$this, 'createTable']);

        add_action('admin_post_vehica/messages/create', [$this, 'create']);

        add_action('admin_post_vehica/messages/reload', [$this, 'reload']);

        add_action('admin_post_vehica/messages/seen', [$this, 'seen']);

        add_action('admin_post_vehica/messages/get', [$this, 'messages']);

        add_action('wp_footer', [$this, 'countChecker']);

        add_action('admin_post_vehica/messages/checkCount', [$this, 'checkCount']);
    }

    /** @noinspection SqlNoDataSourceInspection
     * @noinspection SqlDialectInspection
     */
    public function createTable()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        global $wpdb;
        $charsetCollate = $wpdb->get_charset_collate();

        $tableName = self::getTableName();

        $statement = "CREATE TABLE `{$tableName}` (
            id bigint(20) NOT NULL auto_increment,
            user_from bigint(20) UNSIGNED NOT NULL,
            user_to bigint(20) UNSIGNED NOT NULL,
            created_at datetime NOT NULL,
            message longtext,
            seen tinyint(1) UNSIGNED DEFAULT 0,
            PRIMARY KEY  (id)
        ) $charsetCollate;";

        maybe_create_table($tableName, $statement);
    }

    public static function getTableName()
    {
        global $wpdb;

        return $wpdb->prefix . 'vehica_messages';
    }

    public function create()
    {
        if (empty($_POST['message']) || empty($_POST['userId'])) {
            $this->response(false);
            return;
        }

        $message = sanitize_textarea_field($_POST['message']);
        $userTo = (int)$_POST['userId'];
        $limit = !empty($_POST['limit']) ? (int)$_POST['limit'] : 200;

        if (!empty(trim($message))) {
            CreateMessageAction::create(get_current_user_id(), $userTo, $message);
        }

        $this->response(true, [
            'messages' => Conversation::make($userTo)->getMessages($limit),
        ]);
    }

    /**
     * @param bool $success
     * @param array $additional
     */
    private function response($success = true, $additional = [])
    {
        echo json_encode([
                'success' => $success
            ] + $additional);
    }

    public function reload()
    {
        if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'vehica_messages_reload')) {
            $this->response(false);
            return;
        }

        $this->response(true, [
            'conversations' => User::getCurrent()->getConversations()
        ]);
    }

    public function seen()
    {
        if (empty($_POST['userId']) || !is_user_logged_in()) {
            return;
        }

        Conversation::setSeen((int)$_POST['userId'], get_current_user_id());
    }

    public function messages()
    {
        if (empty($_POST['userId']) || !is_user_logged_in()) {
            return;
        }

        $conversation = Conversation::make((int)$_POST['userId']);
        $limit = !empty($_POST['limit']) ? (int)$_POST['limit'] : 200;

        echo json_encode([
            'messages' => $conversation->getMessages($limit),
            'count' => $conversation->getCount(),
        ]);
    }

    public function countChecker()
    {
        if (!is_user_logged_in()) {
            return;
        }
        ?>
        <div class="vehica-app">
            <vehica-message-count-checker
                    request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/messages/checkCount')); ?>"
            >
                <div slot-scope="checker"></div>
            </vehica-message-count-checker>
        </div>
        <?php
    }

    public function checkCount()
    {
        if (!is_user_logged_in()) {
            return;
        }

        echo User::getCurrent()->getNotSeenConversationNumber();
    }

}
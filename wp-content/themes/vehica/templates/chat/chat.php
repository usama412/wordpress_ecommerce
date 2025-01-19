<h1>chat</h1>

<?php
$user = \Vehica\Model\User\User::getCurrent();
return;
?>

<div class="vehica-app">
    <vehica-chat
            :user-id="<?php echo esc_attr($user->getId()); ?>"
            :initial-conversations="<?php echo htmlspecialchars(json_encode($user->getConversations())); ?>"
            :check-interval="15000"
            request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/messages/reload')); ?>"
            vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_messages_reload')); ?>"
    >
        <div slot-scope="chat" class="vehica-chat">
            <vehica-chat-conversation
                    :user-id="chat.userTo"
                    :conversations="chat.conversations"
            >
                <div slot-scope="conversation">
                    <div v-for="message in conversation.messages">
                        {{ message.message }}
                    </div>
                </div>
            </vehica-chat-conversation>

            <vehica-chat-create-message
                    v-if="chat.userTo"
                    :user-id="chat.userTo"
                    request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/messages/create')); ?>"
            >
                <div slot-scope="createMessage">
                    <form @submit.prevent="createMessage.onCreate">
                        <input
                                @input="createMessage.setMessage($event.target.value)"
                                :value="createMessage.message"
                                type="text"
                        >

                        <button><?php echo esc_html(vehicaApp('send_string')); ?></button>
                    </form>
                </div>
            </vehica-chat-create-message>
        </div>
    </vehica-chat>
</div>
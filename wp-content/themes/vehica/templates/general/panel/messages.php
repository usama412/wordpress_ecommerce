<?php
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

$vehicaUser = \Vehica\Model\User\User::getCurrent();
?>
<template>
    <vehica-chat
            :user-id="<?php echo esc_attr($vehicaUser->getId()); ?>"
            :initial-conversations="<?php echo htmlspecialchars(json_encode($vehicaUser->getConversations())); ?>"
            :check-interval="15000"
            request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/messages/reload')); ?>"
            seen-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/messages/seen')); ?>"
            messages-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/messages/get')); ?>"
            message-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/messages/create')); ?>"
            vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_messages_reload')); ?>"
            :limit="15"
        <?php if (isset($_GET['view']) && $_GET['view'] === 'msg') : ?>
            initial-tab="messages"
        <?php endif; ?>
        <?php if (!empty($_GET['user'])) : ?>
            :user="<?php echo esc_attr((int)$_GET['user']); ?>"
        <?php endif; ?>
    >
        <div
                slot-scope="chat"
                :class="{'vehica-chat__tab--users': chat.currentTab === 'users', 'vehica-chat__tab--messages': chat.currentTab === 'messages'}"
        >
            <div v-if="chat.conversations.length === 0">
                <div class="vehica-panel-list vehica-container">
                    <div class="vehica-panel-list__elements">
                        <div class="vehica-panel__car-list__cars">
                            <h2 class="vehica-panel-list-no-found">
                                <?php echo esc_html(vehicaApp('no_messages_string')); ?>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="chat.conversations.length > 0">
                <div class="vehica-chat">
                    <div class="vehica-chat__users">
                        <h2 class="vehica-chat__title-chats"><?php echo esc_html(vehicaApp('chats_string')); ?></h2>
                        <div class="vehica-chat__users">
                            <div
                                    class="vehica-chat__user"
                                    :class="{'vehica-chat__user--active': chat.conversation && chat.conversation.user.id === conversation.user.id, 'vehica-chat__user--not-seen': !conversation.seen}"
                                    v-for="conversation in chat.conversations"
                                    :key="conversation.user.id"
                                    @click.prevent="chat.setUserTo(conversation.user.id);chat.setTab('messages');"
                            >
                                <div class="vehica-chat__avatar-big">
                                    <img
                                            v-if="conversation.user.image !== '' && conversation.user.image !== false"
                                            :src="conversation.user.image"
                                            :alt="conversation.user.name"
                                    >

                                    <div
                                            v-if="conversation.user.image === '' || conversation.user.image === false"
                                            class="vehica-chat__avatar-big__placeholder"
                                    ></div>
                                </div>

                                <div class="vehica-chat__user-details">
                                    <div class="vehica-chat__user-name">
                                        {{ conversation.user.name }}
                                    </div>

                                    <div v-if="conversation.intro" class="vehica-chat__intro">
                                        <span v-if="conversation.intro.userFromId === <?php echo esc_attr(get_current_user_id()); ?>"><?php echo esc_html(vehicaApp('you_string')); ?> </span>
                                        <span>{{ conversation.intro.intro }}</span>
                                        <span class="vehica-chat__intro-date">({{ conversation.intro.formattedDate }})</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="vehica-chat__conversation">
                        <div class="vehica-chat__listing-info">
                            <div class="vehica-chat__listing-info__user-actions">
                                <div class="vehica-chat__listing-info__user-actions__inner">
                                    <div
                                            @click.prevent="chat.setTab('users')"
                                            class="vehica-chat__listing-info__arrow"
                                    >
                                        <div class="vehica-chat__listing-info__arrow__inner">
                                            <i class="fas fa-arrow-left"></i>
                                        </div>
                                    </div>

                                    <div class="vehica-chat__listing-info__heading">
                                        {{ chat.conversation.user.name }}
                                    </div>

                                    <div style="margin-left:auto;">
                                        <a
                                                class="vehica-chat__listing-info__user-action"
                                                :href="chat.conversation.user.url"
                                                target="_blank"
                                        >
                                            <i class="fas fa-eye"></i> <?php echo esc_html(vehicaApp('visit_profile_string')); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="vehica-chat__messages">
                            <div class="vehica-chat__messages-top">
                                <div v-if="chat.loadingMessages" class="vehica-chat__loader">
                                    <svg width="120" height="30" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg"
                                         fill="#fff">
                                        <circle cx="15" cy="15" r="15">
                                            <animate attributeName="r" from="15" to="15"
                                                     begin="0s" dur="0.8s"
                                                     values="15;9;15" calcMode="linear"
                                                     repeatCount="indefinite"/>
                                            <animate attributeName="fill-opacity" from="1" to="1"
                                                     begin="0s" dur="0.8s"
                                                     values="1;.5;1" calcMode="linear"
                                                     repeatCount="indefinite"/>
                                        </circle>
                                        <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                                            <animate attributeName="r" from="9" to="9"
                                                     begin="0s" dur="0.8s"
                                                     values="9;15;9" calcMode="linear"
                                                     repeatCount="indefinite"/>
                                            <animate attributeName="fill-opacity" from="0.5" to="0.5"
                                                     begin="0s" dur="0.8s"
                                                     values=".5;1;.5" calcMode="linear"
                                                     repeatCount="indefinite"/>
                                        </circle>
                                        <circle cx="105" cy="15" r="15">
                                            <animate attributeName="r" from="15" to="15"
                                                     begin="0s" dur="0.8s"
                                                     values="15;9;15" calcMode="linear"
                                                     repeatCount="indefinite"/>
                                            <animate attributeName="fill-opacity" from="1" to="1"
                                                     begin="0s" dur="0.8s"
                                                     values="1;.5;1" calcMode="linear"
                                                     repeatCount="indefinite"/>
                                        </circle>
                                    </svg>
                                </div>
                                <template v-if="chat.conversation && !chat.reload">
                                    <template v-if="chat.messages">
                                        <div
                                                v-if="chat.messages.length < chat.conversation.count && !chat.loadingMessages"
                                                class="vehica-chat__load-more"
                                        >
                                            <button
                                                    class="vehica-button vehica-button--small"
                                                    @click.prevent="chat.loadMore"
                                            >
                                                <?php echo esc_html(vehicaApp('load_more_string')); ?>
                                            </button>
                                        </div>

                                        <div
                                                v-for="message in chat.messages"
                                                :key="message.id"
                                                class="vehica-chat__message-wrapper"
                                                :class="{
                                                'vehica-chat__message-wrapper--me': message.userFromId === <?php echo esc_attr($vehicaUser->getId()); ?>,
                                                'vehica-chat__message-wrapper--other': message.userFromId !== <?php echo esc_attr($vehicaUser->getId()); ?>
                                            }"
                                        >
                                            <div v-if="message.showDate" class="vehica-chat__time">
                                                {{ message.formattedDate }}
                                            </div>

                                            <div class="vehica-chat__message">
                                                <div class="vehica-chat__avatar-small">
                                                    <img
                                                            v-if="chat.conversation.user.image !== '' && chat.conversation.user.image !== false"
                                                            :src="chat.conversation.user.image"
                                                            :alt="chat.conversation.user.name"
                                                    >

                                                    <div v-if="chat.conversation.user.image === '' || chat.conversation.user.image === false">
                                                        <div class="vehica-chat__avatar-small__placeholder"></div>
                                                    </div>
                                                </div>

                                                <div class="vehica-chat__text" v-html="message.message.linkify()"></div>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </div>

                            <div class="vehica-chat__messages-bottom" v-if="!chat.reload">
                                <form @submit.prevent="chat.onCreate">
                                    <textarea
                                            class="vehica-chat__editor"
                                            @input="chat.setMessage($event.target.value)"
                                            :value="chat.message"
                                            placeholder="<?php echo esc_attr(vehicaApp('aa_string')); ?>"
                                    ></textarea>

                                    <div class="vehica-chat__button">
                                        <button class="vehica-button vehica-button--icon vehica-button--icon--send">
                                            <?php echo esc_html(vehicaApp('send_string')); ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </vehica-chat>
</template>

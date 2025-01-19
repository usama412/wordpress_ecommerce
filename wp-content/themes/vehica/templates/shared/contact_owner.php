<?php
/* @var \Vehica\Widgets\Car\Single\ContactOwnerSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget, $vehicaCar;

$vehicaInitialMessage = '';

if (is_singular(\Vehica\Model\Post\Car::POST_TYPE)) :
    $vehicaInitialMessage = vehicaApp('i_m_interested_in_string') . ' ' . $vehicaCar->getName();
endif;

if ($vehicaCurrentWidget->isContactFormType()) : ?>
    <div class="vehica-contact-form">
        <?php $vehicaCurrentWidget->displayForm(); ?>
    </div>
<?php else : ?>
    <div class="vehica-send-pm vehica-app">
        <?php if (is_user_logged_in()) : ?>
            <vehica-chat-create-message
                    request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/messages/create')); ?>"
                    :user-id="<?php echo esc_attr($vehicaCurrentWidget->getUserId()); ?>"
                    redirect-url="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getMessagesPageUrl() . '&' . vehicaApp('user_rewrite') . '=' . $vehicaCurrentWidget->getUserId()); ?>&view=msg"
                    :same-user="<?php echo esc_attr($vehicaCurrentWidget->getUserId() === get_current_user_id() ? 'true' : 'false'); ?>"
                    same-user-text="<?php echo esc_attr(vehicaApp('same_user_message_string')); ?>"
                    initial-message="<?php echo esc_attr($vehicaInitialMessage); ?>"
            >
                <form slot-scope="props">
                    <div>
                        <textarea
                                @focusin="props.checkSameUser"
                                @input="props.setMessage($event.target.value)"
                                :value="props.message"
                                <?php if (is_author()) : ?>
                                    placeholder="<?php echo esc_attr(vehicaApp('write_your_message_here_string')); ?>"
                                <?php endif; ?>
                        ></textarea>
                    </div>

                    <div class="vehica-send-pm__vehica-button-wrapper">
                        <button class="vehica-button vehica-button--icon vehica-button--icon--send"
                                @click.prevent="props.onCreate">
                            <?php echo esc_html(vehicaApp('send_string')); ?>
                        </button>
                    </div>
                </form>
            </vehica-chat-create-message>
        <?php else : ?>
            <vehica-chat-create-message-not-logged>
                <div slot-scope="props">
                    <form>
                        <div>
                            <textarea @click.prevent="props.click"><?php echo esc_html($vehicaInitialMessage); ?></textarea>
                        </div>

                        <div class="vehica-send-pm__vehica-button-wrapper">
                            <button
                                    class="vehica-button vehica-button--icon vehica-button--icon--send"
                                    @click.prevent="props.click"
                            >
                                <?php echo esc_html(vehicaApp('send_string')); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </vehica-chat-create-message-not-logged>
        <?php endif; ?>
    </div>
<?php
endif;